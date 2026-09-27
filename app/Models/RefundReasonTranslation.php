<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class RefundReasonTranslation extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = ['reason', 'refund_reason_id'];

    public function refund_reason()
    {
        return $this->belongsTo(RefundReason::class);
    }
}
