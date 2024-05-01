<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndikatorModel;

class MasterIndikatorController extends Controller
{
    // Method untuk menampilkan daftar tatanan
    public function index()
    {
        $data = IndikatorModel::orderBy('id', 'DESC')->get();
        return view('admin.master-indikator.index', compact('data'));
    }

    // Method untuk menyimpan data tatanan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Simpan data tatanan baru
        $indikator = new IndikatorModel();
        $indikator->nama_indikator = $request->nama;
        // Tambahkan atribut lainnya sesuai kebutuhan
        $indikator->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.indikator')->with('success', 'Data tatanan berhasil ditambahkan!');
    }

    // Method untuk menghapus data tatanan
    public function destroy($id)
    {
        // Temukan dan hapus tatanan berdasarkan ID
        $indikator = IndikatorModel::findOrFail($id);
        $indikator->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.indikator')->with('success', 'Data tatanan berhasil dihapus!');
    }

    // Method untuk menampilkan form edit tatanan
    public function edit($id)
    {
        // Temukan tatanan berdasarkan ID
        $indikator = IndikatorModel::findOrFail($id);
        return response()->json($indikator);
    }

    // Method untuk menyimpan perubahan pada data tatanan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Temukan dan perbarui tatanan berdasarkan ID
        $indikator = IndikatorModel::findOrFail($id);
        $indikator->nama_indikator = $request->nama;
        // Perbarui atribut lainnya sesuai kebutuhan
        $indikator->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.indikator')->with('success', 'Data tatanan berhasil diperbarui!');
    }
}
