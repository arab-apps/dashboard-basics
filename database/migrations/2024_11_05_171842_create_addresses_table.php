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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addressable_id')->index()->nullable();
            $table->string('addressable_type')->nullable();
            $table->unsignedSmallInteger('city_id')->index();
            $table->string('title');
            $table->string('display_title');
            $table->string('lat');
            $table->string('lng');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('home_number')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
