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
        Schema::create('engineers', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->integer("rating")->default(1);
            $table->integer("user_id")->nullable();
            $table->string("postal_codes")->nullable();
            $table->string("lat")->nullable();
            $table->string("long")->nullable();
            $table->string("home_postcode")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineers');
    }
};
