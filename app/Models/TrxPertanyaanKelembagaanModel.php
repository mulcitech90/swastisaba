<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPertanyaanKelembagaanModel extends Model
{
    use HasFactory;
    protected $table = 'trx_pertanyaan_lembaga';
    protected $primaryKey = 'id';
    protected $fillable = ['id_periode','pertanyaan', 'id_main','id_kelembagaan', 'jawaban_a', 'jawaban_b', 'file'];
    public $timestamps = true;
}
