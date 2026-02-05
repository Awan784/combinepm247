<?php

namespace Database\Seeders;

use App\Models\ModuleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ModuleModel::create([
            "title" => "Engineer Dashboard"
        ]);

        ModuleModel::create([
            "title" => "Contract Dashboard"
        ]);

        ModuleModel::create([
            "title" => "Assign Dashboard"
        ]);

        ModuleModel::create([
            "title" => "Jobs"
        ]);
        
        ModuleModel::create([
            "title" => "Contracts"
        ]);
        
        ModuleModel::create([
            "title" => "Payments"
        ]);

        ModuleModel::create([
            "title" => "Engineers"
        ]);

        ModuleModel::create([
            "title" => "Search Engineers"
        ]);

        ModuleModel::create([
            "title" => "Users"
        ]);
    }
}
