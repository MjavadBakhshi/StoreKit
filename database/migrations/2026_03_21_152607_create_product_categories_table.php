<?php

use Database\Migrations\Concerns\SEOFields;
use Domain\Store\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Domain\Catalog\Models\ProductCategory;

return new class extends Migration
{
    use SEOFields;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignIdFor(ProductCategory::class, 'parent_id')
                    ->nullable()
                    ->constrained();
            $table->string('title', 200);
            $table->string('slug', 200);
            $table->boolean('status')->default(true);
            $table->boolean('menu_visibility')->default(false);

            // seo fields
            $this->upSEOFields($table);
            
            $table->unique(['store_id', 'slug']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
