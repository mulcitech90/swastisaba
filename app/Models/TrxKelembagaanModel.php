<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxKelembagaanModel extends Model
{
    use HasFactory;

    protected $table = 'trx_kelembagaan';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_kelembagaan', 'id_periode'];
    public $timestamps = true;
}
