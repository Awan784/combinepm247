<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('engineer_availability', function (Blueprint $table) {
            $table->id();
            $table->integer("engineer_id");
            $table->date("date_start")->nullable();
            $table->time("start_time")->nullable();
            $table->time("end_time")->nullable();
            $table->string("title")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_availability');
    }
};
