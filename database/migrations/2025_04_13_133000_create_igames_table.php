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
        Schema::create('igames', function (Blueprint $table) {
            $table->id();
            $table->dateTime('gt')->nullable(); // Game Time
            $table->string('f20')->nullable(); // Original f20 field
            $table->string('f20a')->nullable(); // Additional f20 field
            $table->json('f20b')->nullable(); // Additional f20 field
            $table->integer('st')->nullable(); // status
            $table->integer('fths')->nullable(); // ft_home_score
            $table->integer('ftas')->nullable(); // ft_away_score
            $table->string('ln')->nullable(); // league.name
            $table->string('hn')->nullable(); // home_team.name
            $table->string('an')->nullable(); // away_team.name
            $table->decimal('oo', 5, 2)->nullable(); // Original oo field
            $table->decimal('uo', 5, 2)->nullable(); // Original ou field
            $table->decimal('li', 5, 2)->nullable(); // li_decimal

            // Add indexes for frequently queried fields
            $table->index('gt');
            $table->index('st');
            $table->index('oo');
            $table->index('uo');
            $table->index('li');
            $table->index('f20');
            $table->index('f20a');

            $table->enum('is_wn', ['draw', 'win_half', 'win', 'loss_half', 'loss'])->nullable();
            $table->enum('is_ov', ['over', 'under', 'draw'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('igames');
    }
}; 