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
        Schema::create('league7ms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('bg_color')->nullable();
            $table->unsignedBigInteger('league_id')->nullable(); // Foreign key to matches (thid)
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league7ms');
    }
};
