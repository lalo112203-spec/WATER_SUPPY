<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'base_charge' => SystemSetting::get('base_charge', 500),
            'commercial_base_charge' => SystemSetting::get('commercial_base_charge', 250),
            'usage_rate' => SystemSetting::get('usage_rate', 50),
            'alert_threshold' => SystemSetting::get('alert_threshold', 1000), // fallback/old
            'alert_email' => SystemSetting::get('alert_email', ''),
            'regular_green_max' => SystemSetting::get('regular_green_max', 10),
            'regular_orange_max' => SystemSetting::get('regular_orange_max', 14),
            'regular_red_max' => SystemSetting::get('regular_red_max', 20),
            'commercial_green_max' => SystemSetting::get('commercial_green_max', 49),
            'commercial_orange_max' => SystemSetting::get('commercial_orange_max', 50),
            'commercial_red_max' => SystemSetting::get('commercial_red_max', 100),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_charge' => 'required|numeric|min:0',
            'commercial_base_charge' => 'required|numeric|min:0',
            'regular_orange_max' => 'required|numeric|min:0',
            'regular_red_max' => 'required|numeric|min:0',
            'commercial_orange_max' => 'required|numeric|min:0',
            'commercial_red_max' => 'required|numeric|min:0',
            'usage_rate' => 'nullable|numeric|min:0',
            'alert_threshold' => 'nullable|numeric|min:0',
            'alert_email' => 'nullable|email',
            'regular_green_max' => 'nullable|numeric|min:0',
            'commercial_green_max' => 'nullable|numeric|min:0',
        ]);

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
            SystemSetting::set($key, $value, 'number');
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
