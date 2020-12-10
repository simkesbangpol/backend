<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'pegawai']);
        Role::create(['name' => 'user']);

        User::find(1)->assignRole('admin');
    }
}
