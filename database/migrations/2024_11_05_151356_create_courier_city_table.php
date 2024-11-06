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
        Schema::create('courier_city', function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->index();
            $table->unsignedSmallInteger('city_id')->index();
            $table->unique(['courier_id', 'city_id']);

            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_city');
    }
};
