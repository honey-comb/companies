<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateHcCompanyCategoryConnectionTable
 */
class CreateHcCompanyCategoryConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hc_company_category_connection', function (Blueprint $table) {
            $table->increments('count');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->char('company_id', 36)->index();
            $table->char('category_id', 36)->index();

            $table->foreign('company_id')->references('id')->on('hc_company')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('hc_company_category')->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_company_category_connection');
    }
}
