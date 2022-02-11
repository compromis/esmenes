<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHiddenColumnToAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amendments', function (Blueprint $table) {
            $table->string('custom_num')->nullable()->after('registered_by_assembly');
            $table->string('custom_registered_by')->nullable()->after('custom_num');
            $table->boolean('hidden')->default(0)->aftrer('custom_registered_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amendments', function (Blueprint $table) {
            $table->dropColumn('custom_num');
            $table->dropColumn('custom_registered_by');
            $table->dropColumn('hidden');
        });
    }
}
