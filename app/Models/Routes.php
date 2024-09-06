<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\Routes as SkillzRoute;

class Routes extends SkillzRoute
{
    use HasFactory;
    protected $fillable = [];
}
