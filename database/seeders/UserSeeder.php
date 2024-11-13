<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Oddiy foydalanuvchi
       $userPassword = Str::random(12);
       $user = User::create([
           'name' => '########',
           'first_name' => '##########',
           'second_name' => '#########',
           'third_name' => "###########",
           'email' => '###########',
           'employee_id_number' => '######',
           'employee_id' => '1307',
           'status' => true,
           'department_id' => '57',
           'email_verified_at' => now(),
           'password' => Hash::make($userPassword),
           'remember_token' => Str::random(10),
           'is_admin' => true,
       ]);
    }
}
