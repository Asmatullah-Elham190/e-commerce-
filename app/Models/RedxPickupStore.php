<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedxPickupStore extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;
    protected $guarded = [];
}
