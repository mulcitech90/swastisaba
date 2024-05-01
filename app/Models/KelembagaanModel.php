<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelembagaanModel extends Model
{
    use HasFactory;

    protected $table = 'kelembagaan';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_kelembagaan'];
    public $timestamps = true;
}
