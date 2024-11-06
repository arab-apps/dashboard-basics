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
        Schema::create('attribute_order', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedSmallInteger('attribute_id')->index();
            $table->unsignedSmallInteger('attribute_value_id')->index()->nullable();
            $table->string('value')->nullable();

            $table->unique(['order_id', 'attribute_id']);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('category_attribute_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_order');
    }
};
