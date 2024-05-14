<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeModel as Periode;
use App\Models\TatananModel;
use App\Models\TRXTatananModel as TrxTatanan;
use App\Models\PertanyaanModel;
use App\Models\TRXPertanyaanModel as TrxPertanyaan;
use App\Models\TrxPertanyaanKelembagaanModel as TrxPertanyaanLembaga;
use App\Models\TrxKelembagaanModel as TrxKelembagaan;
use App\Models\IndikatorModel as Indikator;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class ValidatorController extends Controller
{
    public function validator_periode(Request $request)
    {
        $periode = Periode::all();
        return view('admin.validator.tatanan',compact('periode'));
    }
    // pemda_list
    function pemda_list($periode){
        // $uniqueUserIds = TrxPertanyaan::distinct()->where('id_periode',$periode)->pluck('user_id');
        // $result = User::whereIn('id',$uniqueUserIds)->get();
        $result = DB::table('trx_main')
        ->join('users', 'users.id', '=', 'trx_main.id_user')
        ->select('trx_main.*', 'users.id as user_id', 'users.name')
        ->where('trx_main.id_periode', $periode)
        ->get();
        return response()->json($result);
    }

    // start
    public function start(Request $request)
    {
        if ($request->prosess == 'tatanan') {
            $data = User::where('id', '=', Auth::user()->id)->update([
                    'status_pengisian' => 'Pengisian',
                    ]);
        }else{
            $data = User::where('id', '=', Auth::user()->id)->update([
                    'status_pengisian_lembaga' => 'Pengisian',
                    ]);
        }
        return response()->json($data);
    }
    public function periode_tatanan()
    {
        $data = Periode::orderBy('id', 'DESC')->get();
        return view('admin.validator.tatanan',compact('data'));
    }
    public function periode_lembaga()
    {
        $data = Periode::orderBy('id', 'DESC')->get();
        return view('admin.validator.lembaga',compact('data'));
    }
    public function pertanyaanlist(Request $request, $id)
    {
        $result = TrxPertanyaan::where('tatanan_id', $id)->where('id_periode', $request->periode)
        ->where('dinas_id', Auth::user()->id_dinas)
        ->where('user_id', $request->user)
        ->get();

        foreach ($result as $key => $ta) {
            $id = $ta->indikator_id;
            $result[$key]->indikator = Indikator::where('id', $id)->pluck('nama_indikator');
        }
        return response()->json($result);
    }
    public function pertanyaanlembaga($periode)
    {
        $result = TrxPertanyaanLembaga::where('id_periode', $periode)
        ->where('user_id', Auth::user()->id)
        ->get();
        $lembaga = TrxKelembagaan::where('id_periode', $periode)->get();
        foreach ($lembaga as $key => $ta) {
            $id = $ta->id_kelembagaan;
            $result[$key]->nama_kelembagaan = TrxKelembagaan::where('id_periode', $periode)->where('id', $id)->pluck('nama_kelembagaan');
        }
        return response()->json($result);
    }
    public function assessment(Request $request, $id)
    {
        $id_ = (base64_decode($id));
        $id_check = $id_;
        $periode = (base64_decode($request->pr));
        $periode = DB::table('trx_main')->where('id_periode', $periode)->where('id_user', $id_)->first();
        $tatanan = TrxTatanan::where('id_periode', $periode->id_periode)->get();
        $indikator = Indikator::all();

        return view('admin.validator.assessment', compact('periode', 'tatanan', 'indikator', 'id_check'));
    }
    public function kelembagaan($id)
    {
        $id_ = (base64_decode($id));
        $periode = Periode::where('id', $id_)->first();
        $lembaga = TrxKelembagaan::where('id_periode', $id_)->get();

        return view('admin.validator.kelembagaan', compact('periode', 'lembaga'));
    }

    public function updatelink(Request $request)
    {
        try {
            $result = TrxPertanyaan::where('id', $request->id)->first();
            $result->file = $request->linkPendukung;
            $result->update();

            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function uploadfile(Request $request)
    {
        try {
            $file = $request->file('file');
            $validated = $request->validate([
                'file' => 'required|max:2048',
            ]);
            $filePath = $file->store('uploads');

            $result = TrxPertanyaanLembaga::where('id', $request->id)->first();
            $result->file = $filePath;
            $result->update();
            $notif = [
                'type' =>'success',
                'message' => 'Data berhasil disimpan'
            ];
            return response()->json($notif);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }

    }
    public function downloadfile($id)
    {
        try {
            $id_ = (base64_decode($id));
            $result = TrxPertanyaanLembaga::where('id', $id_)->first();
            return Storage::download($result->file);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }
    public function pengisianSoal(Request $request){
        try {
            if ($request->tag == 'validator') {
                $result = TrxPertanyaan::where('id', $request->dataId)->first();
                $result->status = $request->validator;
                $result->update();
            }elseif ($request->tag == 'cacatan') {
                $result = TrxPertanyaan::where('id', $request->dataId)->first();
                $result->cacatan = $request->cacatan;
                $result->update();
            }
            else{
                $result = TrxPertanyaan::where('id', $request->dataId)->first();
                $result->jawaban = $request->jawaban;
                $result->nilai = $request->nilai;
                $result->update();
            }

            $notif = [
                'type' =>'success',
               'message' => 'Data berhasil disimpan'
            ];
            return response()->json($notif);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function pengisianSoallembaga(Request $request){
        try {
            $result = TrxPertanyaanLembaga::where('id', $request->dataId)->first();
            $result->jawaban = $request->jawaban;
            $result->update();
            $notif = [
                'type' =>'success',
               'message' => 'Data berhasil disimpan'
            ];
            return response()->json($notif);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    // pengisianSoallembaga
    // validatorController
    public function submitpengisian(Request $request){
        try {
            $id = $request->id;
            $data =DB::table('trx_main')
                ->where('id', '=', $request->id_main)
                ->update([
                'status' => 'Perbaikan',
            ]);
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }


    }
    public function penilaian(Request $request){
        try {
            $idPeriode = $request->id;
            $idDinas = $request->id_dinas;
            $idMain = $request->id_main;
            $idUser = $request->id_user;
            $idUserValidator = Auth::user()->id;

            // get nilai soal TrxPertanyaan
            $dataSoal = TrxPertanyaan::where('id_main', $idMain)
                ->where('dinas_id', $idDinas)
                ->where('status', '1')
                ->get();
            if (count($dataSoal)>0) {
                $dataTatanan = TrxTatanan::where('id_periode', $idPeriode)->get();
                $jumlahSoal = $dataSoal->count();
                // Inisialisasi array untuk menyimpan nilai per tatanan_id
                $nilaiTatanan = [];

                // Inisialisasi array untuk menyimpan total nilai dan jumlah soal per tatanan_id
                foreach ($dataTatanan as $ta) {
                    $nilaiTatanan[$ta->id] = 0;
                }

                foreach ($dataSoal as $v) {
                    if (isset($nilaiTatanan[$v->tatanan_id])) {
                        $nilaiTatanan[$v->tatanan_id] += $v->nilai;
                    } else {
                        $nilaiTatanan[$v->tatanan_id] = $v->nilai;
                    }
                }
                $totalNilaiSemuaTatanan = array_sum($nilaiTatanan);
                $rata2Nilai =  $totalNilaiSemuaTatanan / $jumlahSoal;
                // check input
                $check = DB::table('trx_score')->where('id_main', $idMain)->where('id_periode', $idPeriode)->where('id_dinas', $idDinas)->where('id_user_validator', $idUserValidator)->delete();
                foreach ($nilaiTatanan as $y => $ve) {
                    $score = [
                        'id_main' => $idMain,
                        'id_periode' => $idPeriode,
                        'id_tatanan' => $y,
                        'id_dinas' =>$idDinas,
                        'id_user_validator' => $idUserValidator,
                        'nilai' => $ve
                    ];
                    $data = DB::table('trx_score')->insert($score);
                }
            }else{
                $dataTatanan = TrxTatanan::where('id_periode', $idPeriode)->get();
                $nilaiTatanan = [];

                // Inisialisasi array untuk menyimpan total nilai dan jumlah soal per tatanan_id
                foreach ($dataTatanan as $ta) {
                    $nilaiTatanan[$ta->id] = 0;
                }
                $check = DB::table('trx_score')->where('id_main', $idMain)->where('id_periode', $idPeriode)->where('id_dinas', $idDinas)->where('id_user_validator', $idUserValidator)->delete();
                foreach ($nilaiTatanan as $y => $ve) {
                    $score = [
                        'id_main' => $idMain,
                        'id_periode' => $idPeriode,
                        'id_tatanan' => $y,
                        'id_dinas' =>$idDinas,
                        'id_user_validator' => $idUserValidator,
                        'nilai' => $ve
                    ];
                    $data = DB::table('trx_score')->insert($score);
                }
            }
            $data =DB::table('trx_main')
                ->where('id', '=', $idMain)
                ->update([
                'status' => 'Selesai',
            ]);

            $notif = [
                'type' =>'success',
               'message' => 'Data berhasil disimpan'
            ];
            return response()->json($notif);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
