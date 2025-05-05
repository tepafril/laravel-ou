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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->date('gd')->nullable(); // Game Time
            $table->dateTime('gt')->nullable(); // Game Time

            // Define foreign keys
            $table->unsignedBigInteger('cid')->nullable(); // Foreign key to matches (cid)
            $table->unsignedBigInteger('tid')->nullable(); // Foreign key to matches (cid)
            $table->unsignedBigInteger('taid')->nullable(); // Foreign key to matches (tid)
            $table->unsignedBigInteger('thid')->nullable(); // Foreign key to matches (thid)

            // Create foreign key constraints
            $table->foreign('cid')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('tid')->references('id')->on('leagues')->onDelete('cascade');
            $table->foreign('taid')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('thid')->references('id')->on('teams')->onDelete('cascade');

            $table->integer('mb')->nullable();
            $table->string('hr')->nullable();
            $table->string('ar')->nullable();
            $table->string('sg')->nullable();
            $table->string('s')->nullable();
            $table->string('hc')->nullable();
            $table->string('ns')->nullable();
            $table->integer('tdid')->nullable();
            $table->string('ml')->nullable();
            $table->string('bir')->nullable();
            $table->string('htb')->nullable();
            $table->integer('evid')->nullable();
            $table->string('mid')->nullable();
            $table->string('himg')->nullable();
            $table->string('aimg')->nullable();
            $table->string('ty')->nullable();
            $table->string('cimg')->nullable();
            $table->string('cimg2')->nullable();
            $table->string('tseq')->nullable();
            $table->integer('dorder')->nullable();
            $table->string('ctid')->nullable();
            $table->string('ls_status')->nullable();
            $table->string('display_lineup')->nullable();
            $table->string('ft_show')->nullable();

            $table->decimal('oo', 5 , 2)->nullable();
            $table->decimal('uo', 5 , 2)->nullable();
            $table->decimal('li_decimal', 5, 2)->nullable();
            $table->integer('li')->nullable();
            $table->integer('sp')->nullable();

            $table->integer('ft_home_score')->nullable();
            $table->integer('ft_away_score')->nullable();
            $table->integer('ht_home_score')->nullable();
            $table->integer('ht_away_score')->nullable();
            $table->integer('status')->nullable();

            $table->enum('status7m', ['none', 'done', 'failed'])->default('none');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csgames');
    }
};