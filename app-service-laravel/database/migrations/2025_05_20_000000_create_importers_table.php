<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('importers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner');
            $table->text('address');
            $table->string('avatar')->nullable();
            $table->string('tax_id')->unique();
            $table->string('contact_email')->unique();
            $table->string('contact_phone')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importers');
    }
};
