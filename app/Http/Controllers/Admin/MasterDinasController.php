<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DinasModel;

class MasterDinasController extends Controller
{
    // Method untuk menampilkan daftar dinas
    public function index()
    {
        $data = DinasModel::orderBy('id', 'DESC')->get();
        return view('admin.master-dinas.index', compact('data'));
    }

    // Method untuk menyimpan data dinas baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Simpan data dinas baru
        $dinas = new DinasModel();
        $dinas->nama_dinas = $request->nama;
        // Tambahkan atribut lainnya sesuai kebutuhan
        $dinas->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.dinas')->with('success', 'Data dinas berhasil ditambahkan!');
    }

    // Method untuk menghapus data dinas
    public function destroy($id)
    {
        // Temukan dan hapus dinas berdasarkan ID
        $dinas = DinasModel::findOrFail($id);
        $dinas->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.dinas')->with('success', 'Data dinas berhasil dihapus!');
    }

    // Method untuk menampilkan form edit dinas
    public function edit($id)
    {
        // Temukan dinas berdasarkan ID
        $dinas = DinasModel::findOrFail($id);
        return response()->json($dinas);
    }

    // Method untuk menyimpan perubahan pada data dinas
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Temukan dan perbarui dinas berdasarkan ID
        $dinas = DinasModel::findOrFail($id);
        $dinas->nama_dinas = $request->nama;
        // Perbarui atribut lainnya sesuai kebutuhan
        $dinas->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.dinas')->with('success', 'Data dinas berhasil diperbarui!');
    }
}
