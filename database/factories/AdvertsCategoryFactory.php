<?php

use Faker\Generator as Faker;
use App\Entity\Adverts\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'slug' => $faker->unique()->slug(2),
        'parent_id' => null,
    ];
});
