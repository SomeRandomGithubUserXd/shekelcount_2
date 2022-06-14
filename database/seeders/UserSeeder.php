<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Creates 1 user (admin@gmail.com, password)
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(1)
            ->create();
    }
}
