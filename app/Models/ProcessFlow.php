<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\ProcessFlow as SkillzProcessFlow;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessFlow extends SkillzProcessFlow
{
    use HasFactory;
    protected $fillable = [];
}
