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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('traditional_chinese')->nullable(); // Add other columns as needed
            $table->string('simplified_chinese')->nullable(); // Add other columns as needed
            $table->string('english')->nullable(); // Add other columns as needed
            $table->string('traditional_chinese_short')->nullable(); // Add other columns as needed
            $table->string('simplified_chinese_short')->nullable(); // Add other columns as needed
            $table->string('english_short')->nullable(); // Add other columns as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
