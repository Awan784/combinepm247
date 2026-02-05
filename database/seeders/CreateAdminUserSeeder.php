<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "email" => "admin@example.com",
        "password" => Hash::make("abc123"),
            "user_type_id" => 1,
            "name" => "Admin"
        ]);
    }
}
