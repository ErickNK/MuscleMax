<?php

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
        $this->call(Users95319TableSeeder::class);
        $this->call(Locations95319TableSeeder::class);
        $this->call(Gyms95319TableSeeder::class);
    }
}
