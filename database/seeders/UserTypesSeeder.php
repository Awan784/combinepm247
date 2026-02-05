<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserType::create([
            "title" => "Admin"
        ]);

        UserType::create([
            "title" => "Office User"
        ]);

        UserType::create([
            "title" => "Engineer"
        ]);
    }
}
