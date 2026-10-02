<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // System date override for historical / backlog records entry
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $customDate = \App\Models\SystemSetting::get('system_date');
                if (!empty($customDate)) {
                    $parsed = \Carbon\Carbon::parse($customDate)->setTime(12, 0, 0);
                    \Illuminate\Support\Facades\Date::setTestNow($parsed);
                    \Carbon\Carbon::setTestNow($parsed);
                    \Carbon\CarbonImmutable::setTestNow($parsed);
                }
            }
        } catch (\Throwable $e) {
            // Gracefully ignore during installation / migrations
        }

        // Dynamically detect scheme and host to seamlessly support ngrok, cloudflare, and live domains
        if (isset($_SERVER['HTTP_HOST'])) {
            $isSecure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
                        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
            
            $scheme = $isSecure ? 'https' : 'http';
            $dynamicUrl = $scheme . '://' . $_SERVER['HTTP_HOST'];
            
            // Set for both the config (which affects queues/emails) and the URL generator
            config(['app.url' => $dynamicUrl]);
            \Illuminate\Support\Facades\URL::forceRootUrl($dynamicUrl);
            
            if ($isSecure) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            }
        }

        // Configure Railway Database automatically
        if (isset($_ENV['DATABASE_URL'])) {
            $url = parse_url($_ENV['DATABASE_URL']);
            $driver = $url['scheme'] === 'postgres' ? 'pgsql' : $url['scheme'];
            
            config(['database.default' => $driver]);
            config(["database.connections.{$driver}.url" => $_ENV['DATABASE_URL']]);
        } elseif (isset($_ENV['MYSQL_URL'])) {
            config(['database.default' => 'mysql']);
            config(["database.connections.mysql.url" => $_ENV['MYSQL_URL']]);
        } elseif (isset($_ENV['RAILWAY_ENVIRONMENT'])) {
            $dbPath = storage_path('database.sqlite');
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): Password => Password::min(8));
    }
}
