<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(mt_rand(3, 10)),
        'content' => join("\n\n". $faker->paragraph(mt_rand(3, 6))),
        'published_at' => $faker->dateTimeBetween('-1 month', '+3 days'),
    ];
});
