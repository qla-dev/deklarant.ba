<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tariff_rates', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->string('tariff_rate');
            
            // Tariff rates for different countries/regions
            $table->string('EU')->nullable();
            $table->string('CEFTA')->nullable();
            $table->string('IRN')->nullable();
            $table->string('TUR')->nullable();
            $table->string('CHE_LIE')->nullable(); // Switzerland & Liechtenstein combined
            $table->string('ISL')->nullable();
            $table->string('NOR')->nullable();

            // Additional fields
            $table->string('section');
            $table->string('head');
            $table->string('english_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_rates');
    }
};
