<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_works', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('authors');
			$table->string('publisher');
			$table->string('citations_url');
			$table->string('rank_publisher');
			$table->string('citations');
			$table->string('qualityCitations');
			$table->integer('author_id')->unsigned();
			$table->foreign('author_id')->references('id')->on('authors')->onDelete("cascade");
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
		Schema::dropIfExists('author_works');
	}

}
