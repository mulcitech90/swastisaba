<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TatananModel extends Model
{
    use HasFactory;

    protected $table = 'tatanan';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_tatanan', 'id_model', 'id_indikator'];
}
