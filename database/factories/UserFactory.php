<?php

use Faker\Generator as Faker;
use App\Entity\User;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {

    $active = $faker->boolean;
    $phoneActive = $faker->boolean;

    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
        'phone_verified' => $phoneActive,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'verify_token' => $active ? null : Str::uuid(),
        'phone_verified_token' => $phoneActive ? null : Str::uuid(),
        'phone_verified_token_expire' => $phoneActive ? null : \Carbon\Carbon::now()->addSeconds(300),
        'role' => $active ? $faker->randomElement([User::ROLE_USER, User::ROLE_ADMIN]) : User::ROLE_USER,
        'status' => $active ? User::STATUS_ACTIVE : User::STATUS_WAIT,
    ];
});
