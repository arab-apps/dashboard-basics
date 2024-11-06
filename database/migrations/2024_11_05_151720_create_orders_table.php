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
            $table->string('uid')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('courier_id')->index()->nullable();
            $table->unsignedSmallInteger('provider_id')->index()->nullable();
            $table->longText('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('auto_accept_courier')->default(false);
            $table->enum('payment_method', ['cash', 'visa'])->default('cash');
            $table->unsignedBigInteger('receiving_address_id')->index();
            $table->unsignedBigInteger('delivering_address_id')->index();
            $table->string('contact_number');
            $table->string('distance_in_km');
            $table->Float('weight_in_kg')->nullable();
            $table->enum('status', ['pending', 'accepted', 'receiving', 'received', 'delivering', 'delivered', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->timestamp('scheduled_at');
            $table->decimal('delivering_fee', 8, 2)->default(0);
            $table->decimal('object_price', 8, 2)->nullable();
            $table->decimal('total_cost', 8, 2)->nullable();
            $table->timestamp('receiving_starts_at')->nullable();
            $table->timestamp('received_starts_at')->nullable();
            $table->timestamp('delivering_starts_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('set null');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');
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
