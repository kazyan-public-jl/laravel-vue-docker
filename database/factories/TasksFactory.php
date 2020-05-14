<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tasks;
use Faker\Generator as Faker;

$factory->define(Tasks::class, function (Faker $faker) {
    return [
        "name"=> "サンプルタスクです",
        "status"=> false,
        "order"=> random_int(1, 100)
    ];
});
