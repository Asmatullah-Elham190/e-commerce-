<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class PaymentInformation extends Model
{
    use PreventDemoModeChanges;
    protected $guarded = [];
    protected $table = "payment_informations";

    public function payout_method()
    {
        return $this->belongsTo(OfflinePayoutMethod::class, 'bank_name');
    }

    public function other_payout_method()
    {
        return $this->belongsTo(OfflinePayoutMethod::class, 'payment_name');
    }

    public function getBankNameTextAttribute()
    {
        return is_numeric($this->bank_name)
            ? optional($this->payout_method)->name
            : $this->bank_name;
    }

    public function getPaymentNameTextAttribute()
    {
        return is_numeric($this->payment_name)
            ? optional($this->other_payout_method)->name
            : $this->payment_name;
    }
}
