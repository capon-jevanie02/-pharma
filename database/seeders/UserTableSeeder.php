<?php

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            //ADMIN

            [
                'username'=> 'Admin',
                'email'=> 'admin@gmail.com',
                'password'=> Hash::make('111'),
                'role' => 'admin',
                'status' => '1',
            ],
            [
                'username'=> 'User',
                'email'=> 'user@gmail.com',
                'password'=> Hash::make('111'),
                'role' => 'user',
                'status' => '1',
            ]
        ]);
    }
}
