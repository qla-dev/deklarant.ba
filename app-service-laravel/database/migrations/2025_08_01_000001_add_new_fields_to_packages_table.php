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
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('token_price', 8, 2)->nullable()->after('price'); // Price per AI token
            $table->decimal('price_monthly', 10, 2)->nullable()->after('token_price'); // Monthly price
            $table->decimal('price_yearly', 10, 2)->nullable()->after('price_monthly'); // Yearly price
            $table->decimal('declaration_token_cost', 8, 2)->nullable()->after('price_yearly'); // Token cost per declaration
            $table->decimal('declaration_price', 8, 2)->nullable()->after('declaration_token_cost'); // Price per declaration
            $table->decimal('extra_page_price', 8, 2)->nullable()->after('declaration_price'); // Price for extra invoice page
            $table->integer('simultaneous_logins')->nullable()->after('extra_page_price'); // Number of allowed MAC addresses
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'token_price',
                'price_monthly',
                'price_yearly',
                'declaration_token_cost',
                'declaration_price',
                'extra_page_price',
                'simultaneous_logins',
            ]);
        });
    }
}; 