<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TatananModel;
use App\Models\IndikatorModel;
use App\Models\PertanyaanModel;
use App\Models\DinasModel;

class MasterTatananController extends Controller
{
    // Method untuk menampilkan daftar tatanan
    public function index()
    {
        $data = TatananModel::orderBy('id', 'DESC')->get();
        $indikator = IndikatorModel::all();
        return view('admin.master-tatanan.index', compact('data', 'indikator'));
    }

    // Method untuk menyimpan data tatanan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'pilihanTatanan' => 'required|array',
        ]);

        // Simpan data tatanan baru
        $tatanan = new TatananModel();
        $tatanan->id_model = '1';
        $tatanan->nama_tatanan = $request->nama;
        // Tambahkan atribut lainnya sesuai kebutuhan
        $tatanan->id_indikator = json_encode($request->pilihanTatanan);
        $tatanan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.tatanan')->with('success', 'Data tatanan berhasil ditambahkan!');
    }

    // Method untuk menghapus data tatanan
    public function destroy($id)
    {
        // Temukan dan hapus tatanan berdasarkan ID
        $tatanan = TatananModel::findOrFail($id);
        $tatanan->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.tatanan')->with('success', 'Data tatanan berhasil dihapus!');
    }

    // Method untuk menampilkan form edit tatanan
    public function edit($id)
    {
        // Temukan tatanan berdasarkan ID
        $tatanan = TatananModel::findOrFail($id);
        $data = [
                'tatanan' => $tatanan,
                'indikator' => IndikatorModel::all(),
        ];
        return response()->json($data);
    }

    // Method untuk menyimpan perubahan pada data tatanan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'pilihanTatanan' => 'required|array',
        ]);

        // Temukan dan perbarui tatanan berdasarkan ID
        $tatanan = TatananModel::findOrFail($id);
        $tatanan->nama_tatanan = $request->nama;
        // Perbarui atribut lainnya sesuai kebutuhan
        $tatanan->id_indikator = json_encode($request->pilihanTatanan);
        $tatanan->save();
        $tatanan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.tatanan')->with('success', 'Data tatanan berhasil diperbarui!');
    }

      // pertanyaan
      public function pertanyaan()
      {
          $tatanan = TatananModel::orderBy('id', 'DESC')->get();
          $pertanyaan = PertanyaanModel::all();
          $dinas = DinasModel::all();
          return view('admin.master-tatanan.pertanyaan', compact('pertanyaan','tatanan','dinas'));
      }
      // pertanyaanStore
      public function pertanyaanStore(Request $request)
      {

        $request->validate([
            'tatanan' => 'required',
            'pertanyaan' => 'required',
            'kat' => 'required',
            'dinas' => 'required',
        ]);

        // Buat objek PertanyaanTatanan baru
        $pertanyaanTatanan = new PertanyaanModel();
        $pertanyaanTatanan->pertanyaan = $request->pertanyaan;
        $pertanyaanTatanan->jawaban_a = $request->jawaban_a;
        $pertanyaanTatanan->jawaban_b = $request->jawaban_b;
        $pertanyaanTatanan->jawaban_c = $request->jawaban_c;
        $pertanyaanTatanan->jawaban_d = $request->jawaban_d;
        $pertanyaanTatanan->nilai_a = $request->jawaban_a_select;
        $pertanyaanTatanan->nilai_b = $request->jawaban_b_select;
        $pertanyaanTatanan->nilai_c = $request->jawaban_c_select;
        $pertanyaanTatanan->nilai_d = $request->jawaban_d_select;
        $pertanyaanTatanan->kat = $request->kat;
        $pertanyaanTatanan->dinas_id = $request->dinas;
        $pertanyaanTatanan->tatanan_id = $request->tatanan;

        // Simpan data ke database
        $pertanyaanTatanan->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('master.pertanyaan-tatanan')->with('success', 'Data pertanyaan berhasil ditambahkan!');
      }
      // pertanyaanEdit
      public function pertanyaanEdit($id)
      {
          // Temukan kelembagaan berdasarkan ID
          $tatanan = TatananModel::orderBy('id', 'DESC')->get();
          $pertanyaan = PertanyaanModel::findOrFail($id);
          $dinas = DinasModel::all();
          $data =[
              'tatanan' => $tatanan,
              'pertanyaan' => $pertanyaan,
              'dinas' => $dinas,
          ];
          return response()->json($data);
      }
      // pertanyaanUpdate
      public function pertanyaanUpdate(Request $request, $id)
      {

        $pertanyaanTatanan = PertanyaanModel::findOrFail($id);
        // Perbarui data dengan input dari request
        $pertanyaanTatanan->pertanyaan = $request->pertanyaan;
        $pertanyaanTatanan->jawaban_a = $request->jawaban_a;
        $pertanyaanTatanan->jawaban_b = $request->jawaban_b;
        $pertanyaanTatanan->jawaban_c = $request->jawaban_c;
        $pertanyaanTatanan->jawaban_d = $request->jawaban_d;
        $pertanyaanTatanan->nilai_a = $request->jawaban_a_select;
        $pertanyaanTatanan->nilai_b = $request->jawaban_b_select;
        $pertanyaanTatanan->nilai_c = $request->jawaban_c_select;
        $pertanyaanTatanan->nilai_d = $request->jawaban_d_select;
        $pertanyaanTatanan->kat = $request->kat;
        $pertanyaanTatanan->dinas_id = $request->dinas;
        $pertanyaanTatanan->tatanan_id = $request->tatanan;

        // Simpan data yang diperbarui ke database
        $pertanyaanTatanan->save();

          // Redirect kembali dengan pesan sukses
          return redirect()->route('master.pertanyaan-tatanan')->with('success', 'Data pertanyaan berhasil diperbarui!');
      }
      // pertanyaanDestroy
      public function pertanyaanDestroy($id)
      {
          // Temukan dan hapus kelembagaan berdasarkan ID
          $pertanyaan = PertanyaanModel::findOrFail($id);
          $pertanyaan->delete();

          // Redirect kembali dengan pesan sukses
          return redirect()->route('master.pertanyaan-tatanan')->with('success', 'Data pertanyaan berhasil dihapus!');
      }
}
