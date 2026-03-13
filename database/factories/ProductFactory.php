<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(50),
        'category_id' => $faker->randomDigitNot(0),
        'brand_id' => $faker->randomDigitNot(0)
    ];
});

