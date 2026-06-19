<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'customer');
        })->with('roles')->latest()->paginate(10);

        $roles = Role::where('name', '!=', 'customer')->get();

        $employeeQuery = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'customer');
        });

        $stats = [
            'total_users' => (clone $employeeQuery)->count(),
            'active_users' => (clone $employeeQuery)->where('status', 'active')->count(),
            'new_this_month' => (clone $employeeQuery)->whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat menambahkan user.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        if ($request->role === 'customer') {
            return back()->with('error', 'Tidak dapat menambahkan user dengan role customer di sini.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat memperbarui user.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'role' => 'required|exists:roles,name',
        ]);

        if ($request->role === 'customer') {
            return back()->with('error', 'Tidak dapat mengubah role user menjadi customer.');
        }

        if ($user->id === auth()->id() && $request->role !== $user->roles->first()?->name) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->name = $request->name;
        $user->status = $request->status;
        $user->save();

        $user->syncRoles([$request->role]);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat menghapus user.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        User::destroy($user->id);
        return back()->with('success', 'User berhasil dihapus.');
    }
}
