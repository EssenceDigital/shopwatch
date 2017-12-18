<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SaveJobPart;

use App\JobPart;
use App\Job;

class JobPartsController extends Controller
{
	private function calcAndSaveJobTotals($part)
	{
    	// First get the job so we can update the total
    	$job = Job::findOrFail($part->job_id);

    	// Get the current parts total cost from job
    	$current_parts_total_cost = floatval($job->parts_total_cost);
    	// Calculate new parts total cost based on the part just created
    	$new_parts_total_cost = round($current_parts_total_cost + $part->total_cost, 3);
    	// Set new total in job model
    	$job->parts_total_cost = $new_parts_total_cost;

    	// Get the current parts total billed from job
    	$current_parts_total_billed = floatval($job->parts_total_billed);    	
    	// Calculate new parts total billed based on the part just created
    	$new_parts_total_billed = round($current_parts_total_billed + $part->billing_price, 3);  
    	// Set new total in job model
    	$job->parts_total_billed = $new_parts_total_billed;

    	// Get the current grand total from the job
    	$current_grand_total = $job->job_grand_total;
    	// Calculate the new grand total using new total billed amount
    	$new_grand_total = round($current_grand_total + $new_parts_total_billed, 3);
    	// Set the new grand total in job model
    	$job->job_grand_total = $new_grand_total;

    	// Save the job with updated totals
    	$job = $this->genericSave($job);

    	// Load job relationships so we can return the updated job
    	$job->load('parts');	

    	return $job;	
	}

    public function create(SaveJobPart $request)
    {
    	// Save the part
    	$part = $this->genericSave(new JobPart, $request);
    	
    	// Update the parent job with new totals based on added part
    	$job = $this->calcAndSaveJobTotals($part)

    	
    	return 
    }

    public function remove($id)
    {
    	// Get the part
    	$part = JobPart::with(['job'])->findOrFail($id);

		// Check if parent work order is still open (can only remove a part from an open (not invoiced) work order)
		if($this->ensureWorkOrderIsOpen($part->job->work_order_id)){
			// Check if parent job is marked as complete (cannot remove a part from a job marked complete)
			if(! $part->job->is_complete){

				// Part is allowed to be removed
				return $this->genericRemove($part);
			}
		}    	


    }
}
