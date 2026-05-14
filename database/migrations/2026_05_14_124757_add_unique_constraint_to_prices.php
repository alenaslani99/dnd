<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropIndex(['product_variant_id', 'effective_date']);
            $table->unique(['product_variant_id', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropUnique(['product_variant_id', 'effective_date']);
            $table->index(['product_variant_id', 'effective_date']);
        });
    }
};
