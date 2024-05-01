<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelembagaanModel;
use App\Models\PertanyaanKelembagaanModel;

class MasterKelembagaanController extends Controller
{
    // Method untuk menampilkan daftar kelembagaan
    public function index()
    {
        $data = KelembagaanModel::orderBy('id', 'DESC')->get();
        return view('admin.master-kelembagaan.index', compact('data'));
    }

    // Method untuk menyimpan data kelembagaan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Simpan data kelembagaan baru
        $kelembagaan = new KelembagaanModel();
        $kelembagaan->nama_kelembagaan = $request->nama;
        // Tambahkan atribut lainnya sesuai kebutuhan
        $kelembagaan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.kelembagaan')->with('success', 'Data kelembagaan berhasil ditambahkan!');
    }

    // Method untuk menghapus data kelembagaan
    public function destroy($id)
    {
        // Temukan dan hapus kelembagaan berdasarkan ID
        $kelembagaan = KelembagaanModel::findOrFail($id);
        $kelembagaan->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.kelembagaan')->with('success', 'Data kelembagaan berhasil dihapus!');
    }

    // Method untuk menampilkan form edit kelembagaan
    public function edit($id)
    {
        // Temukan kelembagaan berdasarkan ID
        $kelembagaan = KelembagaanModel::findOrFail($id);

        return response()->json($kelembagaan);
    }

    // Method untuk menyimpan perubahan pada data kelembagaan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Tambahkan validasi sesuai kebutuhan lainnya
        ]);

        // Temukan dan perbarui kelembagaan berdasarkan ID
        $kelembagaan = KelembagaanModel::findOrFail($id);
        $kelembagaan->nama_kelembagaan = $request->nama;
        // Perbarui atribut lainnya sesuai kebutuhan
        $kelembagaan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.kelembagaan')->with('success', 'Data kelembagaan berhasil diperbarui!');
    }
    // pertanyaan
    public function pertanyaan()
    {
        $data = PertanyaanKelembagaanModel::orderBy('id', 'DESC')->get();
        $lembaga = KelembagaanModel::all();
        return view('admin.master-kelembagaan.pertanyaan', compact('data','lembaga'));
    }
    // pertanyaanStore
    public function pertanyaanStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'pertanyaan' =>'required|string|max:255',
            'id_lembaga' =>'required',
        ]);

        // Simpan data kelembagaan baru
        $pertanyaan = new PertanyaanKelembagaanModel();
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->id_kelembagaan = $request->id_lembaga;
        // Tambahkan atribut lainnya sesuai kebutuhan
        $pertanyaan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.pertanyaan-lembaga')->with('success', 'Data pertanyaan berhasil ditambahkan!');
    }
    // pertanyaanEdit
    public function pertanyaanEdit($id)
    {
        // Temukan kelembagaan berdasarkan ID
        $pertanyaan = PertanyaanKelembagaanModel::findOrFail($id);
        $data =[
            'pertanyaan' => $pertanyaan,
            'lembaga' => KelembagaanModel::all(),
        ];
        return response()->json($data);
    }
    // pertanyaanUpdate
    public function pertanyaanUpdate(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' =>'required|string|max:255',
            'pilihlembaga' =>'required',
        ]);

        // Temukan dan perbarui kelembagaan berdasarkan ID
        $pertanyaan = PertanyaanKelembagaanModel::findOrFail($id);
        $pertanyaan->pertanyaan = $request->nama;
        $pertanyaan->id_kelembagaan = $request->pilihlembaga;
        $pertanyaan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.pertanyaan-lembaga')->with('success', 'Data pertanyaan berhasil diperbarui!');
    }
    // pertanyaanDestroy
    public function pertanyaanDestroy($id)
    {
        // Temukan dan hapus kelembagaan berdasarkan ID
        $pertanyaan = PertanyaanKelembagaanModel::findOrFail($id);
        $pertanyaan->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.pertanyaan-lembaga')->with('success', 'Data pertanyaan berhasil dihapus!');
    }


}
