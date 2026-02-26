<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Domain\Catalog\Enums\ProductStatus;
use Domain\Store\Models\Store;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignIdFor(Store::class)->constrained();
            $table->string('title');
            $table->string('slug', 100);
            $table->enum('status', ProductStatus::values())
                ->default(
                    ProductStatus::Draft
                );

            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'slug']);
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
