<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorToProcessTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors_to_process', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('url', 250);
            $table->integer('delay')->unsigned();
            $table->timestamp('processing');
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
        Schema::dropIfExists('authors_to_process');
    }

}
