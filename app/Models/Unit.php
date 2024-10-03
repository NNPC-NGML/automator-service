<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\Unit as ModelsUnit;

class Unit extends ModelsUnit
{
    use HasFactory;
    protected $guarded = [];
}
