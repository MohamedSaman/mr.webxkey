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
        Schema::table('watch_stocks', function (Blueprint $table) {
            // Add assigned_stock column with default value of 0
            $table->integer('assigned_stock')->default(0)->after('available_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_stocks', function (Blueprint $table) {
            $table->dropColumn('assigned_stock');
        });
    }
};
