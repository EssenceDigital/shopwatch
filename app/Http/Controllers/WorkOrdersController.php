<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Http\Requests\SaveWorkOrder;
use App\WorkOrder;
use App\Vehicle;

class WorkOrdersController extends Controller
{

	/** 
	 * Filters the work order table based on passed params.
	 * 
	 * @return Json Collection
	*/
	public function filter($created_at = false, $is_invoiced = false)
	{
		// Possible where fields for the filter
		$whereFields = [
			['filter' => $created_at, 'field' => 'created_at', 'value' => "%{$created_at}%", 'conditional' => 'like'],
			['filter' => $is_invoiced, 'field' => 'is_invoiced', 'value' => $is_invoiced, 'conditional' => '=']
		];

		return $this->genericFilter(WorkOrder::with(['vehicle', 'jobs', 'jobs.parts'])->orderBy('created_at', 'asc'), $whereFields);			
	}

	/** 
	 * Get a work order based on ID.
	 *
	 * @param $id - The ID of the work order
	 * @return Json App\WorkOrder - The requested work order
	*/
	public function get($id)
	{
		return WorkOrder::with(['vehicle', 'jobs', 'jobs.parts'])->findOrFail($id);
	}

	/** 
	 * Get all work orders associated with the supplied customer ID
	 *
	 * @param $id - The ID of the customer
	 * @return Json Collection - The work orders associated with the customer
	*/
	public function getCustomers($id)
	{
		// Get all work orders first
		return WorkOrder::with(['vehicle', 'jobs', 'jobs.parts'])->where('customer_id', $id)->get();		
	}

	/** 
	 * Get all work orders associated with the supplied vehicle ID
	 *
	 * @param $id - The ID of the vehicle
	 * @return Json Collection - The work orders associated with the vehicle
	*/
	public function getVehicles($id)
	{
		return WorkOrder::with(['vehicle', 'jobs', 'jobs.parts'])->where('vehicle_id', $id)->get();
	}

	/** 
	 * Create a new work order in the db associated with a vehicle.
	 *
	 * @param $request - SaveWorkOrder custom request 
	 * @return Json App\WorkOrder - The created work order
	*/
    public function create(SaveWorkOrder $request)
    {
    	// Get the vehicle so we have the customer ID
    	$vehicle = Vehicle::findOrFail($request->vehicle_id);
    	// Merge the customer ID into the request
    	$request->merge(['customer_id' => $vehicle->customer_id]);

    	return $this->genericSave(New WorkOrder, $request);
    }

	/** 
	 * Removes a work order from the db.
	 *
	 * @param $id The id of the work order to remove 
	 * @return Int - The id of the removed work order
	*/
    public function remove($id)
    {
    	// Find the work order
    	$wo = WorkOrder::with(['jobs'])->findOrFail($id);

    	// If the work order has jobs it cannot be removed
    	if(count($wo->jobs) > 0 || $wo->invoice_id != null){
    		// Failed response
	        return response()->json([
	            'result' => 'error',
	            'message' => 'This work order has been closed (invoiced) or there are jobs associated with it and cannot be removed.'
	        ], 422);      		
    	}

    	return $this->genericRemove($wo);    	
    }
}
