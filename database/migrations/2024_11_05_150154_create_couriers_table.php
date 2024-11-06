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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('rate_average', 1)->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('social_id')->nullable();
            $table->enum('social_type', ['google', 'facebook'])->nullable();
            $table->string('password')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('deactivation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
