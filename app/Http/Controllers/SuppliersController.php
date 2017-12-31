<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SaveSupplier;
Use App\Supplier;

class SuppliersController extends Controller
{
	public function all()
	{
		return Supplier::all();
	}

    public function create(SaveSupplier $request)
    {
    	return $this->genericSave(new Supplier, $request);
    }

    public function update(SaveSupplier $request)
    {
    	return $this->genericSave(Supplier::findOrFail($request->id), $request);
    }
}
