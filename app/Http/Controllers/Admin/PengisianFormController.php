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

class PengisianFormController extends Controller
{
    // start
    public function start(Request $request)
    {
        if ($request->prosess == 'tatanan') {
            $data = DB::table('trx_main')
                    ->where('id_user', '=', Auth::user()->id)
                    ->update([
                    'status' => 'Dalam Pengisian',
                    ]);
        }else{
            $data = DB::table('trx_main')
                    ->where('id_user', '=', Auth::user()->id)
                    ->update([
                    'status_lembaga' => 'Dalam Pengisian',
                    ]);
        }
        return response()->json($data);
    }
    public function periode_tatanan()
    {
        $data = DB::table('trx_main')
        ->join('periode', 'periode.id', '=', 'trx_main.id_periode')
        ->join('periode_main', 'periode.id_main_periode', '=', 'periode_main.id')
        ->select('trx_main.*', 'periode_main.periode as periode_name', 'periode.status as status_akses')
        ->where('trx_main.id_user', '=', Auth::user()->id)
        ->get();
        return view('admin.pengisianform.tatanan',compact('data'));
    }
    public function periode_lembaga()
    {
        $data = DB::table('trx_main')
        ->join('periode', 'periode.id', '=', 'trx_main.id_periode')
        ->join('periode_main', 'periode.id_main_periode', '=', 'periode_main.id')
        ->select('trx_main.*', 'periode_main.periode as periode_name', 'periode.status as status_akses')
        ->where('trx_main.id_user', '=', Auth::user()->id)
        ->get();
        return view('admin.pengisianform.lembaga',compact('data'));
    }
    public function pertanyaanlist(Request $request, $id)
    {
        $result = TrxPertanyaan::where('tatanan_id', $id)->where('id_periode', $request->periode)
        ->where('user_id', Auth::user()->id)
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
    public function assessment($id)
    {
        $id_ = (base64_decode($id));
        $periode = Periode::where('id', $id_)->first();
        $tatanan = TrxTatanan::where('id_periode', $id_)->get();
        $indikator = Indikator::all();

        return view('admin.pengisianform.assessment', compact('periode', 'tatanan', 'indikator'));
    }
    public function kelembagaan($id)
    {
        $id_ = (base64_decode($id));
        $periode = Periode::where('id', $id_)->first();
        $lembaga = TrxKelembagaan::where('id_periode', $id_)->get();

        return view('admin.pengisianform.kelembagaan', compact('periode', 'lembaga'));
    }

    public function updatelink(Request $request)
    {
        try {
            $file = $request->file('file');
            $validated = $request->validate([
                'file' => 'required|max:2048',
            ]);
            $filePath = $file->store('uploads');

            $result = TrxPertanyaan::where('id', $request->id)->first();
            $result->file =$filePath;
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
    public function downloadfileTatanan($id)
    {
        try {
            $id_ = (base64_decode($id));
            $result = TrxPertanyaan::where('id', $id_)->first();
            return Storage::download($result->file);
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
    // PengisianFormController
    public function submitpengisian(Request $request){
        try {
            $id = $request->id;
            $status = $request->status;
            if ($request->prosess == 'tatanan') {
                $data =DB::table('trx_main')
                        ->where('id_user', '=', Auth::user()->id)
                        ->update([
                        'status' => 'Verifikasi',
                        ]);
            }else{
                $data = DB::table('trx_main')
                        ->where('id_user', '=', Auth::user()->id)
                        ->update([
                        'status_lembaga' => 'Verifikasi',
                        ]);
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }


    }
}
