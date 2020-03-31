<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Sale extends Model
{
    use AutoNumberTrait;

    protected $fillable = [
        'sales_number', 'customer_name', 'user_id', 'product_id', 'faculty_id', 'major_id', 'quantity', 'price', 'total_price', 'order_date'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'sales_number' => [
                'format' => 'SO-?', // Format kode yang akan digunakan.
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

    public function faculty()
    {
    	return $this->belongsTo(Faculty::class);
    }

    public function major()
    {
    	return $this->belongsTo(Major::class);
    }
}
