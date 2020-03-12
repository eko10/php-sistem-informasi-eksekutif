<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Supplier extends Model
{
    use AutoNumberTrait;
    
    protected $fillable = [
        'supplier_number', 'supplier_name', 'phone', 'address'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'supplier_number' => [
                'format' => 'SO-?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public function purchasing()
    {
        return $this->hasMany('App\Purchasing');
    }
}
