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
            $table->integer('sold_count')->default(0)->after('available_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_stocks', function (Blueprint $table) {
            $table->dropColumn('sold_count');
        });
    }
};
