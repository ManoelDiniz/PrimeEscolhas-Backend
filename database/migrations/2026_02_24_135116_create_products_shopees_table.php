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
        Schema::create('products_shopees', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedBigInteger('item_id');
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission', 8, 4);
            $table->decimal('price', 8, 2);
            $table->unsignedInteger('sales');
            $table->string('image_url');
            $table->string('shop_name');
            $table->string('product_link');
            $table->string('offer_link');
            $table->unsignedBigInteger('period_start_time');
            $table->unsignedBigInteger('period_end_time');
            $table->decimal('price_min', 8, 2);
            $table->decimal('price_max', 8, 2);
            $table->json('product_cat_ids');
            $table->decimal('rating_star', 3, 1);
            $table->unsignedInteger('price_discount_rate');
            $table->unsignedBigInteger('shop_id');
            $table->json('shop_type');
            $table->decimal('seller_commission_rate', 5, 2);
            $table->decimal('shopee_commission_rate', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_shopees');
    }
};
