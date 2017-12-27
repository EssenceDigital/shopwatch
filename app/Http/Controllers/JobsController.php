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
	private function calculateJobTotals($request, $job = false)
	{
		// Get the tech (user) first
		$tech = User::findOrFail($request->tech);
		// Get the current shop rate
		$shop_rate = BusSetting::findOrFail(1)->shop_rate;
		// Calculate the current labour total 
		$labour_total = round((floatval($request->hours) * floatval($shop_rate)), 3);
		// Calculate the current tech pay total
		$tech_pay_total = round(floatval($request->hours) * floatval($tech->hourly_wage), 3);
		// Determine if the grand total should be calculated, or assinged the labour total (first time creation only)
		if(! $job){
			$grand_total = $labour_total;
		} else {
			$grand_total = (floatval($job->job_grand_total) - floatval($job->job_labour_total) + floatval($labour_total);
		}

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
		} else {
    		// Failed response
	        return response()->json($this->woGuardResponse, 422);			
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

		// Check if parent work order is still open (can only modify a part on an open (not invoiced) work order)
		// Check if parent job is marked as complete (cannot modify a part on a job marked complete)
		if($this->guardWorkOrder($job->work_order_id, $job->is_complete)){
			// Calculate totals before saving
			$request = $this->calculateJobTotals($request, $job);

			return $this->genericSave($job, $request);			
		} else {
    		// Failed response
	        return response()->json($this->woGuardResponse, 422);			
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

		// Check if parent work order is still open (can only modify a part on an open (not invoiced) work order)
		// Check if parent job is marked as complete (cannot modify a part on a job marked complete)
		if($this->guardWorkOrder($job->work_order_id, $job->is_complete)){
			// Job is allowed to be removed
			return $this->genericRemove($job);
		} else {
    		// Failed response
	        return response()->json($this->woGuardResponse, 422);			
		}
	}
}
