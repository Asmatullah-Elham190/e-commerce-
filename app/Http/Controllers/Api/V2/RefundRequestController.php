<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\ClubPoint;
use App\Http\Resources\V2\RefundRequestCollection;
use App\Models\OrderDetail;
use App\Models\RefundReason;
use App\Models\RefundRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Utility\EmailUtility;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{

    public function get_list()
    {
        $refunds = RefundRequest::where('user_id', auth()->user()->id)->latest()->paginate(10);

        return new RefundRequestCollection($refunds);
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'id'     => 'required',
                'reason' => 'required|max:120',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp',
            ]);

            $existingRefund = RefundRequest::where('order_detail_id', $request->id)->first();
            if ($existingRefund) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund request already submitted for this product'
                ], 409);
            }

            $user         = auth()->user();
            $order_detail = OrderDetail::where('id', $request->id)->first();

            if (!$order_detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order detail not found'
                ], 404);
            }

            $refund                   = new RefundRequest;
            $refund->user_id          = $user->id;
            $refund->order_id         = $order_detail->order_id;
            $refund->order_detail_id  = $order_detail->id;
            $refund->seller_id        = $order_detail->seller_id;
            $refund->seller_approval  = 0;
            $refund->reason           = $request->reason;
            $refund->refund_code      = date('Ymd-His') . rand(10, 99);
            $refund->admin_approval   = 0;
            $refund->admin_seen       = 0;
            $refund->refund_status    = 0;
            $refund->preferred_payment_channel = 'wallet';

            if (is_numeric($order_detail->gst_amount)) {
                $refund->refund_amount = round($order_detail->price + get_gst_by_price_and_rate($order_detail->price, $order_detail->gst_rate), 2);
            } else {
                $refund->refund_amount = $order_detail->price + $order_detail->tax;
            }

            $image_ids = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $img_id = custom_upload_file($image);
                    if ($img_id) {
                        $image_ids[] = $img_id;
                    }
                }
            }
            $refund->images = implode(',', $image_ids);

            if (addon_is_activated('offline_payment') && addon_is_activated(identifier: 'refund_request')) {
                $refund->preferred_payment_channel = $request->preferred_payment_channel;
                if ($request->preferred_payment_channel == 'offline') {
                    if ($request->payment_information_id == 0 || $request->payment_information_id == null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Please select the payment information'
                        ], 422);
                    } else {
                        $refund->payment_information_id = $request->payment_information_id;
                    }
                }
            }

            if ($refund->save()) {
                $admin            = get_admin();
                $emailIdentifiers = ['refund_request_email_to_admin'];

                if ($order_detail->order->user->email != null) {
                    array_push($emailIdentifiers, 'refund_request_email_to_customer');
                }
                if ($order_detail->order->seller_id != $admin->id) {
                    array_push($emailIdentifiers, 'refund_request_email_to_seller');
                }

                EmailUtility::refundEmail($emailIdentifiers, $refund);

                return response()->json([
                    'success' => true,
                    'message' => 'Refund request has been sent successfully'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function reasonList()
    {
        try {
            $refund_reasons = RefundReason::without('refund_reason_translations')->where('type', 'customer_refund_reason')->where('status', 1)->select('id', 'reason')->get();

            return response()->json([
                'success' => true,
                'message' => 'Refund reasons fetched successfully',
                'data'    => $refund_reasons
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function details($id)
    {
        $refund = RefundRequest::with(['order', 'seller', 'user', 'orderDetail.product.thumbnail'])->where('id', $id)->first();
    
        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Refund not found',
            ], 404);
        }
    
        $product = $refund->orderDetail?->product;
    
        $data = $refund->toArray();
        
        $admin = User::where('user_type', 'admin')->first();

        $data['admin'] = [
            'name'   => $admin?->name,
            'avatar' => $admin?->avatar_original ? uploaded_asset($admin->avatar_original) : null,
        ];
    
        $parseImages = function ($value) {
            if (!$value) return null;
            $ids = explode(',', $value);
            return array_map(fn($id) => uploaded_asset(trim($id)), $ids);
        };
    
        $data['photo']          = $refund->photo ? uploaded_asset($refund->photo) : null;
        $data['reason']         = $refund->reason_text;
        $data['dispute_reason'] = $refund->dispute_reason_text;
        $data['admin_dispute_reject_reason'] = $refund->admin_dispute_reject_reason_display;
        $data['reject_reason'] = $refund->seller_reject_reason_display;
        $data['admin_reject_reason'] = $refund->admin_reject_reason_display;
        $data['dispute_photo']  = $refund->dispute_photo ? uploaded_asset($refund->dispute_photo) : null;
        $data['images']         = $parseImages($refund->images);
        $data['dispute_images'] = $parseImages($refund->dispute_images);
    
        $data['seller'] = [
            'id'     => $refund->seller?->id,
            'name'   => $refund->seller?->name,
            'avatar' => $refund->seller?->avatar_original ? uploaded_asset($refund->seller->avatar_original) : null,
        ];
    
        $data['user'] = [
            'id'     => $refund->user?->id,
            'name'   => $refund->user?->name,
            'avatar' => $refund->user?->avatar_original ? uploaded_asset($refund->user->avatar_original) : null,
        ];
    
        $disputeEligible =
            $refund->orderDetail?->dispute_refund_days > 0 &&
            $refund->dispute_refund_status == 0 &&
            $refund->refund_status == 2 &&
            $refund->dispute_admin_approval == 0;
    
        $data['order'] = [
            'delivered_date'   => $refund->order?->delivered_date,
            'dispute_eligible' => $disputeEligible,
        ];
    
        $data['order_detail'] = [
            'refund_days'         => $refund->orderDetail?->refund_days,
            'dispute_refund_days' => $refund->orderDetail?->dispute_refund_days,
            'product' => [
                'id'            => $product?->id,
                'name'          => $product?->name,
                'price'         => $product?->unit_price,
                'thumbnail_url' => $product?->thumbnail_img
                                    ? uploaded_asset($product->thumbnail_img)
                                    : null,
            ],
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'Refund details fetched successfully',
            'data'    => $data,
        ], 200);
    }

    public function dispute_send(Request $request, $id)
    {
        // validation
        $validator = \Validator::make($request->all(), [
            'reason' => 'required|max:120',
            'dispute_images.*' => 'image|mimes:jpg,jpeg,png,webp',
        ], [
            'reason.required' => 'Please select or write a refund reason',
            'dispute_images.*.image' => 'Each file must be an image',
            'dispute_images.*.mimes' => 'Only JPG, JPEG, PNG, WEBP images are allowed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // refund find
        $refund = RefundRequest::with('orderDetail.product', 'orderDetail.order')
                    ->where('id', $id)
                    ->first();

        if (!$refund) {
            return response()->json([
                'result' => false,
                'message' => 'Refund request not found'
            ], 404);
        }

        // already submitted check
        if ($refund->dispute_refund_status == 1) {
            return response()->json([
                'result' => false,
                'message' => 'Dispute refund request already submitted for this product'
            ], 400);
        }

        $order_detail = $refund->orderDetail;

        $refund->dispute_reason = $request->reason;

        // image upload
        $image_ids = [];
        if ($request->hasFile('dispute_images')) {
            foreach ($request->file('dispute_images') as $image) {
                $upload_id = custom_upload_file($image);
                if ($upload_id) {
                    $image_ids[] = $upload_id;
                }
            }
        }

        $refund->dispute_images = implode(',', $image_ids);
        $refund->dispute_refund_status = 1;
        $refund->dispute_refund_created_at = now();

        if ($refund->save()) {

            // email sending
            $admin = get_admin();
            $emailIdentifiers = ['dispute_refund_request_email_to_admin'];

            if ($order_detail->order->user->email != null) {
                $emailIdentifiers[] = 'dispute_refund_request_email_to_customer';
            }

            if ($order_detail->order->seller_id != $admin->id) {
                $emailIdentifiers[] = 'dispute_refund_request_email_to_seller';
            }

            EmailUtility::disputeRefundEmail($emailIdentifiers, $refund);

            return response()->json([
                'result' => true,
                'message' => 'Dispute Refund Request has been sent successfully'
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}
