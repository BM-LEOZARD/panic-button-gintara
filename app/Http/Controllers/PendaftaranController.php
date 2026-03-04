<?php

namespace App\Http\Controllers;

use App\Events\PendaftaranBaru;
use App\Models\Pendaftaran;
use App\Models\WilayahCover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function index()
    {
        $wilayah = WilayahCover::orderBy('nama')->get();
        return view('pendaftaran.index', compact('wilayah'));
    }

    public function store(Request $request)
    {
        $pendaftaranLama = Pendaftaran::whereIn('status', ['Ditolak', 'Dihapus'])
            ->where(fn($q) => $q->where('nik', $request->nik)
                ->orWhere('email', $request->email)
                ->orWhere('no_hp', $request->no_hp))
            ->get();

        foreach ($pendaftaranLama as $lama) {
            if ($lama->foto_ktp) {
                Storage::disk('public')->delete($lama->foto_ktp);
            }
            $lama->delete();
        }

        $request->validate([
            'wilayah_cover_id' => 'required|exists:wilayah_cover,id',
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('pendaftaran', 'username')->where(fn($q) => $q->whereIn('status', ['Menunggu', 'Disetujui'])),
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('pendaftaran', 'nik')->where(fn($q) => $q->whereIn('status', ['Menunggu', 'Disetujui'])),
                Rule::unique('pelanggan', 'nik'),
            ],
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'no_hp' => [
                'required',
                'string',
                'max:15',
                Rule::unique('pendaftaran', 'no_hp')->where(fn($q) => $q->whereIn('status', ['Menunggu', 'Disetujui'])),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('pendaftaran', 'email')->where(fn($q) => $q->whereIn('status', ['Menunggu', 'Disetujui'])),
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'RT' => 'required|string|max:5',
            'RW' => 'required|string|max:5',
            'GetBlockID' => 'required|string|max:10',
            'GetNumber' => 'required|string|max:10',
            'desa' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longtitude' => 'required|numeric|between:-180,180',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar atau sedang menunggu verifikasi.',
            'no_hp.unique' => 'Nomor HP sudah terdaftar atau sedang menunggu verifikasi.',
            'email.unique' => 'Email sudah terdaftar atau sedang menunggu verifikasi.',
            'latitude.required' => 'Lokasi rumah wajib ditentukan pada peta.',
            'longtitude.required' => 'Lokasi rumah wajib ditentukan pada peta.',
            'foto_ktp.required' => 'Foto KTP wajib diupload.',
        ]);

        $fotoPath = $request->file('foto_ktp')->store('foto_ktp', 'public');

        $ttl = $request->tempat_lahir . ', '
            . \Carbon\Carbon::parse($request->tanggal_lahir)->translatedFormat('d F Y');

        $pendaftaran = Pendaftaran::create([
            'wilayah_cover_id' => $request->wilayah_cover_id,
            'name' => $request->name,
            'username' => $request->username,
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'ttl' => $ttl,
            'alamat' => $request->alamat,
            'RT' => $request->RT,
            'RW' => $request->RW,
            'GetBlockID' => $request->GetBlockID,
            'GetNumber' => $request->GetNumber,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'latitude' => $request->latitude,
            'longtitude' => $request->longtitude,
            'foto_ktp' => $fotoPath,
            'status' => 'Menunggu',
        ]);

        broadcast(new PendaftaranBaru(
            $pendaftaran->load('wilayah')
        ));

        return redirect()->route('pendaftaran.sukses')
            ->with('success', 'Pendaftaran berhasil! Tunggu konfirmasi dari kami.');
    }
}
