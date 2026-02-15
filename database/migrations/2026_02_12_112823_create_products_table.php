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

            $table->decimal('current_price', 10, 2)->nullable();
            $table->decimal('old_price', 10, 2)->nullable();

            $table->string('image')->nullable();

            $table->decimal('rating', 2, 1)->nullable();
            $table->integer('review_count')->default(0);

            $table->decimal('score', 3, 1)->nullable(); // nota interna
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);

            $table->boolean('in_stock')->default(true);

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

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
