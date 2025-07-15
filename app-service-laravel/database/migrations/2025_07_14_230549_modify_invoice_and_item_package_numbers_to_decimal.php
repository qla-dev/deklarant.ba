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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_num_packages', 10, 3)->nullable()->change();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('num_packages', 10, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('total_num_packages')->nullable()->change();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->unsignedInteger('num_packages')->nullable()->change();
        });
    }
};
