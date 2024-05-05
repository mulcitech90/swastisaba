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

class PengisianFormController extends Controller
{
    public function periode_tatanan()
    {
        $data = Periode::orderBy('id', 'DESC')->get();
        return view('admin.pengisianform.tatanan',compact('data'));
    }
    public function periode_lembaga()
    {
        $data = Periode::orderBy('id', 'DESC')->get();
        return view('admin.pengisianform.lembaga',compact('data'));
    }
    public function pertanyaanlist($id)
    {
        $result = TrxPertanyaan::where('tatanan_id', $id)->get();
        foreach ($result as $key => $ta) {
            $id = $ta->indikator_id;
            $result[$key]->indikator = Indikator::where('id', $id)->pluck('nama_indikator');
        }
        return response()->json($result);
    }
    public function pertanyaanlembaga($periode)
    {
        $result = TrxPertanyaanLembaga::where('id_periode', $periode)->get();
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
       dd('masuk');
    }
    public function updatefilelembaga(Request $request)
    {
       dd('masukasdas');
    }
}
