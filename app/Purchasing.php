<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Purchasing extends Model
{
    use AutoNumberTrait;

    protected $fillable = [
        'trans_number', 'user_id', 'product_id', 'supplier_id', 'quantity', 'price', 'total_price', 'order_date'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'trans_number' => [
                'format' => 'PO-?', // Format kode yang akan digunakan.
                'length' => 8 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }
}
