<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\ProcessFlowHistory as SkillzProcessFlowHistory;

class ProcessFlowHistory extends SkillzProcessFlowHistory
{
    use HasFactory;
    protected $fillable = [];
}
