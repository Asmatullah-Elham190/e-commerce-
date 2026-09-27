<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class PickupAddress extends Model
{
    use PreventDemoModeChanges;
    protected $guarded = [];
}
