<?php

use Illuminate\Database\Seeder;
use App\Product;

class CreateProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = [
            [
                'number' => uniqid(),
                'name' => 'Ale-Ale',
                'price' => 1500,
                'stock' => 100,
            ],
            [
                'number' => uniqid(),
                'name' => 'Milo',
                'price' => 2500,
                'stock' => 50,
            ],
        ];
  
        foreach ($product as $key => $value) {
            Product::create($value);
        }
    }
}
