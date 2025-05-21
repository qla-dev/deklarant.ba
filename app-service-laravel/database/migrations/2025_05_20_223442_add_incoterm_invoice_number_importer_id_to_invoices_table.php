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
            $table->string('incoterm')->nullable();
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('importer_id')->nullable();
            $table->foreign('importer_id')->references('id')->on('importers')->onDelete('cascade');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            // Remove the existing country_of_origin column if it exists
            if (Schema::hasColumn('invoice_items', 'country_of_origin')) {
                $table->dropColumn('country_of_origin');
            }
            
            // Add the new country_of_origin column
            $table->string('country_of_origin')->nullable();
            $table->string('quantity_type')->nullable();
            $table->string('item_description_translated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('incoterm');
            $table->dropColumn('invoice_number');
            $table->dropForeign(['importer_id']);
            $table->dropColumn('importer_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('country_of_origin');
            $table->dropColumn('quantity_type');
            $table->dropColumn('item_description_translated');
        });
    }
};
