<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User; // ★
use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        //
        'content' => substr($faker->text, 0, 500),
        'user_id' => function(){
            factory(App\User::class)->create()->id;
        }
    ];
});
