<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'product_id' => $faker->randomDigitNot(0),
        'path' => 'chocolate-aveia.jpg',
        'type_id' => $faker->randomDigitNot(0)
    ];
});
