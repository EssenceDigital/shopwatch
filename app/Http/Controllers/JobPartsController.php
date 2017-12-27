<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\SaveJobPart;

use App\JobPart;
use App\Job;

class JobPartsController extends Controller
{
	private function calcAndSaveJobTotals($part, $job, $prevTotals = false)
	{

    	// If the prevPart is set then the part was just updated and requires job totals to be adjusted
    	if(! $prevTotals){
	    	// PrevPart not set - Cache job totals (for calculations below)
	    	$current_parts_total_cost = floatval($job->parts_total_cost);  
	    	$current_parts_total_billed = floatval($job->parts_total_billed);  
	    	$current_grand_total = floatval($job->job_grand_total);    		   		

    	} else {
    		// Cache and rollback job totals by subtracting the previous part totals (for calculations below)
    		$current_parts_total_cost = floatval($job->parts_total_cost) - floatval($prevTotals['cost']);
    		$current_parts_total_billed = floatval($job->parts_total_billed) - floatval($prevTotals['billing']);
	    	$current_grand_total = floatval($job->job_grand_total) - floatval($prevTotals['billing']); 	    		    	 		
    	}

    	// Calculate new parts total cost based on the part just created
    	$job->parts_total_cost = round(($current_parts_total_cost + floatval($part->total_cost)), 3); 

    	// Calculate new parts total billed based on the part just created
    	$job->parts_total_billed = round(($current_parts_total_billed + floatval($part->billing_price)), 3);  

    	// Calculate the new grand total using new total billed amount
    	$job->job_grand_total = round(($current_grand_total + floatval($part->billing_price)), 3);

    	// Save the job with updated totals
    	$job = $this->genericSave($job);

    	// Load job relationships so we can return the updated job
    	$job->load('parts');	

    	return $job;	
	}

    public function create(SaveJobPart $request)
    {
    	// First get the job so we can update the totals and run some checks
    	$job = Job::findOrFail($request->job_id);

		// Check if parent work order is still open (can only create a part on an open (not invoiced) work order)
		// Check if parent job is marked as complete (cannot create a part on a job marked complete)
		if($this->guardWorkOrder($job->work_order_id, $job->is_complete)){
	    	// Save the part
	    	$part = $this->genericSave(new JobPart, $request);

	    	// Update the parent job with new totals based on added part
	    	$job = $this->calcAndSaveJobTotals($part, $job);	
	    	
	    	return $job;			
		} else {
			// Failed response. Work order is close or job is complete
			return response()->json($this->woGuardResponse, 422);	
		}
    }

    public function update(SaveJobPart $request)
    {
    	// First get the job so we can update the total
    	$job = Job::findOrFail($request->job_id); 
    	   	
		// Check if parent work order is still open (can only modify a part on an open (not invoiced) work order)
		// Check if parent job is marked as complete (cannot modify a part on a job marked complete)
		if($this->guardWorkOrder($job->work_order_id, $job->is_complete)){
	    	// Get the part
	    	$part = JobPart::findOrFail($request->id);
	    	// Cache the previous part cost and billing price
	    	$part_cost = $part->total_cost;
	    	$part_billing = $part->billing_price;

	    	// Save the part and cache result
	    	$part = $this->genericSave($part, $request);

	    	// Update the parent job with new totals based on added part
	    	$job = $this->calcAndSaveJobTotals($part, 
	    		$job, 
	    		// Previous totals for rolling backing job totals
	    		['cost'=> $part_cost, 'billing'=> $part_billing]
	    	);

	    	return $job;			
		} else {
			// Failed response. Work order is close or job is complete
			return response()->json($this->woGuardResponse, 422);	
		}
    }

    public function remove($id)
    {
    	// Get the part
    	$part = JobPart::findOrFail($id);
    	// Get the parent job
    	$job = Job::findOrFail($part->job_id);

		// Check if parent work order is still open (can only remove a part from an open (not invoiced) work order)
		// Check if parent job is marked as complete (cannot remove a part from a job marked complete)
		if($this->guardWorkOrder($job->work_order_id, $job->is_complete)){
	    	// Cache the part cost and total billed
	    	$part_total_cost = $part->total_cost;
	    	$part_total_billed = $part->billing_price;	
	    			
			// Part is allowed to be removed, so remove it
			$id = $this->genericRemove($part);

			// Update job totals by removing the deleted part costs
    		$job->parts_total_cost = floatval($job->parts_total_cost) - floatval($part_total_cost);
    		$job->parts_total_billed = floatval($job->parts_total_billed) - floatval($part_total_billed);
	    	$job->job_grand_total = floatval($job->job_grand_total) - floatval($part_total_billed);  
	    	// Save job
	    	$this->genericSave($job);

			return $id;
		} else {
			// Failed response. Work order is close or job is complete
			return response()->json($this->woGuardResponse, 422);	
		}   	
    }
}
