<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ShippingSystem extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;
    protected $guarded = [];
}
