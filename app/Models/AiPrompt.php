<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class AiPrompt extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;

    protected $fillable = ['type','prompt'];
}
