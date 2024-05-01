<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinasModel extends Model
{
    use HasFactory;

    protected $table = 'dinas';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_dinas'];
    public $timestamps = true   ;
}
