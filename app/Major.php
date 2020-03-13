<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Major extends Model
{
    use AutoNumberTrait;

    protected $fillable = [
        'major_code', 'name', 'faculty_id'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'major_code' => [
                'format' => 'JU-?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }
}
