<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\ProcessFlowStep as SkillzProcessFlowStep;

class ProcessFlowStep extends SkillzProcessFlowStep
{
    use HasFactory;
    protected $fillable = [];

    public function route()
    {
        return $this->belongsTo(Routes::class,  "step_route", "id");
    }

    public function nextStep()
    {
        return $this->belongsTo(ProcessFlowStep::class, 'next_step_id');
    }

    /**
     * Define a one-to-one relationship with the child folder.
     */
    public function previousStep()
    {
        return $this->hasOne(ProcessFlowStep::class, 'next_step_id');
    }
}
