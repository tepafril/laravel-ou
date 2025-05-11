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
        Schema::table('games', function (Blueprint $table) {
            $table->enum('is_wn', ['draw', 'win_half', 'win', 'loss_half', 'loss'])->nullable();
            $table->enum('is_ov', ['over', 'under', 'draw'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['is_wn', 'is_ov']);
        });
    }
}; 