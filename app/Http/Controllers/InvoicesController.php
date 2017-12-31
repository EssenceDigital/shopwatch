<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\SaveInvoice;
use App\Invoice;
use App\WorkOrder;
use App\Job;
use App\JobPart;
use App\BusSetting;

class InvoicesController extends Controller
{
    public function get($id)
    {
    	return Invoice::findOrFail($id);
    }

    public function filter($from_date = false, $to_date = false, $is_paid = false, $customer_id = false)
    {
    	// Default state for date (single date)
    	$date = false;
    	// If only one of from_date or to_date is set then the filter wants a specific single date
    	if($from_date && !$to_date){
    		// Cache date
    		$date = $from_date;
    	} elseif($to_date && !$from_date){
    		// Cache date
    		$date = $to_date;
    	}

		// Possible where fields for the filter
		$whereFields = [
			['filter' => $date, 'field' => 'date', 'value' => $date, 'conditional' => '='],
			['filter' => $is_paid, 'field' => 'is_paid', 'value' => $is_paid, 'conditional' => '='],
			['filter' => $customer_id, 'field' => 'customer_id', 'value' => $customer_id, 'conditional' => '=']
		];

		// Possible where between fields for the filter
		$whereBetweeeFields = [
			'first' => ['field' => 'date', 'value' => $from_date],
			'second' => ['field' => 'date', 'value' => $to_date]
		];

		return $this->genericFilter(Invoice::orderBy('created_at', 'asc'), $whereFields, $whereBetweenFields);    	
    }

    public function create(SaveInvoice $request)
    {
    	// Get the work order first because we need the customer ID
    	$wo = WorkOrder::with(['vehicle', 'customer', 'jobs', 'jobs.parts'])->findOrFail($request->work_order_id);
    	// Get the current business settings
    	$bus_settings = BusSetting::find(1);
    	// Start the invoice
    	$invoice = new Invoice;
    	// Assign basic invoice values
    	$invoice->created_by = 'Matt';//Auth::name();
    	$invoice->work_order_id = $wo->id;
    	$invoice->customer_id = $wo->customer_id;
    	$invoice->gst_rate = $bus_settings->gst_rate;
    	$invoice->shop_supply_rate = $bus_settings->shop_supply_rate;

    	// Start totals
    	$total_labour = 0;
    	$total_labour_cost = 0;
    	$total_parts = 0;
    	$total_parts_cost = 0;

    	// Calculate labour / parts totals
    	forEach($wo->jobs as $job){
    		$total_labour += floatval($job->job_labour_total);
    		$total_labour_cost += floatval($job->tech_pay_total);
    		$total_parts += floatval($job->parts_total_billed);
    		$total_parts_cost += floatval($job->parts_total_cost);
    	}

    	// Calculate final totals
    	$sub_total = floatval($total_labour) + floatval($total_parts);
    	$gst_total = floatval($sub_total) * floatval($invoice->gst_rate);
    	$grand_total = floatval($sub_total) + floatval($gst_total);

    	// Cache totals in invoice
    	$invoice->total_labour = $total_labour;
    	$invoice->total_labour_cost = $total_labour_cost;
    	$invoice->total_parts = $total_parts;
    	$invoice->total_parts_cost = $total_parts_cost;
    	$invoice->sub_total = $sub_total;
    	$invoice->gst_total = $gst_total;
    	$invoice->grand_total = $grand_total;

    	return $this->genericSave($invoice);
    }
}
