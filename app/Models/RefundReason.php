<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;
use App;

class RefundReason extends Model
{
    use PreventDemoModeChanges;

    protected $with = ['refund_reason_translations'];
    protected $fillable = ['type', 'reason'];
    
    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $refund_reason_translation = $this->refund_reason_translations->where('lang', $lang)->first();
        return $refund_reason_translation != null ? $refund_reason_translation->$field : $this->$field;
    }

    public function refund_reason_translations()
    {
        return $this->hasMany(RefundReasonTranslation::class);
    }
}
