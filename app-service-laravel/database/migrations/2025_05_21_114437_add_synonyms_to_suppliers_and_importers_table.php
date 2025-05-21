<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->json('synonyms')->nullable()->after('avatar');
        });

        Schema::table('importers', function (Blueprint $table) {
            $table->json('synonyms')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('synonyms');
        });

        Schema::table('importers', function (Blueprint $table) {
            $table->dropColumn('synonyms');
        });
    }
};

