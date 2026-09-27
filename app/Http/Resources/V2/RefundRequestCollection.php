<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RefundRequestCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $refund_label = '';
                if($data->refund_status == 1) {
                    $refund_label = 'Approved';
                } elseif($data->refund_status == 2) {
                    $refund_label = 'Rejected';
                }else {
                    $refund_label = 'PENDING';
                }

                $dispute_refund_label = '';
                if($data->dispute_refund_status == 2) {
                    $dispute_refund_label = 'Approved';
                } elseif($data->dispute_refund_status == 3) {
                    $dispute_refund_label = 'Rejected';
                } elseif($data->dispute_refund_status == 2) {
                    $dispute_refund_label = 'Pending';
                }
                
                $disputeEligible =
                    $data->orderDetail?->dispute_refund_days > 0 &&
                    $data->dispute_refund_status == 0 &&
                    $data->refund_status == 2 &&
                    $data->dispute_admin_approval == 0;

                return [
                    'id' => (int)$data->id,
                    'user_id' => (int)$data->user_id,
                    'order_code' =>  $data->order == null ? translate("Order not found") : $data->order->code,
                    'product_name' => $data->orderDetail != null && $data->orderDetail->product != null ? $data->orderDetail->product->getTranslation('name', 'en') : "",
                    'product_price' => $data->orderDetail != null ? single_price($data->orderDetail->price) : "",
                    'refund_status' => (int) $data->refund_status,
                    'dispute_refund_status' => (int) $data->dispute_refund_status,
                    'refund_label' => $refund_label,
                    'dispute_refund_label' => $dispute_refund_label,
                    'seller_approval' => $data->seller_approval,
                    'reject_reason' => $data->reject_reason,
                    'reason' => $data->reason,
                    'date' => date('d-m-Y', strtotime($data->created_at)),
                    'thumbnail_image' => $data->orderDetail->product->thumbnail_img ? uploaded_asset($data->orderDetail->product->thumbnail_img) : null,
                    'Is_dispute_available' => $disputeEligible
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
