<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Product extends Model
{
    use AutoNumberTrait;

    protected $fillable = ['product_number', 'name', 'category_id', 'price', 'stock'];

    public function getAutoNumberOptions()
    {
        return [
            'product_number' => [
                'format' => 'PR-?', // Format kode yang akan digunakan.
                'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function purchasing()
    {
        return $this->hasMany('App\Purchasing');
    }

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }
}
