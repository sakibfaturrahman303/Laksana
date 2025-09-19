<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('pages.admin.usermanajemen.index', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,operator',
            'email' => 'nullable|unique:users',
        ]);

        // Batasi maksimal 4 admin
        if ($request->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 4) {
                return redirect()->route('users.index')
                    ->with('error', 'Maksimal hanya boleh ada 4 user dengan role admin.');
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email ?? null, // biar null kalau kosong
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|unique:users,name,'.$user->id,
            'role' => 'required|in:admin,operator',
            'email' => 'nullable|unique:users,email,'.$user->id,
        ]);

        // Jika role diganti ke admin, cek batas maksimal 4
        if ($request->role === 'admin' && $user->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 4) {
                return redirect()->route('users.index')
                    ->with('error', 'Maksimal hanya boleh ada 4 user dengan role admin.');
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user) {
        // Cek kalau user yang dihapus admin terakhir
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users.index')
                    ->with('error', 'Minimal harus ada 1 user dengan role admin. Admin terakhir tidak bisa dihapus.');
            }
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    public function roles() {
        return view('pages.admin.usermanajemen.role');
    }
}
