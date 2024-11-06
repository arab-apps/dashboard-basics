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
        Schema::create('order_bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->decimal('price', 8, 2)->index();
            $table->enum('status', ['pending', 'accepted', 'declined', 'cancelled'])->index();
            $table->timestamps();

            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_bids');
    }
};
