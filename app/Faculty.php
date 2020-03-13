<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Faculty extends Model
{
    use AutoNumberTrait;
    
    protected $fillable = [
        'faculty_code', 'name'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'faculty_code' => [
                'format' => 'FA-?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public function major()
    {
        return $this->hasMany('App\Major');
    }

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }
}
