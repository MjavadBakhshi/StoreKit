<?php

namespace Database\Migrations\Concerns;

use Domain\Catalog\Enums\SEORobot;

trait SEOFields
{
    function upSEOFields($table)
    {
            $table->string('page_title', 60)->nullable();
            $table->string('meta_description', 155)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('canonical_url')->nullable();
            $table->text('redirect301_url')->nullable();
            $table->enum('robot', [SEORobot::values()])->nullable();
    }

    function downSEOFields($table)
    {
        $table->dropColumn('page_title');
        $table->dropColumn('meta_description');
        $table->dropColumn('meta_keywords');
        $table->dropColumn('canonical_url');
        $table->dropColumn('redirect301_url');
        $table->dropColumn('robot');
    }
}