<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSupplierFieldsNullable extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('owner')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('contact_email')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('owner')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('contact_email')->nullable(false)->change();
        });
    }
}

