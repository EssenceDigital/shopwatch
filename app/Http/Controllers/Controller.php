<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function genericSave($model, $request, $beforeFill = null, $beforeSave = null, $afterSave = null)
    {
    	// Check if the before fill function should run
    	if(is_callable($beforeFill)){
    		// Run before fill function with request passed as a reference
    		call_user_func_array($beforeFill, array(&$request));
    	}

    	// Populate the model with request data
    	$model->fill($request->all());

    	// Save the model, if it does not save then return a failed response
    	if(! $model->save()){
    		// Failed response
	        return response()->json([
	            'result' => 'error',
	            'message' => 'Problem storing model.'
	        ], 422);      		
    	}

    	return $model;
    }
}
