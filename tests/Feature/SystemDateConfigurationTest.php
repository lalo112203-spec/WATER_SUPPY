<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemDateConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customerType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerType = CustomerType::create([
            'name' => 'Residential',
            'base_charge' => 100,
            'usage_rate' => 15,
            'green_max' => 10,
            'orange_max' => 20,
            'red_max' => 30,
            'base_limit' => 10,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);
    }

    protected function tearDown(): void
    {
        \Illuminate\Support\Facades\Date::setTestNow(null);
        \Carbon\Carbon::setTestNow(null);
        \Carbon\CarbonImmutable::setTestNow(null);

        parent::tearDown();
    }

    public function test_admin_can_set_custom_system_date_via_settings_update()
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['settings_authorized' => true])
            ->post(route('settings.update'), [
                'admin_password_verification' => 'password123',
                'types' => [
                    $this->customerType->id => [
                        'base_charge' => 100,
                        'usage_rate' => 15,
                        'green_max' => 10,
                        'orange_max' => 20,
                        'red_max' => 30,
                        'base_limit' => 10,
                    ]
                ],
                'system_date' => '2024-05-15',
            ]);

        $response->assertRedirect(route('settings.index'));
        $this->assertEquals('2024-05-15', SystemSetting::get('system_date'));
        $this->assertEquals('2024-05-15', now()->format('Y-m-d'));
    }

    public function test_admin_can_quick_set_system_date()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('settings.set-date'), [
                'system_date' => '2023-11-20',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('2023-11-20', SystemSetting::get('system_date'));
        $this->assertEquals('2023-11-20', now()->format('Y-m-d'));
    }

    public function test_admin_can_reset_system_date_to_realtime()
    {
        SystemSetting::set('system_date', '2023-01-01');
        \Illuminate\Support\Facades\Date::setTestNow('2023-01-01 12:00:00');

        $this->assertEquals('2023-01-01', now()->format('Y-m-d'));

        $response = $this->actingAs($this->admin)
            ->post(route('settings.reset-date'));

        $response->assertSessionHas('success');
        $this->assertNull(SystemSetting::get('system_date'));
        $this->assertNotEquals('2023-01-01', now()->format('Y-m-d'));
    }

    public function test_bill_created_uses_the_configured_system_date()
    {
        // Set historical system date
        SystemSetting::set('system_date', '2024-03-10');
        $parsed = \Carbon\Carbon::parse('2024-03-10')->setTime(12, 0, 0);
        \Illuminate\Support\Facades\Date::setTestNow($parsed);
        \Carbon\Carbon::setTestNow($parsed);
        \Carbon\CarbonImmutable::setTestNow($parsed);

        $customer = Customer::create([
            'customer_id' => '1002',
            'name' => 'Maria Clara',
            'address' => 'Sample Street',
            'customer_type_id' => $this->customerType->id,
            'type' => 'Residential',
            'meter_reading' => 20,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('billing.store'), [
            'customer_id' => $customer->id,
            'new_reading' => 35,
            'consumption' => 15,
            'base_charge' => 100,
            'usage_charge' => 75,
            'billing_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ]);

        $bill = \App\Models\Bill::where('customer_id', $customer->id)->first();
        $this->assertNotNull($bill);
        $this->assertEquals('2024-03-10', $bill->billing_date->format('Y-m-d'));
        $this->assertEquals('2024-04-09', $bill->due_date->format('Y-m-d'));
    }

    public function test_non_admin_cannot_change_or_reset_system_date()
    {
        $regularUser = User::factory()->create(['role' => 'consumer']);

        $response1 = $this->actingAs($regularUser)
            ->post(route('settings.set-date'), [
                'system_date' => '2025-01-01',
            ]);
        $response1->assertStatus(403);

        $response2 = $this->actingAs($regularUser)
            ->post(route('settings.reset-date'));
        $response2->assertStatus(403);
    }
}
