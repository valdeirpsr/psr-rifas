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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('ticket_url', 255);
            $table->string('payment_code', 40);
            $table->string('date_of_expiration', 40);
            $table->decimal('transaction_amount', 10, 2);
            $table->string('qr_code', 255);
            $table->string('date_approved');
            $table->timestamps();

            $table->primary('id');
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
