<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatorTask extends Model
{
    use HasFactory;

    // if a task has been completed 
    const COMPLETED = 1;
    const PENDING = 0;

    protected $fillable = [
        "processflow_history_id",
        "formbuilder_data_id",
        "entity_id",
        "entity_site_id",
        "user_id",
        "processflow_id",
        "processflow_step_id",
        "task_status",
    ];


    public function processflowHistory()
    {
        return $this->hasOne(ProcessFlowHistory::class, "task_id");
    }

    public function processflow()
    {
        return $this->belongsTo(ProcessFlow::class,  "processflow_id", "id");
    }

    public function processflowStep()
    {
        return $this->belongsTo(ProcessFlowStep::class, "processflow_id", "id");
    }
}
