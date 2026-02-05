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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string("customer_email")->nullable();
            $table->string("postcode")->nullable();
            $table->integer("created_by")->nullable();
            $table->string("job_invoice_no")->nullable();
            $table->string("added_by")->nullable();
            $table->enum('contract_status', ['0' ,'1', '2'])->default('0');
            $table->date("date")->nullable();
            $table->integer("engineer_id")->nullable();
            $table->integer("agent_id")->nullable();
            $table->integer("hand_overed_agent")->nullable();
            $table->enum('status', ['Active', 'Completed'])->default('active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
