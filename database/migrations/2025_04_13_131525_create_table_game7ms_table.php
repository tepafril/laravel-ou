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
        Schema::create('game7ms', function (Blueprint $table) {
            $table->id();
            $table->date('gd')->nullable(); // Game Time
            $table->dateTime('gt')->nullable(); // Game Time
            $table->string('f1')->nullable();
            $table->string('f4')->nullable();
            $table->string('f5')->nullable();
            $table->string('f6')->nullable();
            $table->string('f7')->nullable();
            $table->string('f8')->nullable();
            $table->integer('f11')->nullable();
            $table->string('f12')->nullable();
            $table->integer('f13')->nullable();
            $table->integer('f14')->nullable();
            $table->integer('f15')->nullable();
            $table->integer('f17')->nullable();
            $table->string('f18')->nullable();
            $table->string('f19')->nullable();
            $table->string('f20')->nullable();

            // Define foreign keys
            $table->unsignedBigInteger('tid')->nullable(); // Foreign key to matches (cid)
            $table->unsignedBigInteger('taid')->nullable(); // Foreign key to matches (tid)
            $table->unsignedBigInteger('thid')->nullable(); // Foreign key to matches (thid)

            // Create foreign key constraints
            $table->foreign('tid')->references('id')->on('league7ms')->onDelete('cascade');
            $table->foreign('taid')->references('id')->on('team7ms')->onDelete('cascade');
            $table->foreign('thid')->references('id')->on('team7ms')->onDelete('cascade');

            $table->integer('mid')->nullable();
            $table->string('isrun')->nullable();

            $table->string('ah1_0')->nullable();
            $table->string('ah1_1')->nullable();
            $table->string('ah1_2')->nullable();
            $table->string('ah1_3')->nullable();
            $table->string('ah1_4')->nullable();

            $table->string('hda1_0')->nullable();
            $table->string('hda1_1')->nullable();
            $table->string('hda1_2')->nullable();
            $table->string('hda1_3')->nullable();

            $table->string('ou1_0')->nullable();
            $table->string('ou1_1')->nullable();
            $table->string('ou1_2')->nullable();
            $table->string('ou1_3')->nullable();

            $table->string('ah2_0')->nullable();
            $table->string('ah2_1')->nullable();
            $table->string('ah2_2')->nullable();
            $table->string('ah2_3')->nullable();
            $table->string('ah2_4')->nullable();


            $table->string('hda2_0')->nullable();
            $table->string('hda2_1')->nullable();
            $table->string('hda2_2')->nullable();
            $table->string('hda2_3')->nullable();

            $table->string('ou2_0')->nullable();
            $table->string('ou2_1')->nullable();
            $table->string('ou2_2')->nullable();
            $table->string('ou2_3')->nullable();


            $table->integer('status')->nullable();
            $table->integer('ht_home_score')->nullable();
            $table->integer('ht_away_score')->nullable();
            $table->integer('ft_home_score')->nullable();
            $table->integer('ft_away_score')->nullable();

            $table->enum('wn', ['draw', 'win_half', 'win', 'loss_half', 'loss'])->nullable();
            $table->enum('ov', ['over', 'under', 'draw'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game7ms');
    }
};
