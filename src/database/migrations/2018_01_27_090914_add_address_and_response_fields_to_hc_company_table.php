<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressAndResponseFieldsToHcCompanyTable extends Migration
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
            $table->string('address')->nullable();
            $table->json('original_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_company', function(Blueprint $table)
        {
            $table->dropColumn(['address', 'original_data']);
        });
    }
}
