<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // provinsi DKI Jakarta
        \App\Models\User::create([
            'name' => 'Superadmin',
            'email' =>'superadmin@admin.com',
            'password' => '12345',
            'role' => 'admin',
            'created_at' => now(),

        ]);
        // provinsi DKI Jakarta
        \App\Models\ProvinsiModel::create([
            'nama' => 'DKI Jakarta',
            'created_at' => now(),
        ]);
        // peroide 2021
        \App\Models\PeriodeModel::create([
         //start_year
            'start_year' => 2022,
            'end_year' => 2023,
            'periode' => '2022-2023',
            'status' => 'aktif',
            'created_at' => now(),

        ]);
        // model swasti saba
        \App\Models\ModelAssesmentModel::create([
            'nama_model' => 'Swasti Saba',
            'id_periode' => 1,
            'created_at' => now(),
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
            \App\Models\KabkotaModel::create([
                'nama' => $kabkota,
                'provinsi_id' => 1,
                'created_at' => now(),

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
            \App\Models\DinasModel::create([
                'nama_dinas' => $unit,
                'created_at' => now(),

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
            \App\Models\TatananModel::create([
                'nama_tatanan' => $tatanan,
                'id_model' => 1,
                'id_indikator' => 1,
                'created_at' => now(),

            ]);
        }

        // kelembagaan
        $data_kelembagaan = [
            'Forum Kabupaten/Kota',
            'Tim Pembina',
            'Forum Komunikasi di Kecamatan',
            'Pokja Desa/Kelurahan',
        ];
        foreach ($data_kelembagaan as $kelembagaan) {
            \App\Models\KelembagaanModel::create([
                'nama_kelembagaan' => $kelembagaan,
                'created_at' => now(),

            ]);
        }

        // indikator
        $data_indikator = [
            'Indikator Pokok',
            'Indikator Pendukung',
            'Kegiatan Forum',
        ];
        foreach ($data_indikator as $kelembagaan) {
            \App\Models\IndikatorModel::create([
                'nama_indikator' => $kelembagaan,
                'created_at' => now(),

            ]);
        }



    }
}
