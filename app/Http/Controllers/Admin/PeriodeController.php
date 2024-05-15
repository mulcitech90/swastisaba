<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeModel as Periode;
use App\Models\PeriodeMainModel as MainPeriode;
use App\Models\TatananModel;
use App\Models\TRXTatananModel as TrxTatanan;
use App\Models\PertanyaanModel;
use App\Models\TRXPertanyaanModel as TrxPertanyaan;
use App\Models\KelembagaanModel as Lembaga;
use App\Models\TrxKelembagaanModel as TrxLembaga;
use App\Models\PertanyaanKelembagaanModel as PertanyaanLembaga;
use App\Models\TrxPertanyaanKelembagaanModel as TrxPertanyaanLembaga;
use App\Models\User;
use DB;

class PeriodeController extends Controller
{
    public function periode()
    {
        $data = Periode::join('periode_main', 'periode.id_main_periode', '=', 'periode_main.id')
        ->select('periode.*', 'periode_main.periode as periode_name')
        ->orderBy('periode.id', 'DESC')
        ->get();
        return view('admin.periode.tatanan',compact('data'));
    }
    // periode_store
    public function periode_store(Request $request)
    {
        try {
            $this->validate($request, [
                'start' => 'required|integer|lt:end', // memastikan start kurang dari end
                'end' => 'required|integer|gt:start', // memastikan end lebih besar dari start
            ]);

            $start = $request->start;
            $end = $request->end;
            $joinyears = $start . '-' . $end;

            // Periksa apakah ada periode yang bentrok di MainPeriode
            $exisMainPeriods = MainPeriode::whereBetween('start_year', [$start, $end])
                ->orWhereBetween('end_year', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_year', '<=', $start)
                        ->where('end_year', '>=', $end);
                })
                ->exists();

            if ($exisMainPeriods) {
                return response()->json([
                    'error' => true,
                    'message' => 'Periode sudah tersedia'
                ]);
            }

            // Simpan periode utama
            $mainperiode = new MainPeriode;
            $mainperiode->start_year = $start;
            $mainperiode->end_year = $end;
            $mainperiode->periode = $joinyears; // atau format lainnya yang Anda butuhkan
            $mainperiode->save();

            // Periksa apakah ada periode yang bentrok di Periode
            $existingPeriods = Periode::whereBetween('start_year', [$start, $end])
                ->orWhereBetween('end_year', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_year', '<=', $start)
                        ->where('end_year', '>=', $end);
                })
                ->exists();

            if ($existingPeriods) {
                return response()->json([
                    'error' => true,
                    'message' => 'Periode sudah tersedia'
                ]);
            }

            // Simpan setiap tahun dalam rentang sebagai entri terpisah
            for ($year = $start; $year <= $end; $year++) {
                $periode = new Periode;
                $periode->id_main_periode = $mainperiode->id;
                $periode->start_year = $year;
                $periode->end_year = $year;
                $periode->periode = $year; // atau format lainnya yang Anda butuhkan
                $periode->status = 0;
                $periode->save();

                // Jika $periode->id ada, lanjutkan dengan penyimpanan data tambahan
                if ($periode->id) {
                    // Dapatkan semua user pemda dalam database
                    $data_tatanan = TatananModel::all();
                    $data_pertanyaan = PertanyaanModel::all();
                    $data_user = User::where('role', 'pemda')->get();

                    // Insert ke tabel trx_tatanan dan trx_pertanyaan
                    $checktatanan = TrxTatanan::where('id_periode', $periode->id)->first();
                    if (!$checktatanan) {
                        foreach ($data_tatanan as $value) {
                            $tatanan = new TrxTatanan();
                            $tatanan->id_periode = $periode->id;
                            $tatanan->id_model = '1';
                            $tatanan->id_tatanan = $value->id;
                            $tatanan->nama_tatanan = $value->nama_tatanan;
                            $tatanan->id_indikator = $value->id_indikator;
                            $tatanan->save();
                        }
                    }

                    $checkpertanyaan = TrxPertanyaan::where('id_periode', $periode->id)->first();
                    if (!$checkpertanyaan) {
                        foreach ($data_user as $user) {
                            $data = [
                                'id_periode' => $periode->id,
                                'tahun_periode' => $periode->periode,
                                'id_user' => $user->id,
                            ];

                            // Insert data ke dalam tabel trx_main dan mendapatkan ID yang baru saja diinsert
                            $mainId = DB::table('trx_main')->insertGetId($data);

                            foreach ($data_pertanyaan as $pertanyaan) {
                                $pertanyaanTatanan = new TrxPertanyaan();
                                $pertanyaanTatanan->id_periode = $periode->id;
                                $pertanyaanTatanan->id_pertanyaan_asal = $pertanyaan->id;
                                $pertanyaanTatanan->no_pertanyaan = $pertanyaan->no_pertanyaan;
                                $pertanyaanTatanan->pertanyaan = $pertanyaan->pertanyaan;
                                $pertanyaanTatanan->jawaban_a = $pertanyaan->jawaban_a;
                                $pertanyaanTatanan->jawaban_b = $pertanyaan->jawaban_b;
                                $pertanyaanTatanan->jawaban_c = $pertanyaan->jawaban_c;
                                $pertanyaanTatanan->jawaban_d = $pertanyaan->jawaban_d;
                                $pertanyaanTatanan->nilai_a = $pertanyaan->nilai_a;
                                $pertanyaanTatanan->nilai_b = $pertanyaan->nilai_b;
                                $pertanyaanTatanan->nilai_c = $pertanyaan->nilai_c;
                                $pertanyaanTatanan->nilai_d = $pertanyaan->nilai_d;
                                $pertanyaanTatanan->kat = $pertanyaan->kat;
                                $pertanyaanTatanan->dinas_id = $pertanyaan->dinas_id;
                                $pertanyaanTatanan->tatanan_id = $pertanyaan->tatanan_id;
                                $pertanyaanTatanan->indikator_id = $pertanyaan->indikator_id;
                                $pertanyaanTatanan->user_id = $user->id;
                                $pertanyaanTatanan->id_main = $mainId;

                                $pertanyaanTatanan->save();
                            }
                        }
                    }
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Periode berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
               'message' => $th->getMessage()
            ]);
        }

    }

    public function updateStatus(Request $request)
    {
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


        $checkpertanyaan = TrxPertanyaanLembaga::where('id_periode', $request->periode_id)->first();
        if (!$checkpertanyaan) {
            foreach ($data_user as $key => $e) {
                $checktatanan = TrxLembaga::where('id_periode', $request->periode_id)->first();
                $main = DB::table('trx_main')->where('id_user', $e->id)->where('id_periode', $request->periode_id)->first();
                if (!$checktatanan) {
                    foreach ($data_lembaga as $key => $value) {
                        $tatanan = new TrxLembaga();
                        $tatanan->id_periode = $request->periode_id;
                        $tatanan->id_main = $main->id;
                        $tatanan->id_lembaga_asal = $value->id;
                        $tatanan->nama_kelembagaan = $value->nama_kelembagaan;
                        $tatanan->save();
                    }
                }
                // get data id_main int tabel trx_main where users_id and periode id
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
                    $pertanyaanTatanan->id_main = $main->id;
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
        DB::table('trx_main')->where('id_periode', $id)->delete();
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
