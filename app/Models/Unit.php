<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Unit extends Model
{
    use PreventDemoModeChanges;
    protected $guarded = [];
}
