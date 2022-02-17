<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Vehicle;

class MainController extends Controller
{
    public function getCarList(Request $request) {
		$rules = [
			'Id' => array('numeric'),
			'DateAdded' => array('regex:/^\d{4}\-\d{2}\-\d{2}$/'),
			'Type' => array('in:used,new'),
			'Year' => array('digits:4'),
			'Model' => array('string'),
			'Make' => array('string'),
			'Vin' => array('string'),
			'MinYear' => array('digits:4'),
			'MaxYear' => array('digits:4'),
			'MinMsrp' => array('numeric'),
			'MaxMsrp' => array('numeric'),
			'MinMiles' => array('numeric'),
			'MaxMiles' => array('numeric'),
			'Sort' => array('in:Id,DateAdded,Type,Year,Model,Make,Vin'),
			'Order' => array('in:asc,desc'),
			'Sort2' => array('in:Id,DateAdded,Type,Year,Model,Make,Vin'),
			'Order2' => array('in:asc,desc'),
			'Sort3' => array('in:Id,DateAdded,Type,Year,Model,Make,Vin'),
			'Order3' => array('in:asc,desc'),
			'Page' => array('numeric'),
			'Limit' => array('numeric')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		$query = Vehicle::where('Deleted', 0);
		
		if ($request->Id)
			$query->where('Id', $request->Id);
		
		if ($request->DateAdded)
			$query->where(DB::raw('date(DateAdded)'), $request->DateAdded);
		
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
		
		if ($request->MinYear)
			$query->where('Year', '>=', $request->MinYear);	
		
		if ($request->MaxYear)
			$query->where('Year', '<=', $request->MaxYear);	
		
		if ($request->MinMsrp)
			$query->where('Msrp', '>=', $request->MinMsrp);	
		
		if ($request->MaxMsrp)
			$query->where('Msrp', '<=', $request->MaxMsrp);	
		
		if ($request->MinMiles)
			$query->where('Miles', '>=', $request->MinMiles);	
		
		if ($request->MaxMiles)
			$query->where('Miles', '<=', $request->MaxMiles);	
		
		$carType = env('CAR_TYPE');
		if ($carType)
			$query->where('Type', $carType);
		
		if ($request->Sort) {
			$order = $request->Order ? $request->Order : 'asc';
			$query->orderBy($request->Sort, $order);
		}
		
		if ($request->Sort2) {
			$order2 = $request->Order2 ? $request->Order2 : 'asc';
			$query->orderBy($request->Sort2, $order2);
		}
		
		if ($request->Sort3) {
			$order3 = $request->Order3 ? $request->Order3 : 'asc';
			$query->orderBy($request->Sort3, $order3);
		}
		
		if ($request->Limit) {
			$query->limit($request->Limit);
			if ($request->Page) {
				$offset = $request->Page * $request->Limit;
				$query->offset($offset);
			}
		}
		
		$result = $query->get();
		
		return response()->json($result);
    }
	
	public function getCar($Id) {
		$rules = [
			'Id' => array('required', 'numeric')
		];
				
		$messages = [
		];
		
		$input['Id'] = $Id;
		
		$validator = Validator::make($input, $rules, $messages);
		
		if ($validator->fails()) {			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		$query = Vehicle::where([['Id', $Id], ['Deleted', 0]]);
		
		$carType = env('CAR_TYPE');
		if ($carType)
			$query->where('Type', $carType);
		
		$result = $query->first();
		
		return response()->json($result);
	}
	
	public function addCar(Request $request) {
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
		
		return response()->json($result);
	}
	
	public function delCar($Id) {
		$rules = [
			'Id' => array('required', 'numeric')
		];
				
		$messages = [
		];
		
		$input['Id'] = $Id;
		
		$validator = Validator::make($input, $rules, $messages);
		
		if ($validator->fails()) {			
			$result['status'] = '500';   
			$result['message'] = $validator->errors()->first();
			
			return response()->json($result, 500);	
		}
		
		Vehicle::where('Id', $Id)->update(['Deleted' => 1]);
		
		$result['status'] = 'success';
		
		return response()->json($result);
	}
	
	public function editCar($Id, Request $request) {
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
		
		$input = $request->all();
		$input['Id'] = $Id;
		
		$validator = Validator::make($input, $rules, $messages);
		
		if ($validator->fails()) {			
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
		
		Vehicle::where('Id', $Id)->update($data);
		
		$result = Vehicle::where('Id', $Id)->first();
		
		return response()->json($result);
	}
}
