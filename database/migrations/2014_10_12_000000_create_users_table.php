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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->unique();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->string('password');
            $table->json('addresses')->nullable();
           /* $table->boolean('active')->default(false);
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->integer('trusted_count')->default(0);
            $table->integer('trusted_limit')->default(1000);*/
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
