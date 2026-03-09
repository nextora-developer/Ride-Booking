<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ManagerAuthController extends Controller
{
    public function create()
    {
        return view('auth.manager.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt([
            'email'     => $data['email'],
            'password'  => $data['password'],
            'role'      => 'manager',
            'is_active' => 1,
        ], $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials or account inactive.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('manager.dashboard');
    }
}
