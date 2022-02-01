<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        User::truncate();
        return [
            'name' => 'Jane Doe',//$this->faker->name(),
            'email' => 'janedoe@gmail.com',//$this->faker->unique()->safeEmail(),
            'address' => 'Iloilo Philippines',
            'role' => 'administrator',
            'email_verified_at' => now(),
            'password' => Hash::make('Master'),
            //'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
