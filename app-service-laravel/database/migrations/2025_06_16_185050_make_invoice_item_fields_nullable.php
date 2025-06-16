<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('item_code')->nullable()->change();
            $table->integer('version')->nullable()->change();
            $table->text('item_description_original')->nullable()->change();
            $table->string('item_description')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->float('base_price')->nullable()->change();
            $table->float('total_price')->nullable()->change();
            $table->string('currency')->nullable()->change();
            $table->json('best_customs_code_matches')->nullable()->change();
            $table->boolean('tariff_privilege')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('item_code')->nullable(false)->change();
            $table->integer('version')->nullable(false)->change();
            $table->text('item_description_original')->nullable(false)->change();
            $table->string('item_description')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->float('base_price')->nullable(false)->change();
            $table->float('total_price')->nullable(false)->change();
            $table->string('currency')->nullable(false)->change();
            $table->json('best_customs_code_matches')->nullable(false)->change();
            $table->boolean('tariff_privilege')->nullable(false)->change();
        });
    }
};
