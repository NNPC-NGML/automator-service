<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Skillz\Nnpcreusable\Models\Designation as ModelsDesignation;

class Designation extends ModelsDesignation
{
    use HasFactory;
    protected $guarded = [];
}
