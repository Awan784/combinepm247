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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer("job_id")->nullable();
            $table->integer("sent_by")->nullable();
            $table->dateTime("sent_time")->nullable();
            $table->dateTime("received_time")->nullable();
            $table->dateTime("inform_time")->nullable();
            $table->string("status")->default("sent")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
