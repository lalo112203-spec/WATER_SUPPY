<?php

namespace App\Http\Controllers;

use App\Models\RegistrationCode;
use Illuminate\Http\Request;

class RegistrationCodeController extends Controller
{
    public function index()
    {
        $codes = RegistrationCode::with('user')
            ->orderBy('is_used', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('registration-codes.index', compact('codes'));
    }

    public function store(Request $request)
    {
        // Generate a random 8-digit numeric code
        do {
            $code = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (RegistrationCode::where('code', $code)->exists());

        RegistrationCode::create([
            'code' => $code,
            'is_used' => false,
        ]);

        return back()->with('status', 'Code generated successfully: ' . $code);
    }

    public function destroy(RegistrationCode $registrationCode)
    {
        $registrationCode->delete();
        return back()->with('status', 'Code deleted.');
    }
}
