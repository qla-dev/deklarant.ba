<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Api\UploadController;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        UploadController::updateOldTasks();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is irreversable
    }
};
