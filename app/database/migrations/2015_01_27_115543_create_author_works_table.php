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
			$table->string('title', 500);
			$table->string('authors', 400);
			$table->string('publisher');
			$table->string('publisher_in_google')->nullable();
			$table->string('citations_url');
			$table->string('rank_publisher', 10);
			$table->string('citations', 15);
			$table->string('quality_citations', 15);
			$table->string('year', 10);
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
