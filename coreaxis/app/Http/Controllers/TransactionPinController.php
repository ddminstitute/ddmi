<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransactionPinController extends Controller
{
    public function showSetPin()
    {
        return view('auth.pin.set');
    }

    public function setPin(Request $request)
    {
        $data = $request->validate([
            'pin'             => 'required|digits:4|confirmed',
            'pin_confirmation' => 'required|digits:4',
        ]);

        auth()->user()->update([
            'transaction_pin' => Hash::make($data['pin']),
        ]);

        return back()->with('success', 'Transaction PIN set successfully.');
    }

    public function showVerifyPin()
    {
        return view('auth.pin.verify');
    }

    public function verifyPin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:4']);

        if (! Hash::check($request->pin, auth()->user()->transaction_pin)) {
            return back()->withErrors(['pin' => 'Incorrect transaction PIN.']);
        }

        // Store verification in session for 10 minutes
        session(['pin_verified_at' => now()->timestamp]);

        return redirect()->intended(route('dashboard'))->with('success', 'PIN verified.');
    }
}
