<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(20)->create();

        // \App\Models\User::factory(20)->create([
        //     'name' => Str::random(10),
        //     'email' => Str::random(7).'@dhvsu.edu.ph',
        //     'password' => Hash::make('password'),
        //     'user_privilege' => 'Basic User',
        // ]);
    }
}
