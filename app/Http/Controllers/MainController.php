<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Vehicle;

class MainController extends Controller
{
    public function getCarList(Request $request) {
		//print_r($request->all()); exit;
		$rules = [
			'Id' => array('numeric'),
			'Type' => array('in:used,new'),
			'Year' => array('digits:4'),
			'Model' => array('string'),
			'Make' => array('string'),
			'Vin' => array('string'),
			//'Deleted' => array('in:0,1'),
			'Sort' => array('in:Type,Year,Model,Make,Vin'),
			'Order' => array('in:asc,desc'),
			'Page' => array('numeric'),
			'Limit' => array('numeric')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			//print_r($validator->errors()->all()); exit;
			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		$query = Vehicle::where('Deleted', 0);
		
		if ($request->Id)
			$query->where('Id', $request->Id);
		
		if ($request->Type)
			$query->where('Type', $request->Type);
		
		if ($request->Year)
			$query->where('Year', $request->Year);		

		if ($request->Model)
			$query->where('Model', $request->Year);
		
		if ($request->Make)
			$query->where('Make', $request->Make);
		
		if ($request->Vin)
			$query->where('Vin', $request->Vin);
		
		//if ($request->Deleted)
			//$query->where('Deleted', $request->Deleted);
		
		$carType = env('CAR_TYPE');
		if ($carType)
			$query->where('Type', $carType);
		
		if ($request->Sort) {
			$order = $request->Order ? $request->Order : 'asc';
			$query->orderBy($request->Sort, $order);
		}
		
		if ($request->Limit) {
			$query->limit($request->Limit);
			if ($request->Page) {
				$offset = $request->Page * $request->Limit;
				$query->offset($offset);
			}
		}
		
		$result = $query->get();
		//print_r($result); exit;
		
		return $result->toJson();
    }
	
	public function addCar(Request $request) {
		//print_r($request->all()); exit;
		$rules = [
			'Type' => array('required', 'in:used,new'),
			'Year' => array('required', 'digits:4'),
			'Model' => array('required', 'string'),
			'Make' => array('required', 'string'),
			'Vin' => array('required', 'string'),
			'Msrp' => array('numeric'),
			'Miles' => array('numeric')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			//print_r($validator->errors()->all()); exit;
			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		$data = new Vehicle();
		$data->Type = $request->Type;
		$data->Year = $request->Year;
		$data->Model = $request->Model;
		$data->Make = $request->Make;
		$data->Vin = $request->Vin;
		$data->DateAdded = date('Y-m-d H:i:s');
		$msrp = $request->Msrp ? $request->Msrp : 0;
		$data->Msrp = $msrp;
		$miles = $request->Miles ? $request->Miles : 0;
		$data->Miles = $miles;
		
		$data->save();
		
		$result['Id'] = $data->Id;
		
		return json_encode($result);
	}
	
	public function delCar(Request $request) {
		//print_r($request->all()); exit;
		$rules = [
			'Id' => array('required', 'numeric')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			//print_r($validator->errors()->all()); exit;
			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		Vehicle::where('Id', $request->Id)->update(['Deleted' => 1]);
		
		$result['status'] = 'success';
		
		return json_encode($result);
	}
	
	public function editCar(Request $request) {
		//print_r($request->all()); exit;
		$rules = [
			'Id' => array('required', 'numeric'),
			'Type' => array('in:used,new'),
			'Year' => array('digits:4'),
			'Model' => array('string'),
			'Make' => array('string'),
			'Vin' => array('string'),
			'Msrp' => array('numeric'),
			'Miles' => array('numeric')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			//print_r($validator->errors()->all()); exit;
			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		$data = array();
		
		if ($request->Type)
			$data['Type'] = $request->Type;		
		if ($request->Year)
			$data['Year'] = $request->Year;
		if ($request->Model)
			$data['Model'] = $request->Model;
		if ($request->Make)
			$data['Make'] = $request->Make;
		if ($request->Vin)
			$data['Vin'] = $request->Vin;
		if ($request->Msrp)
			$data['Msrp'] = $request->Msrp;
		if ($request->Miles)
			$data['Miles'] = $request->Miles;
		
		if (!count($data)) {
			$result['status'] = '500';   
			$result['message'] = 'Please enter a value to update';
			return response()->json($result, 500);	
		}
		
		Vehicle::where('Id', $request->Id)->update($data);
		
		$result = Vehicle::where('Id', $request->Id)->get();
		
		return $result->toJson();
	}
}
