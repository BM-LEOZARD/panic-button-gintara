<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins  = User::where('role', 'Admin')->latest()->get();
        $adminsNonaktif = User::where('role', 'Admin')->onlyTrashed()->latest()->get();

        return view('superadmin.data-admin.index', compact('admins', 'adminsNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.data-admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'role' => 'Admin',
            'password' => Hash::make('Admin123'),
        ]);

        return redirect()->route('superadmin.data-admin.index')
            ->with('success', 'Akun Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admin = User::where('role', 'Admin')->findOrFail($id);
        return view('superadmin.data-admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'Admin')->findOrFail($id);

        $request->validate([
            'no_hp' => 'required|string|max:20',
        ]);

        $admin->update([
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('superadmin.data-admin.index')
            ->with('success', 'No. HP Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::where('role', 'Admin')->findOrFail($id);
        $admin->delete();

        return redirect()->route('superadmin.data-admin.index')
            ->with('success', 'Akun Admin berhasil dinonaktifkan.');
    }

    public function restore($id)
    {
        $admin = User::where('role', 'Admin')->onlyTrashed()->findOrFail($id);
        $admin->restore();

        return redirect()->route('superadmin.data-admin.index')
            ->with('success', 'Akun Admin berhasil diaktifkan kembali.');
    }

    public function forceDelete($id)
    {
        $admin = User::where('role', 'Admin')->onlyTrashed()->findOrFail($id);
        $admin->forceDelete();

        return redirect()->route('superadmin.data-admin.index')
            ->with('success', 'Akun Admin berhasil dihapus secara permanen.');
    }
}
