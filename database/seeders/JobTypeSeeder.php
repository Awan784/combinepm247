<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobType::create([
            "title" => "Plumbing",
            "bgcolor" => "bg-info"
        ]);
        JobType::create([
            "title" => "Drainage Basic",
            "bgcolor" => "bg-success"
        ]);
        JobType::create([
            "title" => "Drainage External",
            "bgcolor" => "bg-success"
        ]);
        JobType::create([
            "title" => "Heating",
            "bgcolor" => "bg-danger"
        ]);
        JobType::create([
            "title" => "Gas",
            "bgcolor" => "bg-secondary"
        ]);
        JobType::create([
            "title" => "Unvented",
            "bgcolor" => "bg-tertiary"
        ]);
        JobType::create([
            "title" => "Electrical",
            "bgcolor" => "bg-warning"
        ]);
    }
}
