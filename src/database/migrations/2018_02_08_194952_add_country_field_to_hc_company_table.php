<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryFieldToHcCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hc_company', function(Blueprint $table)
        {
            $table->string('country_id', 2)->nullable();

            $table->unique(['code', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_company', function(Blueprint $table) {
            $table->dropUnique(['code', 'country_id']);
            $table->dropColumn('country_id');
        });
    }
}
