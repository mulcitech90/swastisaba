<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeModel as Periode;
use App\Models\TatananModel;
use App\Models\TRXTatananModel as TrxTatanan;
use App\Models\PertanyaanModel;
use App\Models\TRXPertanyaanModel as TrxPertanyaan;
use App\Models\KelembagaanModel as Lembaga;
use App\Models\TrxKelembagaanModel as TrxLembaga;
use App\Models\PertanyaanKelembagaanModel as PertanyaanLembaga;
use App\Models\TrxPertanyaanKelembagaanModel as TrxPertanyaanLembaga;
use App\Models\User;

class PeriodeController extends Controller
{
    public function periode()
    {
        $data = Periode::orderBy('id', 'DESC')->get();
        return view('admin.periode.tatanan',compact('data'));
    }
    // periode_store
    public function periode_store(Request $request)
    {
        try {
            $this->validate($request, [
                'start' =>'required',
                'end' =>'required',
            ]);

            // kondisi start tidak boleh sama dengan end
            $tahun = $request->start. '-'. $request->end;
            // check periode sudah tersedia atau tidak
            $check = Periode::where('periode', $tahun)->first();
            if ($check) {
                return json_encode([
                    'error' => true,
                   'message' => 'Periode sudah tersedia'
                ]);
            }
            $periode = new Periode;
            $periode->start_year = $request->start;
            $periode->end_year = $request->end;
            $periode->periode = $tahun;
            $periode->status = 0;
            $periode->save();

            return json_encode([
                'error' => false,
               'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return json_encode([
                'error' => true,
               'message' => $th->getMessage()
            ]);
        }
    }
    public function updateStatus(Request $request)
    {
        $data_tatanan = TatananModel::all();
        $data_pertanyaan = PertanyaanModel::all();
        $data_user = User::where('role', 'pemda')->get();
        // insert to tabel trx_tatanan dan trx_pertanyaan

        $checktatanan = TrxTatanan::where('id_periode', $request->periode_id)->first();
        if (!$checktatanan) {
            foreach ($data_tatanan as $key => $value) {
                $tatanan = new TrxTatanan();
                $tatanan->id_periode = $request->periode_id;
                $tatanan->id_model = '1';
                $tatanan->id_tatanan = $value->id;
                $tatanan->nama_tatanan = $value->nama_tatanan;
                $tatanan->id_indikator = $value->id_indikator;
                $tatanan->save();
            }
        }
        $checkpertanyaan = TrxPertanyaan::where('id_periode', $request->periode_id)->first();
        if (!$checkpertanyaan) {
            foreach ($data_user as $y => $e) {
                foreach ($data_pertanyaan as $key => $value) {
                    $pertanyaanTatanan = new TrxPertanyaan;
                    $pertanyaanTatanan->id_periode = $request->periode_id;
                    $pertanyaanTatanan->id_pertanyaan_asal = $value->id;
                    $pertanyaanTatanan->no_pertanyaan = $value->no_pertanyaan;
                    $pertanyaanTatanan->pertanyaan = $value->pertanyaan;
                    $pertanyaanTatanan->jawaban_a = $value->jawaban_a;
                    $pertanyaanTatanan->jawaban_b = $value->jawaban_b;
                    $pertanyaanTatanan->jawaban_c = $value->jawaban_c;
                    $pertanyaanTatanan->jawaban_d = $value->jawaban_d;
                    $pertanyaanTatanan->nilai_a = $value->nilai_a;
                    $pertanyaanTatanan->nilai_b = $value->nilai_b;
                    $pertanyaanTatanan->nilai_c = $value->nilai_c;
                    $pertanyaanTatanan->nilai_d = $value->nilai_d;
                    $pertanyaanTatanan->kat = $value->kat;
                    $pertanyaanTatanan->dinas_id = $value->dinas_id;
                    $pertanyaanTatanan->tatanan_id = $value->tatanan_id;
                    $pertanyaanTatanan->indikator_id = $value->indikator_id;
                    $pertanyaanTatanan->user_id = $e->id;
                    $pertanyaanTatanan->save();
                }
            }
        }
        $periodeId = $request->periode_id;
        $isChecked = $request->is_checked;

        // Temukan periode berdasarkan ID
        $periode = Periode::find($periodeId);

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        // Update status periode sesuai dengan nilai tombol switch
        $periode->status = $isChecked ? 1 : 0;
        $periode->save();

        return response()->json(['message' => 'Status periode berhasil diperbarui'], 200);

    }
    public function updateStatuslembaga(Request $request)
    {
        $data_lembaga = Lembaga::all();
        $data_pertanyaan = PertanyaanLembaga::all();
        $data_user = User::where('role', 'pemda')->get();

        // insert to tabel trx_tatanan dan trx_pertanyaan

        $checktatanan = TrxLembaga::where('id_periode', $request->periode_id)->first();
        if (!$checktatanan) {
            foreach ($data_lembaga as $key => $value) {
                $tatanan = new TrxLembaga();
                $tatanan->id_periode = $request->periode_id;
                $tatanan->id_lembaga_asal = $value->id;
                $tatanan->nama_kelembagaan = $value->nama_kelembagaan;
                $tatanan->save();
            }
        }
        $checkpertanyaan = TrxPertanyaanLembaga::where('id_periode', $request->periode_id)->first();
        if (!$checkpertanyaan) {
            foreach ($data_user as $key => $e) {
                foreach ($data_pertanyaan as $key => $value) {
                    $pertanyaanTatanan = new TrxPertanyaanLembaga;
                    $pertanyaanTatanan->id_periode = $request->periode_id;
                    $pertanyaanTatanan->id_pertanyaan_lembaga_asal = $value->id;
                    $pertanyaanTatanan->no_pertanyaan = $value->no_pertanyaan;
                    $pertanyaanTatanan->pertanyaan = $value->pertanyaan;
                    $pertanyaanTatanan->id_kelembagaan = $value->id_kelembagaan;
                    $pertanyaanTatanan->jawaban_a = $value->jawaban_a;
                    $pertanyaanTatanan->jawaban_b = $value->jawaban_b;
                    $pertanyaanTatanan->file = $value->file;
                    $pertanyaanTatanan->user_id = $e->id;
                    $pertanyaanTatanan->save();
                }
            }
        }

        $periodeId = $request->periode_id;
        $isChecked = $request->is_checked;

        // Temukan periode berdasarkan ID
        $periode = Periode::find($periodeId);

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        // Update status periode sesuai dengan nilai tombol switch
        $periode->status_lembaga = $isChecked ? 1 : 0;
        $periode->save();

        return response()->json(['message' => 'Status periode berhasil diperbarui'], 200);
    }
    public function periode_destroy($id)
    {
        TrxPertanyaan::where('id_periode', $id)->delete();
        TrxTatanan::where('id_periode', $id)->delete();
        TrxPertanyaanLembaga::where('id_periode', $id)->delete();
        TrxLembaga::where('id_periode', $id)->delete();
        $periode = Periode::find($id);

        if (!$periode) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 404);
        }

        // Hapus periode
        $periode->delete();

        return response()->json(['message' => 'Periode berhasil dihapus'], 200);
    }
}
