<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Vehicle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Vehicle', function (Blueprint $table) {
	    $table->increments('Id');
	    $table->dateTime('DateAdded');
	    $table->enum('Type', ['used', 'new']);
	    $table->decimal('Msrp', 20, 2);
	    $table->integer('Year');
	    $table->string('Make');
	    $table->string('Model');
	    $table->integer('Miles');
	    $table->string('Vin');
	    $table->boolean('Deleted')->default(0);
        });

	// Insert some test data
        DB::table('Vehicle')->insert([[
            'DateAdded' => DB::raw('NOW()'),
            'Type' => 'used',
	    'Msrp' => '28000',
	    'Year' => '2019',
	    'Make' => 'Honda',
	    'Model' => 'Civic',
	    'Miles' => '50000',
	    'Vin' => 'ABCD12345678',
	    ],
	    [
            'DateAdded' => DB::raw('NOW()'),
            'Type' => 'new',
            'Msrp' => '35000',
            'Year' => '2022',
            'Make' => 'Toyata',
            'Model' => 'Cramy',
            'Miles' => '3000',
            'Vin' => 'DEFG45678901',
	    ]	
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Vehicle');
    }
}
