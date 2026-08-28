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
            'alert_threshold' => SystemSetting::get('alert_threshold', 1000), 
            'alert_email' => SystemSetting::get('alert_email', ''),
            'global_additional_charges' => json_decode(SystemSetting::get('global_additional_charges', '[]'), true),
        ];
 
        $customerTypes = \App\Models\CustomerType::all();

        return view('settings.index', compact('settings', 'customerTypes'));
    }

    public function authorize(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $systemLockPassword = SystemSetting::get('system_lock_password');
        
        if ($systemLockPassword) {
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $systemLockPassword)) {
                return back()->withErrors(['password' => __('The provided password does not match our records.')]);
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
                return back()->withErrors(['password' => __('The provided password does not match our records.')]);
            }
        }

        session()->put('settings_authorized', true);

        return redirect()->route('settings.index');
    }
 
    public function update(Request $request)
    {
        session()->put('settings_authorized', true);
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
 
        $request->validate([
            'admin_password_verification' => 'required',
        ]);

        $systemLockPassword = SystemSetting::get('system_lock_password');
        
        if ($systemLockPassword) {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, $systemLockPassword)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')])->withInput();
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, auth()->user()->password)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')])->withInput();
            }
        }
 
        $validated = $request->validate([
            'types' => 'required|array',
            'types.*.base_charge' => 'required|numeric|min:0',
            'types.*.usage_rate' => 'required|numeric|min:0',
            'types.*.green_max' => 'required|numeric|min:0',
            'types.*.orange_max' => 'required|numeric|min:0',
            'types.*.red_max' => 'required|numeric|min:0',
            'types.*.base_limit' => 'required|numeric|min:0',
            'alert_threshold' => 'nullable|numeric|min:0',
            'alert_email' => 'nullable|email',
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

        foreach ($validated['types'] as $typeId => $typeData) {
            if ($typeData['red_max'] <= $typeData['orange_max']) {
                return redirect()->back()
                    ->withErrors(['types.'.$typeId.'.red_max' => 'Red max must be greater than orange max'])
                    ->withInput();
            }
            \App\Models\CustomerType::where('id', $typeId)->update([
                'base_charge' => $typeData['base_charge'],
                'usage_rate' => $typeData['usage_rate'],
                'green_max' => $typeData['green_max'],
                'orange_max' => $typeData['orange_max'],
                'red_max' => $typeData['red_max'],
                'base_limit' => $typeData['base_limit'],
            ]);
        }
 
        if (isset($validated['alert_email'])) {
            SystemSetting::set('alert_email', $validated['alert_email'], 'text');
        }
        if (isset($validated['alert_threshold'])) {
            SystemSetting::set('alert_threshold', $validated['alert_threshold'], 'number');
        }
 
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }

    public function storeCustomerType(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:customer_types,name',
            'admin_password_verification' => 'required',
        ]);

        $systemLockPassword = SystemSetting::get('system_lock_password');
        
        if ($systemLockPassword) {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, $systemLockPassword)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')])->withInput();
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, auth()->user()->password)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')])->withInput();
            }
        }

        \App\Models\CustomerType::create([
            'name' => $validated['name'],
            'base_charge' => 100,
            'usage_rate' => 15,
            'green_max' => 15,
            'orange_max' => 25,
            'red_max' => 35,
            'base_limit' => 10,
        ]);

        return redirect()->back()->with('success', 'Customer type created successfully.');
    }

    public function storeCustomerTypeAjax(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:customer_types,name',
            'admin_password_verification' => 'required',
        ]);

        $systemLockPassword = SystemSetting::get('system_lock_password');
        
        if ($systemLockPassword) {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, $systemLockPassword)) {
                return response()->json(['error' => __('The provided password does not match our records.')], 422);
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, auth()->user()->password)) {
                return response()->json(['error' => __('The provided password does not match our records.')], 422);
            }
        }

        $validated = $request->only('name');

        $type = \App\Models\CustomerType::create([
            'name' => $validated['name'],
            'base_charge' => 100,
            'usage_rate' => 15,
            'green_max' => 15,
            'orange_max' => 25,
            'red_max' => 35,
            'base_limit' => 10,
        ]);

        return response()->json([
            'success' => true,
            'type' => $type
        ]);
    }

    public function destroyCustomerType(Request $request, \App\Models\CustomerType $customerType)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'admin_password_verification' => 'required',
        ]);

        $systemLockPassword = SystemSetting::get('system_lock_password');
        
        if ($systemLockPassword) {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, $systemLockPassword)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')]);
            }
        } else {
            if (!\Illuminate\Support\Facades\Hash::check($request->admin_password_verification, auth()->user()->password)) {
                return back()->withErrors(['admin_password_verification' => __('The provided password does not match our records.')]);
            }
        }

        foreach ($customerType->customers as $customer) {
            if ($customer->user) {
                $customer->user->delete();
            }
            $customer->bills()->delete();
            $customer->waterUsages()->delete();
            $customer->delete();
        }

        $customerType->delete();

        return redirect()->back()->with('success', 'Customer type and all associated customers deleted successfully.');
    }
}
