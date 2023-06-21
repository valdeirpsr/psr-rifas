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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_fullname', 64);
            $table->string('customer_email', 100);
            $table->string('customer_telephone', 20);
            $table->json('numbers_reserved', 20)->nullable();
            $table->enum('status', ['archived', 'expired', 'paid', 'reserved'])->default('reserved');
            $table->timestamps();

            $table->foreignId('rifa_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
