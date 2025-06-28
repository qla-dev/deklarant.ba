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
        // Get all invoices and update their internal status if necessary
        $invoices = \App\Models\Invoice::all();
        foreach ($invoices as $invoice) {
            $invoice->updateInternalStatusIfNecessary();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is irreversable
    }
};
