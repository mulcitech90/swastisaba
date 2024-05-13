<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TRXPertanyaanModel extends Model
{
    use HasFactory;
    protected $table = 'trx_pertanyaan';
    protected $primaryKey = 'id';
    protected $fillable = ['pertanyaan', 'dinas_id', 'kab_kota_id','indikator_id','id_main', 'provinsi_id', 'tatanan_id', 'user_id', 'status', 'jawaban_a', 'jawaban_b', 'jawaban_c', 'jawaban_d', 'nilai_a', 'nilai_b', 'nilai_c', 'nilai_d', 'ket', 'file', 'jawaban', 'id_periode'];
    public $timestamps = true;
}
