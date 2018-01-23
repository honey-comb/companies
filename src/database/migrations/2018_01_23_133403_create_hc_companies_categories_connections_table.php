<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcCompaniesCategoriesConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_companies_categories_connections', function(Blueprint $table)
		{
            $table->increments('count');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

			$table->char('company_id', 36)->index();
			$table->char('category_id', 36)->index();

            $table->foreign('company_id')->references('id')->on('hc_companies')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('category_id')->references('id')->on('hc_companies_categories')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hc_companies_categories_connections');
	}

}
