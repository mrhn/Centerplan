<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Enums\TransactionTypes;
use App\Models\Transaction;
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

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'executed_at' => $faker->dateTime,
        'description' => $faker->text(50),
        'type' => $faker->randomElement([TransactionTypes::DEBIT, TransactionTypes::CREDIT]),
        'amount' => $faker->randomFloat(2, 1, 500000),
    ];
});
