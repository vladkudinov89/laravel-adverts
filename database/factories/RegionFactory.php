<?php

use Faker\Generator as Faker;
use App\Entity\Region;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->city,
        'slug' => $faker->unique()->slug(2),
        'parent_id' => null
    ];
});
