<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitingWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('citing_works', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 500);
			$table->string('authors', 400);
			$table->string('publisher');
			$table->string('publisher_in_google')->nullable();
			$table->string('publisher_in_external_web')->nullable();
			$table->string('url')->nullable();
			$table->string('rank_publisher', 10);
			$table->string('year', 10);
			$table->integer('author_work_id')->unsigned();
			$table->foreign('author_work_id')->references('id')->on('author_works')->onDelete("cascade");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('citing_works');
	}

}
