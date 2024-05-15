<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'periode';
    protected $primaryKey = 'id';
    protected $fillable = ['id_main_periode','start_year', 'end_year', 'periode', 'status', 'status_lembaga'];
    public $timestamps = true;

}
