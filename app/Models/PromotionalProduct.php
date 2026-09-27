<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class PromotionalProduct extends Model
{
    use PreventDemoModeChanges;
}
