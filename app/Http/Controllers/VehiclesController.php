<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use App\Http\Requests\SaveVehicle;

Use App\Vehicle;

class VehiclesController extends Controller
{
	/** 
	 * Get a vehicle based on ID.
	 *
	 * @param $id - The ID of the vehicle
	 * @return Json App\Vehicle - The requested vehicle
	*/
	public function get($id)
	{
		return Vehicle::findOrFail($id);
	}

	/** 
	 * Create a new vehicle in the db associated with a customer.
	 *
	 * @param $request - SaveVehicle custom request 
	 * @return Json App\Vehicle - The created vehicle
	*/
    public function create(SaveVehicle $request)
    {
    	return $this->genericSave(new Vehicle, $request);
    }

	/** 
	 * Updates a vehicle in the db.
	 *
	 * @param $request - SaveVehicle custom request 
	 * @return Json App\Vehicle - The updated vehicle
	*/
    public function update(SaveVehicle $request)
    {
    	return $this->genericSave(Vehicle::findOrFail($request->id), $request);
    }

	/** 
	 * Removes a vehicle from the db.
	 *
	 * @param $id The id of the vehicle to remove 
	 * @return Int - The id of the removed vehicle
	*/
    public function remove($id)
    {
    	// Find the vehicle
    	$vehicle = Vehicle::findOrFail($id);

    	// If the vehicle has work orders it cannot be removed
    	if(count($vehicle->work_orders) > 0){
    		// Failed response
	        return response()->json([
	            'result' => 'error',
	            'message' => 'Customer with vehicles cannot be removed.'
	        ], 422);      		
    	}

    	return $this->genericRemove($vehicle);
    }
}
