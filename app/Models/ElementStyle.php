<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ElementStyle extends Model
{
    use PreventDemoModeChanges;
    protected $fillable = [
        'value'
    ];
    public function elementType()
    {
        return $this->belongsTo(ElementType::class);
    }
}
