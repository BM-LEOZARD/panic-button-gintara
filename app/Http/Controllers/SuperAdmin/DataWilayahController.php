<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WilayahCover;
use Illuminate\Http\Request;

class DataWilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wilayah = WilayahCover::all();
        return view('superadmin.data-wilayah.index', compact('wilayah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.data-wilayah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode_wilayah' => 'required|string|max:20|unique:wilayah_cover,kode_wilayah',
            'latitude' => 'required|numeric|between:-90,90',
            'longtitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:50|max:10000',
            'alamat' => 'required|string',
        ], [
            'kode_wilayah.unique' => 'Kode wilayah sudah digunakan.',
        ]);

        WilayahCover::create($request->all());

        return redirect()->route('superadmin.data-wilayah.index')
            ->with('success', 'Wilayah berhasil ditambahkan.');
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
        $wilayah = WilayahCover::findOrFail($id);
        return view('superadmin.data-wilayah.edit', compact('wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $wilayah = WilayahCover::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kode_wilayah' => 'required|string|max:20|unique:wilayah_cover,kode_wilayah,' . $id,
            'latitude' => 'required|numeric|between:-90,90',
            'longtitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:50|max:10000',
            'alamat' => 'required|string',
        ], [
            'kode_wilayah.unique' => 'Kode wilayah sudah digunakan.',
        ]);

        $wilayah->update($request->all());

        return redirect()->route('superadmin.data-wilayah.index')
            ->with('success', 'Wilayah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wilayah = WilayahCover::findOrFail($id);
        $wilayah->delete();

        return redirect()->route('superadmin.data-wilayah.index')
            ->with('success', 'Wilayah berhasil dihapus.');
    }
}
