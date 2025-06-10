<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Remove unique constraints from suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['tax_id']);
            $table->dropUnique(['contact_email']);
            $table->dropUnique(['contact_phone']);
            $table->string('contact_phone')->nullable()->change();
            $table->string('tax_id')->nullable()->change();
        });

        // Remove unique constraints from importers table
        Schema::table('importers', function (Blueprint $table) {
            $table->dropUnique(['tax_id']);
            $table->dropUnique(['contact_email']);
            $table->dropUnique(['contact_phone']);
            $table->text('address')->nullable()->change();
            $table->string('contact_email')->nullable()->change();
            $table->string('contact_phone')->nullable()->change();
            $table->string('tax_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Add back unique constraints to suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique('tax_id');
            $table->unique('contact_email');
            $table->unique('contact_phone');
        });

        // Add back unique constraints to importers table
        Schema::table('importers', function (Blueprint $table) {
            $table->unique('tax_id');
            $table->unique('contact_email');
            $table->unique('contact_phone');
        });
    }
}; 