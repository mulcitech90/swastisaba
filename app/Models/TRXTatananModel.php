<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TRXTatananModel extends Model
{
    use HasFactory;

    protected $table = 'trx_tatanan';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_tatanan', 'id_model', 'id_indikator', 'id_periode'];
}
