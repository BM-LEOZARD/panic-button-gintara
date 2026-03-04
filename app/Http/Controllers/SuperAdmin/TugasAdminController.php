<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Events\TugasAdminDitugaskan;
use App\Http\Controllers\Controller;
use App\Models\TugasAdmin;
use App\Models\User;
use App\Models\WilayahCover;
use Illuminate\Http\Request;

class TugasAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'Admin')
            ->with('tugasAdmin.wilayah')
            ->latest()
            ->get();

        $wilayah = WilayahCover::orderBy('nama')->get();

        $wilayahJson = $wilayah->map(function ($w) {
            return [
                'id' => $w->id,
                'nama' => $w->nama,
                'kode' => $w->kode_wilayah,
            ];
        })->values();

        return view('superadmin.tugas-admin.index', compact('admins', 'wilayah', 'wilayahJson'));
    }

    public function create() {}

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'wilayah_cover_id' => 'required|exists:wilayah_cover,id',
        ]);

        $sudahAda = TugasAdmin::where('user_id', $request->user_id)
            ->where('wilayah_cover_id', $request->wilayah_cover_id)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Admin sudah memiliki tugas di wilayah tersebut.');
        }

        $tugas = TugasAdmin::create([
            'user_id' => $request->user_id,
            'wilayah_cover_id' => $request->wilayah_cover_id,
        ]);

        broadcast(new TugasAdminDitugaskan(
            $tugas->load('wilayah')
        ));

        return back()->with('success', 'Tugas wilayah berhasil ditambahkan.');
    }

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}

    public function destroy($id)
    {
        $tugas = TugasAdmin::findOrFail($id);
        $tugas->delete();

        return back()->with('success', 'Tugas wilayah berhasil dihapus.');
    }
}