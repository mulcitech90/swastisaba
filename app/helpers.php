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
?>
