<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KabkotaModel;

class MasterWilayahController extends Controller
{
    public function index()
    {
        $data = KabkotaModel::orderBy('id', 'DESC')->get();
        return view('admin.master-wilayah.index', compact('data'));
    }

    // Fungsi untuk menampilkan data yang akan diedit dalam modal
    public function edit($id)
    {
        $wilayah = KabkotaModel::find($id);
        return response()->json($wilayah);
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Simpan data wilayah baru
        $wilayah = new KabkotaModel();
        $wilayah->provinsi_id = '1';
        $wilayah->nama = $request->nama;
        $wilayah->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.wilayah')->with('success', 'Data wilayah berhasil ditambahkan!');
    }

    // Fungsi untuk update data wilayah
    public function update(Request $request, $id)
    {
        $wilayah = KabkotaModel::find($id);
        $wilayah->nama = $request->nama;
        $wilayah->save();
        return response()->json(['success' => true]);
    }

    // Fungsi untuk menghapus data wilayah
    public function delete($id)
    {
        $wilayah = KabkotaModel::find($id);
        $wilayah->delete();
        return response()->json(['success' => true]);
    }
}
