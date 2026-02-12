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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();

            $table->decimal('current_price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();

            $table->string('image')->nullable();
            $table->string('affiliate_url')->nullable();

            $table->string('marketplace')->nullable();

            $table->integer('review_count')->default(0);
            $table->decimal('rating', 2, 1)->nullable();

            $table->boolean('in_stock')->default(true);

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
