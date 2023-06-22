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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('day_index')->default(7);
            $table->string('image',1000)->nullable();
            $table->double('price')->default(0);
            $table->double('weight')->default(0);
            $table->foreignId('category_id')->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->unsignedInteger('food_part_id')->nullable();
            $table->json('positions');
            $table->boolean('is_week')->default(false);
            $table->boolean('addition')->default(false);
            $table->boolean('disabled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
