<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabkotaModel extends Model
{
    use HasFactory;

    protected $table = 'kab_kota';
    protected $primaryKey = 'id';
    protected $fillable = ['provinsi_id', 'nama'];
}
