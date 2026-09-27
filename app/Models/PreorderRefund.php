<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class PreorderRefund extends Model
{
    use HasFactory,PreventDemoModeChanges;
    
    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
