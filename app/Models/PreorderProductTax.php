<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class PreorderProductTax extends Model
{
    use HasFactory,PreventDemoModeChanges;

    public function preorder_tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
