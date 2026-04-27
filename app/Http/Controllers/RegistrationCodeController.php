<?php

namespace App\Http\Controllers;

use App\Models\RegistrationCode;
use Illuminate\Http\Request;

class RegistrationCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = RegistrationCode::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('customer_id', 'like', "%{$search}%");
                  });
            });
        }

        $codes = $query->orderBy('is_used', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

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
