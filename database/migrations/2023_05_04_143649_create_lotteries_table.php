<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->string('image',1000);
            $table->string('title')->nullable();
            $table->longText('description');
            $table->json('occupied_places')->nullable();
            $table->integer('place_count')->default(0);
            $table->integer('free_place_count')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->date('date_end')->nullable();
            $table->unsignedInteger('win_promo_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotteries');
    }
};
