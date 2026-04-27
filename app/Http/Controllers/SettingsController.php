<?php
 
namespace App\Http\Controllers;
 
use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;
 
class SettingsController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('profile.edit');
        }

        // Check if user is already authorized for this session
        if (!session('settings_authorized')) {
            return view('settings.lock');
        }
 
        $settings = [
            'regular_base_charge' => SystemSetting::get('regular_base_charge', 100),
            'commercial_base_charge' => SystemSetting::get('commercial_base_charge', 250),
            'regular_usage_rate' => SystemSetting::get('regular_usage_rate', 15),
            'commercial_usage_rate' => SystemSetting::get('commercial_usage_rate', 25),
            'alert_threshold' => SystemSetting::get('alert_threshold', 1000), 
            'alert_email' => SystemSetting::get('alert_email', ''),
            'regular_green_max' => SystemSetting::get('regular_green_max', 10),
            'regular_orange_max' => SystemSetting::get('regular_orange_max', 14),
            'regular_red_max' => SystemSetting::get('regular_red_max', 20),
            'commercial_green_max' => SystemSetting::get('commercial_green_max', 49),
            'commercial_orange_max' => SystemSetting::get('commercial_orange_max', 50),
            'commercial_red_max' => SystemSetting::get('commercial_red_max', 100),
            'regular_base_limit' => SystemSetting::get('regular_base_limit', 10),
            'commercial_base_limit' => SystemSetting::get('commercial_base_limit', 10),
            'global_additional_charges' => json_decode(SystemSetting::get('global_additional_charges', '[]'), true),
        ];
 
        return view('settings.index', compact('settings'));
    }

    public function authorize(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        session()->flash('settings_authorized', true);

        return redirect()->route('settings.index');
    }
 
    public function update(Request $request)
    {
        session()->flash('settings_authorized', true);
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
 
        $request->validate([
            'admin_password_verification' => 'required|current_password',
        ]);
 
        $validated = $request->validate([
            'regular_base_charge' => 'required|numeric|min:0',
            'commercial_base_charge' => 'required|numeric|min:0',
            'regular_orange_max' => 'required|numeric|min:0',
            'regular_red_max' => 'required|numeric|min:0',
            'commercial_orange_max' => 'required|numeric|min:0',
            'commercial_red_max' => 'required|numeric|min:0',
            'regular_usage_rate' => 'required|numeric|min:0',
            'commercial_usage_rate' => 'required|numeric|min:0',
            'alert_threshold' => 'nullable|numeric|min:0',
            'alert_email' => 'nullable|email',
            'regular_green_max' => 'nullable|numeric|min:0',
            'commercial_green_max' => 'nullable|numeric|min:0',
            'regular_base_limit' => 'nullable|numeric|min:0',
            'commercial_base_limit' => 'nullable|numeric|min:0',
            'additional_charge_names' => 'nullable|array',
            'additional_charge_amounts' => 'nullable|array',
        ]);
 
        // Build the deductions JSON
        $additionalCharges = [];
        if (isset($validated['additional_charge_names'])) {
            foreach ($validated['additional_charge_names'] as $index => $name) {
                if (!empty($name)) {
                    $additionalCharges[] = [
                        'name' => $name,
                        'amount' => floatval($validated['additional_charge_amounts'][$index] ?? 0)
                    ];
                }
            }
        }
        SystemSetting::set('global_additional_charges', json_encode($additionalCharges), 'json');

        // Basic validation for thresholds logic
        if ($validated['regular_red_max'] <= $validated['regular_orange_max']) {
            return redirect()->back()
                ->withErrors(['regular_red_max' => 'Regular red max must be greater than orange max'])
                ->withInput();
        }
 
        if ($validated['commercial_red_max'] <= $validated['commercial_orange_max']) {
            return redirect()->back()
                ->withErrors(['commercial_red_max' => 'Commercial red max must be greater than orange max'])
                ->withInput();
        }
 
        foreach ($validated as $key => $value) {
            if (in_array($key, ['additional_charge_names', 'additional_charge_amounts'])) continue;
            $type = ($key === 'alert_email') ? 'text' : 'number';
            SystemSetting::set($key, $value ?? '', $type);
        }
 
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
