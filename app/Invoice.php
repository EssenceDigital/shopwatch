<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'work_order_id', 'created_by', 'date', 'is_paid', 'payment_method', 'paid_on', 'gst_rate', 'shop_supply_rate', 'total_labour', 'total_parts', 'sub_total', 'gst_total', 'grand_total' 
    ];

    /**
     * Get the work order the invoice is associated to
     */
    public function work_order()
    {
        return $this->belongsTo('App\WorkOrder');
    }    
}
