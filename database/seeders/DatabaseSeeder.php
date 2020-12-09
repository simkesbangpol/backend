<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
//         $this->call('UsersTableSeeder');
        DB::table('users')->insert([
            'username' => "User",
            'address' => "Address",
            'phone_number' => "Phone",
            'name' => "User",
            'email' => "testdev@email.com",
            'password' => Hash::make('password')
        ]);
    }
}
