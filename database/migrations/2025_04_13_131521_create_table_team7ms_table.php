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
        Schema::create('team7ms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('fullname')->nullable();
            $table->unsignedBigInteger('team_id')->nullable(); // Foreign key to matches (thid)
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team7ms');
    }
};
