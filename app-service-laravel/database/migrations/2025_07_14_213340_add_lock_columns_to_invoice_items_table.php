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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->boolean('weight_gross_locked')->default(false)->nullable(false);
            $table->boolean('weight_net_locked')->default(false)->nullable(false);
            $table->boolean('num_packages_locked')->default(false)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn([
                'weight_gross_locked',
                'weight_net_locked',
                'num_packages_locked'
            ]);
        });
    }
};
