<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@gmail.com',
            'username' =>'admin1',
            'password'=> Hash::make('password123'), // كلمة المرور مشفرة
            'role' => 'admin',
            'is_verified' => true,
            'phone_number' => '0592634452',
        ]);

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@gmail.com',
            'username' => 'admin2',
            'password' => Hash::make('password456'),
            'role' => 'admin',
            'is_verified' => true,
            'phone_number' => '0592674452',
        ]);

        User::create([
            'name' => 'Admin 3',
            'email' => 'admin3@gmail.com',
            'username' =>'admin3',
            'password'=> Hash::make('password789'),
            'role' => 'admin',
            'is_verified' => true,
            'phone_number' =>'05926514452',
        ]);
    }
}
