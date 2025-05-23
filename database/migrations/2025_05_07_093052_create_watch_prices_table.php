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
        Schema::create('watch_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('supplier_price', 10, 2)->comment('Price from supplier');
            $table->decimal('selling_price', 10, 2)->comment('Regular selling price');
            $table->decimal('discount_price', 10, 2)->nullable()->comment('Discounted price if applicable');
            $table->foreignId('watch_id')->constrained('watch_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_prices');
    }
};
