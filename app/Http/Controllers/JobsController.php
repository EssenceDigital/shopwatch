<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use App\Http\Requests\SaveJob;
Use App\Http\Requests\UpdateJob;
Use App\WorkOrder;
Use App\Job;
Use App\User;
Use App\BusSetting;

class JobsController extends Controller
{

	/** 
	 * Sets the financial totals that will be saved in a job. Merges new values with the supplied request.
	 *
	 * @param $request - The job request
	 * @return The merged request
	*/
	private function calculateJobTotals($request)
	{
		// Get the tech (user) first
		$tech = User::findOrFail($request->tech);
		// Get the current shop rate
		$shop_rate = BusSetting::findOrFail(1)->shop_rate;
		// Calculate the current labour total 
		$labour_total = round(($request->hours * $shop_rate), 3);
		// Calculate the current tech pay total
		$tech_pay_total = round(($request->hours * $tech->hourly_wage), 3);

		// Merge the calculated data with the request
		$request->merge([
			'tech' => $tech->name, 
			'tech_hourly_rate' => $tech->hourly_wage,
			'tech_pay_total' => $tech_pay_total,
			'shop_rate' => $shop_rate,
			'job_labour_total' => $labour_total,
			// Will get updated later if there is parts on this job (likely)
			'job_grand_total' => $labour_total
		]);

		return $request;	
	}

	/** 
	 * Returns and error if the work order is closed (invoiced) and true if the work order is open (not invoiced)
	 *
	 * @param $work_order_id - The ID of the parent work order
	 * @return Boolean or json error
	*/
	private function ensureWorkOrderIsOpen($work_order_id)
	{
		// First get the owning work order so we can make sure its still open and not invoiced
		$wo = WorkOrder::findOrFail($work_order_id);

		// If work order is closed (invoiced) then stop here
		if($wo->is_invoiced){
    		// Failed response
	        return response()->json([
	            'result' => 'error',
	            'message' => 'Cannot create, update, or remove a job on a closed work order (invoiced).'
	        ], 422);			
		}

		return true;		
	}

	/** 
	 * Get a job based on ID.
	 *
	 * @param $id - The ID of the job
	 * @return Json App\Job - The requested job
	*/
	public function get($id)
	{
		return Job::with(['parts'])->findOrFail($id);
	}

	/** 
	 * Create a new job in the db associated with a work order. 
	 * Only creates the job if the parent work order is open.
	 *
	 * @param $request - SaveJob custom request 
	 * @return Json App\Job - The created job
	*/
	public function create(SaveJob $request)
	{
		// Check if parent work order is still open
		if($this->ensureWorkOrderIsOpen($request->work_order_id)){
			// Calculate totals before saving
			$request = $this->calculateJobTotals($request);

			return $this->genericSave(new Job, $request);			
		}
	}

	/** 
	 * Update an existing job in the db associated with a work order.
	 * Only updates the job if the parent work order is open.	 
	 *
	 * @param $request - UpdateJob custom request 
	 * @return Json App\Job - The updated job
	*/
	public function update(UpdateJob $request)
	{
		// Get the job
		$job = Job::findOrFail($request->id);

		// Check if parent work order is still open
		if($this->ensureWorkOrderIsOpen($job->work_order_id)){
			// Calculate totals before saving
			$request = $this->calculateJobTotals($request);

			return $this->genericSave($job, $request);			
		}
	}

	/** 
	 * Removes a job from the db. 
	 * Only removes the job if the parent work order is open and the job has not been marked complete.
	 *
	 * @param $id - The id of the job to remove 
	 * @return Int - The id of the removed job
	*/
	public function remove($id)
	{
		// Find the job
		$job = Job::findOrFail($id);

		// Check if parent work order is still open (can only remove a job from an open (not invoiced) work order)
		if($this->ensureWorkOrderIsOpen($job->work_order_id)){
			// Check if job is marked as complete (cannot remove a job marked complete)
			if(! $job->is_complete){

				// Job is allowed to be removed
				return $this->genericRemove($job);
			}
		}
	}
}
