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
        $uniqueUserIds = TrxPertanyaan::distinct()->where('id_periode',$periode)->pluck('user_id');
        $result = User::whereIn('id',$uniqueUserIds)->get();
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
        $periode = Periode::where('id',$periode)->first();
        $tatanan = TrxTatanan::where('id_periode', $periode->id)->get();
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
            $result = TrxPertanyaan::where('id', $request->dataId)->first();
            $result->jawaban = $request->jawaban;
            $result->nilai = $request->nilai;
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
            $status = $request->status;
            if ($request->prosess == 'tatanan') {
                $data = User::where('id', '=', Auth::user()->id)->update([
                    'status_pengisian' => 'Verifikasi',
                ]);
            }else{
                $data = User::where('id', '=', Auth::user()->id)->update([
                    'status_pengisian_lembaga' => 'Verifikasi',
                ]);
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }


    }
}
