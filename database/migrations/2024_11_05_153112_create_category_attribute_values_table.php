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
        Schema::create('category_attribute_values', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('attribute_slug');
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('attribute_id')->index();

            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attribute_values');
    }
};
