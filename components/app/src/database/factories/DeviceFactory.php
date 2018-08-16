<?php

use App\Domain\Device\Models\Type;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Domain\Device\Models\Device::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $type = factory(Type::class)->create();

    return [
        'user_id'=> $user,
        'type_id'=> $type,
        'model' => $faker->name,
        'brand' => $faker->name,
        'system' => $faker->name,
        'version' => $faker->bankAccountNumber,
        'mailed_to' => $faker->email,
        'mailed_at' => Carbon::now(),
        'accepted_at'=>null,
    ];
});
