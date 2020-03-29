<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulan extends Model
{
    protected $table = 'bulan';
    protected $fillable = ['nama', 'semester', 'tahun', 'urutan'];
}
