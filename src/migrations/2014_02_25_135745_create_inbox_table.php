<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboxTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inbox', function($table){
            $table->increments('id');
            $table->integer('user_id');
            $table->string('type', 64)->default('default');
            $table->enum('status', array('read', 'unread'))->default('unread');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
            $table->index(array('user_id', 'created_at'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inbox');
	}

}
