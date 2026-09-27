<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class ShippingLabelDownload extends Model
{
    use PreventDemoModeChanges;
}
