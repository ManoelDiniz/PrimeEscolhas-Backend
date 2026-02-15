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
        Schema::table('affiliate_links', function (Blueprint $table) {
            $table->decimal('oldPrice', 10, 2)->nullable()->after('price');
            $table->string('discountPercentage')->nullable()->after('oldPrice');
            $table->decimal('currentPrice', 10, 2)->nullable()->after('discountPercentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_links', function (Blueprint $table) {
            $table->dropColumn(['oldPrice', 'discountPercentage', 'currentPrice']);
        });
    }
};
