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
            // Make sure `document_history` already exists before running this
            $table->text('description')->nullable()->after('document_history');
            $table->text('icon')->nullable()->after('description');
            $table->string('speed_limit')->nullable()->after('icon');
            $table->integer('duration')->nullable()->after('speed_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('icon');
            $table->dropColumn('speed_limit');
            $table->dropColumn('duration');
        });
    }
};
