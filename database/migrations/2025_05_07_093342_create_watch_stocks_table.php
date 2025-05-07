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
        Schema::create('watch_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_stock')->default(0)->comment('Quantity available in shop');
            $table->integer('store_stock')->default(0)->comment('Quantity available in warehouse/store');
            $table->integer('damage_stock')->default(0)->comment('Quantity of damaged items');
            $table->integer('total_stock')->default(0)->comment('Total of all stock types');
            $table->integer('available_stock')->default(0)->comment('Stock available for sale');
            $table->foreignId('watch_id')->constrained('watch_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_stocks');
    }
};