<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHcComapniesCategoriesTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hc_companies_categories_translations', function(Blueprint $table)
		{
            $table->increments('count');
            $table->uuid('id')->unique();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

			$table->char('record_id', 36)->index();
			$table->char('language_code', 36)->index();

            $table->foreign('record_id')->references('id')->on('hc_companies_categories')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('language_code')->references('id')->on('hc_languages')
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
		Schema::drop('hc_companies_categories_translations');
	}

}
