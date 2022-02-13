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
	 
	private $test_username = 'denny_test';
	private $test_token = 'CjwKCAiA9aKQBhBREiwAyGP5lU0Fw85cvboak0HgbBkoU2xKS15kkiBHjHiKLlQ9FSBwnmxrnjutQRoChAIQAvD_BwE';
	
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
		
		$this->json('POST', '/addCar', $data, ['Accept' => 'application/json', 'UserName' => $this->test_username, 'Token' => $this->test_token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "Id"
            ]);
			
		global $carId;
		$carId = DB::getPdo()->lastInsertId();
	}
	
	public function testGetCar() {
		global $carId;
		
		$data = array();
			
		$this->json('GET', "/getCar/$carId", $data, ['Accept' => 'application/json', 'UserName' => $this->test_username, 'Token' => $this->test_token])
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
	
	public function testGetCarList()
    {
		global $carId;
		$data['Id'] = $carId;
			
		$this->json('GET', '/getCarList', $data, ['Accept' => 'application/json', 'UserName' => $this->test_username, 'Token' => $this->test_token])
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
		$data['Vin'] = 'AAAAA5555544444';
			
		$this->json('PATCH', "/editCar/$carId", $data, ['Accept' => 'application/json', 'UserName' => $this->test_username, 'Token' => $this->test_token])
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
		$data = array();
		
		$this->json('DELETE', "/delCar/$carId", $data, ['Accept' => 'application/json', 'UserName' => $this->test_username, 'Token' => $this->test_token])
            ->assertStatus(200)
            ->assertJson([
				'status' => 'success'
				]);		
				
		$car = Vehicle::where('Id', $carId)->get()->first();
		$this->assertEquals(1, $car->Deleted);
	}
}
