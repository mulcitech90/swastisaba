<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class swastisabaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // provinsi DKI Jakarta
        \App\Models\ProvinsiModel::factory()->create([
            'nama' => 'DKI Jakarta',
        ]);
        // peroide 2021
        \App\Models\PeriodeModel::factory()->create([
         //start_year
            'start_year' => 2022,
            'end_year' => 2023,
            'periode' => '2022-2023',
            'status' => 'aktif',
        ]);
        // model swasti saba
        \App\Models\ModelModel::factory()->create([
            'nama' => 'Swasti Saba',
            'id_periode' => 1,
        ]);

        // kab_kota
        $data_kabkota = [
            'Jakarta Pusat',
            'Jakarta Utara',
            'Jakarta Barat',
            'Jakarta Selatan',
            'Jakarta Timur',
            'Kepulauan Seribu',
        ];
        foreach ($data_kabkota as $kabkota) {
            \App\Models\KabkotaModel::factory()->create([
                'nama' => $kabkota,
                'provinsi_id' => 1,
            ]);
        }
        // unit
        $data_unit = [
            'Dinas Kesehatan',
            'Dinas Ketahanan Pangan, Kelautan dan Pertanian',
            'Dinas Lingkungan Hidup',
            'Badan Penanggulangan Bencana Daerah',
            'Dinas Perumahan Rakyat dan Kawasan Pemukiman',
            'Dinas Perindustrian, Perdagangan, Koperasi Usaha Kecil dan Menengah',
            'Dinas Pariwisata dan Ekonomi Kreatif',
            'Dinas Tenaga Kerja, Transmigrasi dan Energi',
            'Dinas Perhubungan',
            'Dinas Sosial',
            'Dinas Pemberdayaan, Perlindungan Anak dan Pengendalian Penduduk'
        ];
        foreach ($data_unit as $unit) {
            \App\Models\DinasModel::factory()->create([
                'nama_dinas' => $unit,
                'id_model' => 1,
            ]);
        }

        // tatanan
        $data_tatanan = [
            'Sehat Mandiri',
            'Pemukiman dan Pemakaman',
            'Pendidikan',
            'Pasar',
            'Pariwisata',
            'Transportasi',
            'Perindustrian',
            'Sosial',
            'Bencana',
        ];
        foreach ($data_tatanan as $tatanan) {
            \App\Models\TatananModel::factory()->create([
                'nama' => $tatanan,
                'id_model' => 1,
            ]);
        }

    }
}
