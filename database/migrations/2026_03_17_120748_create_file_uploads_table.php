<?php

use Domain\Store\Models\Store;
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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class)->constrained();
            $table->string('original_name', 200);
            $table->string('stored_name', 100);
            $table->unsignedInteger('size');
            $table->boolean('is_private')->default(false);

            $table->unique(['store_id', 'stored_name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
