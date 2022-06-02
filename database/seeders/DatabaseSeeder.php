<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => 'Victor Arana',
            'email' => 'victor@codersfree.com',
            'password' => bcrypt('12345678'),
        ]);


        \App\Models\User::factory(10)->create();
    }
}
