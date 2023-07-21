<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    static $employeeId = 1;

    $employeeIdString = str_pad($employeeId, 5, '0', STR_PAD_LEFT);
    $employeeId++;

    // Generate a unique NRC
    $uniqueNrc = Employee::where('nrc', '!=', null)->max('nrc');
    $nrc = $uniqueNrc ? ++$uniqueNrc : '12/MAYAKA(N)178001';

    return [
        'employee_id' => $employeeIdString,
        'name' => $faker->name,
        'phone' => $faker->numerify('###########'), // generates a 11-digit number
        'nrc' => $nrc,
        'email' => $faker->unique()->safeEmail,
        'gender' => $faker->randomElement([1, 2]),
        'address' => $faker->address,
        'date_of_birth' => $faker->date,
        'language' => $faker->randomElement([1, 2]),
        'career_part' => $faker->randomElement([1, 2, 3, 4]),
        'level' => $faker->randomElement([1, 2, 3, 4]),
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
