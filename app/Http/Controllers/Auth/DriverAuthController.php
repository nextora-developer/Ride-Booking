<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class DriverAuthController extends Controller
{
    public function create()
    {
        return view('auth.driver.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ role check
        if (($user->role ?? null) !== 'driver') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account is not a driver.',
            ]);
        }

        return redirect()->route('driver.dashboard');
    }

    public function registerForm()
    {
        return view('auth.driver.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:120'], // display name / login name
            'full_name'             => ['required', 'string', 'max:120'],
            'ic_number'             => ['required', 'string', 'max:30'],
            'phone'                 => ['required', 'string', 'max:30'],

            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::defaults()],

            'car_plate'             => ['required', 'string', 'max:30'],
            'car_model'             => ['required', 'string', 'max:80'],

            'bank_name'             => ['required', 'string', 'max:60'],
            'bank_account'          => ['required', 'string', 'max:40'],

            'shift'                 => ['required', 'in:day,night'],
        ]);

        $user = User::create([
            'name'          => $data['name'],
            'full_name'     => $data['full_name'],
            'ic_number'     => strtoupper($data['ic_number']),
            'phone'         => $data['phone'],

            'email'         => strtolower($data['email']),
            'password'      => $data['password'],
            'car_plate'     => strtoupper($data['car_plate']),
            'car_model'     => $data['car_model'],

            'bank_name'     => $data['bank_name'],
            'bank_account'  => $data['bank_account'],

            'role'          => 'driver',
            'shift'         => $data['shift'],
            'driver_status' => 'approved',
        ]);

        return redirect()
            ->route('driver.login')
            ->with('success', 'Registration successfully.');
    }
}
