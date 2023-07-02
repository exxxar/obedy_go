<?php

use App\Enums\OrderStatusEnum;
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
            $table->foreignId('user_id')->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('number')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('text')->nullable();
            $table->integer('status')->default(OrderStatusEnum::OrderAccepted->value);
            $table->integer('delivery_price')->default(0);
            $table->integer('summary_price')->default(0);
            $table->float('delivery_range')->default(0.00);
            $table->timestamps();
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
