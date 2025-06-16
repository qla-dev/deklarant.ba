<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->float('total_weight_net')->nullable();
            $table->float('total_weight_gross')->nullable();
            $table->integer('total_num_packages')->nullable();
            $table->string('incoterm_destination')->nullable();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->float('weight_gross')->nullable();
            $table->float('weight_net')->nullable();
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('total_weight_net');
            $table->dropColumn('total_weight_gross');
            $table->dropColumn('total_num_packages');
            $table->dropColumn('incoterm_destination');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('weight_gross');
            $table->dropColumn('weight_net');
        });
    }
};
