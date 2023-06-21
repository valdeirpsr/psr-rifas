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
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('thumbnail', 255);
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->string('slug', 255);
            $table->integer('total_numbers_available');
            $table->integer('buy_max')->unsigned()->default(300);
            $table->integer('buy_min')->unsigned()->default(1);
            $table->string('raffle', 64);
            $table->enum('status', ['archived', 'draft', 'published'])->default('draft');
            $table->dateTimeTz('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
