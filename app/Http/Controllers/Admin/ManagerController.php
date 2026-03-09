<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $managers = User::query()
            ->where('role', 'manager')

            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                        ->orWhere('full_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");

                    if (is_numeric($q)) {
                        $x->orWhere('id', $q);
                    }

                    if (strtolower($q) === 'active') {
                        $x->orWhere('is_active', true);
                    }

                    if (strtolower($q) === 'inactive') {
                        $x->orWhere('is_active', false);
                    }
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.managers.index', compact('managers', 'q'));
    }

    public function show(User $manager)
    {
        abort_unless($manager->role === 'manager', 404);

        return view('admin.managers.show', compact('manager'));
    }

    public function create()
    {
        return view('admin.managers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'full_name'  => ['nullable', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'shift'      => ['required', 'in:day,night'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $manager = User::create([
            'name'       => $data['name'],
            'full_name'  => $data['full_name'] ?? null,
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'shift'      => $data['shift'],
            'password'   => Hash::make($data['password']),
            'role'       => 'manager',
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.managers.show', $manager)
            ->with('success', 'Manager account created.');
    }

    public function edit(User $manager)
    {
        abort_unless($manager->role === 'manager', 404);

        return view('admin.managers.edit', compact('manager'));
    }

    public function update(Request $request, User $manager)
    {
        abort_unless($manager->role === 'manager', 404);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'full_name'  => ['nullable', 'string', 'max:255'],
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($manager->id),
            ],
            'phone'      => ['nullable', 'string', 'max:50'],
            'shift'      => ['required', 'in:day,night'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $manager->update($data);

        return redirect()
            ->route('admin.managers.show', $manager)
            ->with('success', 'Manager updated.');
    }

    public function toggle(User $manager)
    {
        abort_unless($manager->role === 'manager', 404);

        // 防止 admin toggle 自己 / 或意外处理非 manager
        abort_if($manager->id === auth()->id(), 403);

        $manager->update([
            'is_active' => !$manager->is_active,
        ]);

        return back()->with('success', 'Manager status updated.');
    }
}
