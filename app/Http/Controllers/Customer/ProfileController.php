<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'full_name'  => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'ic_number'  => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($data);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function editPassword()
    {
        return view('customer.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors([
                'current_password' => '当前密码不正确。',
            ]);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // ✅ 防止旧 session 风险（可选但推荐）
        $request->session()->regenerate();

        return back()->with('status', '密码已更新。');
    }
}
