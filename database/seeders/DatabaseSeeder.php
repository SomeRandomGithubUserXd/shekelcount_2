<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Seeds database with required data
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
    }
}
