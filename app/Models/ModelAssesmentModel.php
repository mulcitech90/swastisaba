<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelAssesmentModel extends Model
{
    use HasFactory;

    protected $table = 'model';
    protected $primaryKey = 'id';
    protected $fillable = ['id_periode', 'nama_model'];
    public $timestamps = true;

}
