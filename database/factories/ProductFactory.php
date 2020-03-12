<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
    return [
        'number' => uniqid(),
        'name' => $faker->productName,
        'price' => $faker->numberBetween(1000, 1000000),
        'stock' => $faker->numberBetween(10, 200),
        'created_at' => $faker->dateTime() 
    ];
});
