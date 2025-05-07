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
        Schema::table('game7ms', function (Blueprint $table) {
            $table->string('f20a')->nullable()->after('f20');  // Add after f20 for clarity
            $table->json('f20b')->nullable()->after('f20a');  // JSON to hold array of decimals
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game7ms', function (Blueprint $table) {
            $table->dropColumn(['f20a', 'f20b']);
        });
    }
};
