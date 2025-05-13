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
        Schema::create('watch_details', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Unique product code/SKU');
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('made_by')->nullable();
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->string('type')->nullable();
            $table->string('movement')->nullable();
            $table->string('dial_color')->nullable();
            $table->string('strap_color')->nullable();
            $table->string('strap_material')->nullable();
            $table->float('case_diameter_mm')->nullable();
            $table->float('case_thickness_mm')->nullable();
            $table->string('glass_type')->nullable();
            $table->string('water_resistance')->nullable();
            $table->text('features')->nullable();
            $table->string('image')->nullable();
            $table->string('warranty')->nullable();
            $table->text('description')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->string('location')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->text('discription')->nullable();
            $table->foreignId('supplier_id')->constrained('watch_suppliers');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_details');
    }
};
