<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanModel extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan';
    protected $primaryKey = 'id';
    protected $fillable = ['pertanyaan', 'dinas_id', 'kab_kota_id', 'indikator_id','provinsi_id', 'tatanan_id', 'user_id', 'status', 'jawaban_a', 'jawaban_b', 'jawaban_c', 'jawaban_d', 'nilai_a', 'nilai_b', 'nilai_c', 'nilai_d', 'ket', 'file'];
    public $timestamps = true;
}
