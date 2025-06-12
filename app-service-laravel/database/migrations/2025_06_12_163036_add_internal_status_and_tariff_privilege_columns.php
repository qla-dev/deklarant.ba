<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternalStatusAndTariffPrivilegeColumns extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('internal_status')->default(0)->after('scan_time');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->boolean('tariff_privilege')->default(false)->after('currency');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('internal_status');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('tariff_privilege');
        });
    }
}
