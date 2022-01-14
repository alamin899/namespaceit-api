<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $password = bcrypt("password");

        User::create([
            'name' => 'alamin',
            'email' => 'alamin@yopmail.com',
            'password' => $password,
        ]);

    }
}
