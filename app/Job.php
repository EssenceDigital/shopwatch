<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'work_order_id', 'technician', 'title', 'description', 'hours', 'hourly_rate', 'is_complete', 'job_labour_total', 'job_grand_total'
    ];

    /**
     * Get the parts assigned to this job
     */
    public function parts()
    {
        return $this->hasMany('App\JobPart');
    }     
}
