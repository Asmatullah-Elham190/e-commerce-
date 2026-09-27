<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class TodaysDeal extends Model
{
    use PreventDemoModeChanges;
}
