<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'job_id', 'title', 'total_cost', 'billing_price'
    ];

    /**
     * Get the job the part belongs to
     */
    public function job()
    {
        return $this->belongsTo('App\Job');
    }     
}
