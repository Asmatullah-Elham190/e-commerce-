<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class RefundRequest extends Model
{
    use PreventDemoModeChanges;
    protected $casts = [
        'refund_approval_datatime' => 'datetime',
        'dispute_refund_created_at' => 'datetime',
        'dispute_refund_approval_datatime' => 'datetime',
        'seller_refund_approval_datatime' => 'datetime',
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentInformation()
    {
        return $this->belongsTo(PaymentInformation::class, 'payment_information_id');
    }

    public function refund_reason()
    {
        return $this->belongsTo(RefundReason::class, 'reason');
    }

    public function getReasonTextAttribute()
    {
        return is_numeric($this->reason) ? optional($this->refund_reason)->reason : $this->reason;
    }

    public function dispute_reason_relation()
    {
        return $this->belongsTo(RefundReason::class, 'dispute_reason');
    }

    public function getDisputeReasonTextAttribute()
    {
        return is_numeric($this->dispute_reason) ? optional($this->dispute_reason_relation)->reason : $this->dispute_reason;
    }

    public function seller_reject_reason_relation()
    {
        return $this->belongsTo(RefundReason::class, 'reject_reason');
    }

    public function getSellerRejectReasonDisplayAttribute()
    {
        return is_numeric($this->reject_reason) ? optional($this->seller_reject_reason_relation)->reason : $this->reject_reason;
    }

    public function dispute_reject_reason_relation()
    {
        return $this->belongsTo(RefundReason::class, 'dispute_reject_reason');
    }

    public function getDisputeRejectReasonDisplayAttribute()
    {
        return is_numeric($this->dispute_reject_reason) ? optional($this->dispute_reject_reason_relation)->reason : $this->dispute_reject_reason;
    }

    public function admin_reject_reason_relation()
    {
        return $this->belongsTo(RefundReason::class, 'admin_reject_reason');
    }

    public function getAdminRejectReasonDisplayAttribute()
    {
        return is_numeric($this->admin_reject_reason) ? optional($this->admin_reject_reason_relation)->reason : $this->admin_reject_reason;
    }

    public function admin_dispute_reject_reason_relation()
    {
        return $this->belongsTo(RefundReason::class, 'admin_dispute_reject_reason');
    }

    public function getAdminDisputeRejectReasonDisplayAttribute()
    {
        return is_numeric($this->admin_dispute_reject_reason) ? optional($this->admin_dispute_reject_reason_relation)->reason : $this->admin_dispute_reject_reason;
    }
}
