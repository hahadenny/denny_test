<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Vehicle;
use DB;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
	public $Id;
	 
    public function testConnect()
    {
        $response = $this->get('/getCarList');
        $response->assertStatus(200);
		
		$response = $this->post('/addCar');
        $response->assertStatus(200);
		
		$response = $this->delete('/delCar');
        $response->assertStatus(200);
		
		$response = $this->patch('/editCar');
        $response->assertStatus(200);
    }
	
	public function testAddCar()
    {		
		$data['DateAdded'] =  date('Y-m-d H:i:s');
		$data['Type'] = 'new';
		$data['Msrp'] = '10000';
		$data['Year'] = '2011';
		$data['Make'] = 'Honda';
		$data['Model'] = 'CRV';
		$data['Miles'] = '8000';
		$data['Vin'] = 'ABCD12345678';
		
		$this->json('POST', '/addCar', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "Id"
            ]);
			
		global $carId;
		$carId = DB::getPdo()->lastInsertId();
	}
	
	public function testGetCarList()
    {
		global $carId;
		$data['Id' ] = $carId;
			
		$this->json('GET', '/getCarList', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(
				['*' => [
					"Id",
					"DateAdded",
					"Type",
					"Msrp",
					"Year",
					"Make",
					"Model",
					"Miles",
					"Vin",
					"Deleted"
            ]]); 
	}
	
	public function testEditCar() {
		global $carId;
		$data['Id' ] = $carId;
		$data['Vin'] = 'AAAAA5555544444';
			
		$this->json('PATCH', '/editCar', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(
				['*' => [
					"Id",
					"DateAdded",
					"Type",
					"Msrp",
					"Year",
					"Make",
					"Model",
					"Miles",
					"Vin",
					"Deleted"
            ]]); 
			
		$car = Vehicle::where('Id', $carId)->get()->first();
		$this->assertEquals($data['Vin'], $car->Vin);
	}
	
	public function testDelCar() {
		global $carId;
		$data['Id' ] = $carId;
		
		$this->json('DELETE', '/delCar', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
				'status' => 'success'
				]);		
				
		$car = Vehicle::where('Id', $carId)->get()->first();
		$this->assertEquals(1, $car->Deleted);
	}
}
