<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanKelembagaanModel extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan_kelembagaan';
    protected $primaryKey = 'id';
    protected $fillable = ['pertanyaan', 'id_kelembagaan', 'jawaban_a', 'jawaban_b', 'file'];
    public $timestamps = true;
}
