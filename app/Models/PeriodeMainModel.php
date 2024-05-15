<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeMainModel extends Model
{
    use HasFactory;

    protected $table = 'periode_main';
    protected $primaryKey = 'id';
    protected $fillable = ['start_year', 'end_year', 'periode'];
    public $timestamps = true;

}
