<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sales_number', 'customer_name', 'user_id', 'product_id', 'faculty_id', 'major_id', 'quantity', 'price', 'total_price'
    ];

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
