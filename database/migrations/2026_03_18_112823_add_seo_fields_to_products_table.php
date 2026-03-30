<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Domain\Catalog\Enums\SEORobot;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->text('tags')->nullable();
            $table->string('page_title', 60)->nullable();
            $table->string('meta_description', 155)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('canonical_url')->nullable();
            $table->text('redirect301_url')->nullable();
            $table->enum('robot', [SEORobot::values()])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tags');
            $table->dropColumn('page_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('canonical_url');
            $table->dropColumn('redirect301_url');
            $table->dropColumn('robot');

        });
    }
};
