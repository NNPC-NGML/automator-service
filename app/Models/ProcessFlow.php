<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\ProcessFlow as SkillzProcessFlow;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessFlow extends SkillzProcessFlow
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'start_step_id',
        'frequency',
        'status',
        'frequency_for',
        'day',
        'week',
        "start_user_designation",
        "start_user_department",
        "start_user_unit",
        "last_run",
    ];
}
