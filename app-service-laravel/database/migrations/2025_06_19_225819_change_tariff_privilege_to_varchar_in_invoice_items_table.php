<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTariffPrivilegeToVarcharInInvoiceItemsTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('tariff_privilege')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->boolean('tariff_privilege')->default(false)->change();
        });
    }
}
