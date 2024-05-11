<?php
use App\Models\IndikatorModel;
use App\Models\KelembagaanModel;
use App\Models\TatananModel;
if (!function_exists('IndikatorName')) {
    function IndikatorName($id) {

        $data = IndikatorModel::whereIn('id', $id)->pluck('nama_indikator');
        if ($data->isEmpty()) {
            $result = '-';
        } else {
            $result = $data->implode(',');
        }

        return $result;
    }
}
if (!function_exists('LembagaName')) {
    function LembagaName($id) {

        $data = KelembagaanModel::where('id', $id)->select('nama_kelembagaan')->first();
        if (!$data) {
            $result = '-';
        } else {
            $result = $data->nama_kelembagaan;
        }

        return $result;
    }
}
if (!function_exists('TatananName')) {
    function TatananName($id) {

        $data = TatananModel::where('id', $id)->select('nama_tatanan')->first();
        if (!$data) {
            $result = '-';
        } else {
            $result = $data->nama_tatanan;
        }

        return $result;
    }
}
if (!function_exists('CountSoal')) {
    function CountSoal($id, $tag) {
        if ($tag == 'jumlahtatanan') {
            $data = DB::table('trx_tatanan')->where('id_periode', $id)->count();
        }else if ($tag == 'jumlahsoal'){
            $data = DB::table('trx_pertanyaan')->where('id_periode', $id)->where('user_id', Auth::user()->id)->count();
        }elseif ($tag == 'jumlahterjawab') {
            $data = DB::table('trx_pertanyaan')->where('id_periode', $id)->where('user_id', Auth::user()->id)->where('jawaban', '!=', NULL)->count();
        }else {
            $data = 0;
        }
        if (!$data) {
            $result = 0;
        } else {
            $result = $data;
        }

        return $result;
    }
}
if (!function_exists('CountSoalLembaga')) {
    function CountSoalLembaga($id, $tag) {
        if ($tag == 'hitungsoal'){
            $data = DB::table('trx_pertanyaan_lembaga')->where('id_periode', $id)->where('user_id', Auth::user()->id)->count();
        }else {
            $data = 0;
        }
        if (!$data) {
            $result = 0;
        } else {
            $result = $data;
        }

        return $result;
    }
}
if (!function_exists('statuspengisian')) {
    function statuspengisian($id) {
            $data = DB::table('users')->where('id', $id)->pluck('status_pengisian')->first();
            if (!$data) {
                $result = '-';
            }else {
                $result = $data;
            }

        return $result;
    }
}
if (!function_exists('statuspengisianlembaga')) {
    function statuspengisianlembaga($id) {
            $data = DB::table('users')->where('id', $id)->pluck('status_pengisian_lembaga')->first();
            if (!$data) {
                $result = '-';
            }else {
                $result = $data;
            }

        return $result;
    }
}
?>
