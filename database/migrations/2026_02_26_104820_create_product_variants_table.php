<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Domain\Catalog\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignIdFor(Product::class)->constraind();
            $table->string('sku', 100);
            // 99999999.99
            $table->decimal('price', 10, 2)->unsigned();
            $table->unsignedBigInteger('stock')->default(0);
            $table->json('attributes');
            $table->timestamps();
            // Sku is scoped to be unique in its product 
            $table->unique(['product_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
