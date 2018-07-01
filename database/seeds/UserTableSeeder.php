<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            "firstname" => "Erick",
            "lastname" => "Njiru",
            "bio"=>"Gym Lover",
            "type"=>"normal",
            "tel"=>"0718931359",
            "email" => "ericknjiru@gmail.com",
            "password" => bcrypt("123456")
        ]);
    }
}
