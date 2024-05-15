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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// helper functions
class DashboardController extends Controller
{
    public function dashboard_admin(Request $request)
    {
        $periodeId = $request->input('periode_id');

        if (!$periodeId) {
            // Ambil periode terakhir jika tidak ada inputan
            $periodeId = DB::table('periode')->latest('id')->value('id');
        }

        $data_main = DB::table('trx_main')
            ->when($periodeId, function ($query, $periodeId) {
                return $query->where('id_periode', $periodeId);
            })
            ->get();

        $data_pertatanan = [];
        $wilayahs = []; // Array untuk menyimpan nilai total wilayah
        $tatanan_sums = []; // Array untuk menyimpan total nilai dan soal setiap tatanan
        $total_wilayah = $data_main->count();

        foreach ($data_main as $main) {
            $data_tatanan = TrxTatanan::where('id_periode', '=', $main->id_periode)->get();
            $nilaiTatanan = [];

            $totalNilai = 0; // Set nilai total ulang untuk setiap wilayah
            $totalSoal = 0; // Set total soal ulang untuk setiap wilayah

            foreach ($data_tatanan as $tatanan) {
                $data_score_tatanan = DB::table('trx_score')
                    ->where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('id_tatanan', '=', $tatanan->id_tatanan)
                    ->sum('nilai');

                $data_total_soal = TrxPertanyaan::where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('tatanan_id', '=', $tatanan->id_tatanan)
                    ->count();

                $totalNilai += $data_score_tatanan; // Akumulasi nilai per wilayah
                $totalSoal += $data_total_soal; // Akumulasi jumlah soal per wilayah

                if ($data_total_soal > 0) {
                    $rataRataNilai = $data_score_tatanan / $data_total_soal;
                } else {
                    $rataRataNilai = 0;
                }

                $nilaiTatanan[] = [
                    'idTatanan' => $tatanan->id_tatanan,
                    'nilai' => number_format($rataRataNilai, 2) . '%'
                ];

                // Akumulasi nilai dan soal setiap tatanan di seluruh wilayah
                if (!isset($tatanan_sums[$tatanan->id_tatanan])) {
                    $tatanan_sums[$tatanan->id_tatanan] = ['totalNilai' => 0, 'totalSoal' => 0];
                }
                $tatanan_sums[$tatanan->id_tatanan]['totalNilai'] += $data_score_tatanan;
                $tatanan_sums[$tatanan->id_tatanan]['totalSoal'] += $data_total_soal;
            }

            $wilayah = [
                'namaWilayah' => $this->UserName($main->id),
                'totalNilai' => $totalNilai, // Total nilai per wilayah
                'totalSoal' => $totalSoal // Total soal per wilayah
            ];

            $wilayahs[] = $wilayah; // Simpan nilai total wilayah
            $data_pertatanan[] = [
                'namaWilayah' => $this->UserName($main->id), // Ganti dengan nama wilayah yang sesuai
                'nilaiTatanan' => $nilaiTatanan
            ];
        }

        // Menghitung rata-rata global per wilayah
        foreach ($wilayahs as &$wilayah) {
            if ($wilayah['totalSoal'] > 0) {
                $wilayah['rataRataGlobal'] = $wilayah['totalNilai'] / $wilayah['totalSoal'];

                // Menentukan kategori berdasarkan rata-rata global
                if ($wilayah['rataRataGlobal'] >= 0 && $wilayah['rataRataGlobal'] < 71) {
                    $wilayah['kategori'] = 'Tidak Lolos';
                } elseif ($wilayah['rataRataGlobal'] >= 71 && $wilayah['rataRataGlobal'] < 81) {
                    $wilayah['kategori'] = 'PADAPA';
                } elseif ($wilayah['rataRataGlobal'] >= 81 && $wilayah['rataRataGlobal'] < 91) {
                    $wilayah['kategori'] = 'WIWERDA';
                } elseif ($wilayah['rataRataGlobal'] >= 91 && $wilayah['rataRataGlobal'] <= 100) {
                    $wilayah['kategori'] = 'WISTARA';
                } else {
                    $wilayah['kategori'] = '-';
                }
            } else {
                $wilayah['rataRataGlobal'] = 0;
                $wilayah['kategori'] = 'Tidak Lolos';
            }
        }

        // Menghitung rata-rata setiap tatanan dibagi total wilayah
        $tatanan_averages = [];
        foreach ($tatanan_sums as $id_tatanan => $sums) {
            if ($sums['totalSoal'] > 0) {
                $tatanan_averages[$id_tatanan] = number_format(($sums['totalNilai'] / $sums['totalSoal']), 2) . '%';
            } else {
                $tatanan_averages[$id_tatanan] = '0.00%';
            }
        }
        $periode_list = Periode::All();
        return view('admin.dashboard', compact('data_pertatanan', 'wilayahs', 'tatanan_averages', 'periode_list', 'periodeId'));
    }
    public function dashboard_pemda(Request $request)
    {
        $periodeId = $request->input('periode_id');

        if (!$periodeId) {
            // Ambil periode terakhir jika tidak ada inputan
            $periodeId = DB::table('periode')->latest('id')->value('id');
        }

        $data_main = DB::table('trx_main')
            ->where('id_user', '=', Auth::user()->id)
            ->when($periodeId, function ($query, $periodeId) {
                return $query->where('id_periode', $periodeId);
            })
            ->get();

        $data_pertatanan = [];
        $totalNilai = 0;
        $totalSoal = 0;

        foreach ($data_main as $main) {
            $data_tatanan = TrxTatanan::where('id_periode', '=', $main->id_periode)->get();
            $nilaiTatanan = [];

            foreach ($data_tatanan as $tatanan) {
                $data_score_tatanan = DB::table('trx_score')
                    ->where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('id_tatanan', '=', $tatanan->id_tatanan)
                    ->sum('nilai');

                $data_total_soal = TrxPertanyaan::where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('tatanan_id', '=', $tatanan->id_tatanan)
                    ->count();

                if ($data_total_soal > 0) {
                    $rataRataNilai = $data_score_tatanan / $data_total_soal;
                } else {
                    $rataRataNilai = 0;
                }

                $nilaiTatanan[] = [
                    'idTatanan' => $tatanan->id_tatanan,
                    'nilai' => number_format($rataRataNilai, 2) . '%'
                ];

                // Akumulasi nilai dan jumlah soal untuk perhitungan rata-rata global
                $totalNilai += $data_score_tatanan;
                $totalSoal += $data_total_soal;
            }

            $data_pertatanan[] = [
                'namaWilayah' => Auth::user()->name, // Ganti dengan nama wilayah yang sesuai
                'nilaiTatanan' => $nilaiTatanan
            ];
        }

        // Menghitung rata-rata global dari semua tatanan
        $rataRataGlobal = 0;
        if ($totalSoal > 0) {
            $rataRataGlobal = $totalNilai / $totalSoal;

            // Menentukan kategori berdasarkan rata-rata global
            if ($rataRataGlobal > 0 && $rataRataGlobal < 71) {
                $kategori = 'Tidak Lolos';
            } elseif ($rataRataGlobal >= 71 && $rataRataGlobal < 81) {
                $kategori = 'PADAPA';
            } elseif ($rataRataGlobal >= 81 && $rataRataGlobal < 91) {
                $kategori = 'WIWERDA';
            } elseif ($rataRataGlobal >= 91 && $rataRataGlobal <= 100) {
                $kategori = 'WISTARA';
            }else{
                $kategori = '-';
            }
        } else {
            $kategori = '';
        }

        // Menambahkan kategori ke array data
        foreach ($data_pertatanan as &$wilayah) {
            $wilayah['kategori'] = $kategori;
        }
        $periode_list = Periode::All();
        return view('admin.dashboard-pemda',compact('data_pertatanan', 'rataRataGlobal', 'kategori','periode_list', 'periodeId'));
    }
    public function dashboard_dinas(Request $request)
    {
        $periodeId = $request->input('periode_id');

        if (!$periodeId) {
            // Ambil periode terakhir jika tidak ada inputan
            $periodeId = DB::table('periode')->latest('id')->value('id');
        }

        $data_main = DB::table('trx_main')
            ->when($periodeId, function ($query, $periodeId) {
                return $query->where('id_periode', $periodeId);
            })
            ->get();
        $data_pertatanan = [];
        $wilayahs = []; // Array untuk menyimpan nilai total wilayah
        $tatanan_sums = []; // Array untuk menyimpan total nilai dan soal setiap tatanan
        $total_wilayah = $data_main->count();

        foreach ($data_main as $main) {
            $data_tatanan = TrxTatanan::where('id_periode', '=', $main->id_periode)->get();
            $nilaiTatanan = [];

            $totalNilai = 0; // Set nilai total ulang untuk setiap wilayah
            $totalSoal = 0; // Set total soal ulang untuk setiap wilayah

            foreach ($data_tatanan as $tatanan) {
                $data_score_tatanan = DB::table('trx_score')
                    ->where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('id_tatanan', '=', $tatanan->id_tatanan)
                    ->sum('nilai');

                $data_total_soal = TrxPertanyaan::where('id_main', '=', $main->id)
                    ->where('id_periode', '=', $main->id_periode)
                    ->where('tatanan_id', '=', $tatanan->id_tatanan)
                    ->count();

                $totalNilai += $data_score_tatanan; // Akumulasi nilai per wilayah
                $totalSoal += $data_total_soal; // Akumulasi jumlah soal per wilayah

                if ($data_total_soal > 0) {
                    $rataRataNilai = $data_score_tatanan / $data_total_soal;
                } else {
                    $rataRataNilai = 0;
                }

                $nilaiTatanan[] = [
                    'idTatanan' => $tatanan->id_tatanan,
                    'nilai' => number_format($rataRataNilai, 2) . '%'
                ];

                // Akumulasi nilai dan soal setiap tatanan di seluruh wilayah
                if (!isset($tatanan_sums[$tatanan->id_tatanan])) {
                    $tatanan_sums[$tatanan->id_tatanan] = ['totalNilai' => 0, 'totalSoal' => 0];
                }
                $tatanan_sums[$tatanan->id_tatanan]['totalNilai'] += $data_score_tatanan;
                $tatanan_sums[$tatanan->id_tatanan]['totalSoal'] += $data_total_soal;
            }

            $wilayah = [
                'namaWilayah' => $this->UserName($main->id),
                'totalNilai' => $totalNilai, // Total nilai per wilayah
                'totalSoal' => $totalSoal // Total soal per wilayah
            ];

            $wilayahs[] = $wilayah; // Simpan nilai total wilayah
            $data_pertatanan[] = [
                'namaWilayah' => $this->UserName($main->id), // Ganti dengan nama wilayah yang sesuai
                'nilaiTatanan' => $nilaiTatanan
            ];
        }

        // Menghitung rata-rata global per wilayah
        foreach ($wilayahs as &$wilayah) {
            if ($wilayah['totalSoal'] > 0) {
                $wilayah['rataRataGlobal'] = $wilayah['totalNilai'] / $wilayah['totalSoal'];

                // Menentukan kategori berdasarkan rata-rata global
                if ($wilayah['rataRataGlobal'] >= 0 && $wilayah['rataRataGlobal'] < 71) {
                    $wilayah['kategori'] = 'Tidak Lolos';
                } elseif ($wilayah['rataRataGlobal'] >= 71 && $wilayah['rataRataGlobal'] < 81) {
                    $wilayah['kategori'] = 'PADAPA';
                } elseif ($wilayah['rataRataGlobal'] >= 81 && $wilayah['rataRataGlobal'] < 91) {
                    $wilayah['kategori'] = 'WIWERDA';
                } elseif ($wilayah['rataRataGlobal'] >= 91 && $wilayah['rataRataGlobal'] <= 100) {
                    $wilayah['kategori'] = 'WISTARA';
                } else {
                    $wilayah['kategori'] = '-';
                }
            } else {
                $wilayah['rataRataGlobal'] = 0;
                $wilayah['kategori'] = 'Tidak Lolos';
            }
        }

        // Menghitung rata-rata setiap tatanan dibagi total wilayah
        $tatanan_averages = [];
        foreach ($tatanan_sums as $id_tatanan => $sums) {
            if ($sums['totalSoal'] > 0) {
                $tatanan_averages[$id_tatanan] = number_format(($sums['totalNilai'] / $sums['totalSoal']), 2) . '%';
            } else {
                $tatanan_averages[$id_tatanan] = '0.00%';
            }
        }
        $periode_list = Periode::All();
        return view('admin.dashboard-dinas', compact('data_pertatanan', 'wilayahs', 'tatanan_averages', 'periode_list', 'periodeId'));
    }

    public function UserName($mainId){
        // Logika untuk mendapatkan nama wilayah dari $mainId
        $user = DB::table('users')
            ->join('trx_main', 'users.id', '=', 'trx_main.id_user')
            ->where('trx_main.id', $mainId)
            ->select('users.name')
            ->first();

        return $user ? $user->name : '-';
    }

}
