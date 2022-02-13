<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User', function (Blueprint $table) {
            $table->increments('Id');
			$table->string('UserName');
			$table->string('Token');
        });
		
		// Insert some test data
        DB::table('User')->insert([
            'UserName' => env('TEST_USERNAME'),
			'Token' => env('TEST_TOKEN')
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('User');
    }
}
