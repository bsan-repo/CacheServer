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
			$table->string('name', 100);
			$table->string('authors');
			$table->string('publisher');
			$table->string('rankPublisher', 10);
			$table->integer('authorwork_id')->unsigned();
			$table->foreign('authorwork_id')->references('id')->on('author_works')->onDelete("cascade");
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
