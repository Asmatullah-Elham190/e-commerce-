<?php

namespace App\Http\Controllers\Seller;

use App\Models\SellerAdminConversation;
use App\Models\SellerAdminMessage;
use App\Models\SellerAdminNotice;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerAdminPromotion;
use App\Models\User;
use App\Models\SellerAdminRequest;

class SellerHubController extends Controller
{
    public function view_chat_tab(Request $request)
    {
        $sellerId = Auth::id();
        $adminId  = $this->getAdminId();
        $admin = User::where('user_type', 'admin')->first();

        $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
        })
            ->orWhere(function ($q) use ($adminId, $sellerId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
            })
            ->first();

        $messages = collect();
        if ($conversation) {
            SellerAdminMessage::where('seller_admin_conversation_id', $conversation->id)
                ->where('user_id', '!=', $sellerId)
                ->where('seen', 0)
                ->update(['seen' => 1]);

            $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        }

        return view('seller.chats.single_chat', compact('messages','admin'));
    }

    public function send_chat_message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $sellerId = Auth::id();
        $adminId  = $this->getAdminId();

        $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
        })
            ->orWhere(function ($q) use ($adminId, $sellerId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
            })
            ->first();

        if (!$conversation) {
            $conversation = SellerAdminConversation::create([
                'sender_id'   => $sellerId,
                'receiver_id' => $adminId,
            ]);
        } else {
            $conversation->touch();
        }

        $message = SellerAdminMessage::create([
            'seller_admin_conversation_id' => $conversation->id,
            'user_id'                      => $sellerId,
            'message'                      => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->message,
            'time'    => $message->created_at->format('M d Y, h:i a'),
        ]);
    }

    public function view_notices_tab(Request $request)
    {
        $sellerId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->first();

        $notices = SellerAdminNotice::with(['sellers', 'seenSellers'])
            ->where('save_as_preset', 0)
            ->orderByRaw("notice_type = 'default' desc")
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_notice_sellers.seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $notices->each(function ($notice) use ($sellerId) {
            $notice->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
        });    

        $presetNotices = collect();

        if ($shop) {
            $presetDefaults = SellerAdminNotice::whereIn('id', [1, 2, 3, 4])
                ->where('status', 1)
                ->get()
                ->keyBy('id');

            if ($shop->verification_status == 0 && $presetDefaults->has(1)) {
                $presetNotices->push($presetDefaults->get(1));
            }

            if (
                addon_is_activated('gst_system')
                && $shop->gst_verification == 0
                && $presetDefaults->has(2)
            ) {
                $presetNotices->push($presetDefaults->get(2));
            }

            if ($shop->package_invalid_at) {
                $expiryDate = \Carbon\Carbon::parse($shop->package_invalid_at);
                $today = \Carbon\Carbon::today();

                $isExpired = $today->gte($expiryDate);
                $withinWeekOfExpiry = !$isExpired && $today->diffInDays($expiryDate) <= 7;

                if ($withinWeekOfExpiry && $presetDefaults->has(3)) {
                    $notice = $presetDefaults->get(3);
                    $notice->expiry_date = $expiryDate->format('M d, Y');
                    $presetNotices->push($notice);
                }

                if ($isExpired && $presetDefaults->has(4)) {
                    $notice = $presetDefaults->get(4);
                    $notice->expiry_date = $expiryDate->format('M d, Y');
                    $presetNotices->push($notice);
                }
            }
        }

        $notices = $presetNotices->concat($notices);

        return view('seller.chats.notices', compact('notices'));
    }

    public function view_requests_tab(Request $request)
    {
        $sellerId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->first();

        $requests = SellerAdminRequest::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seller.chats.requests', compact('requests', 'shop'));
    }

    public function view_promotions_tab(Request $request)
    {
        $sellerId = Auth::id();

        $promotions = SellerAdminPromotion::with(['flashSale', 'sellers', 'respondedSellers'])
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $promotions->each(function ($promotion) use ($sellerId) {
            $promotion->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
        });    

        return view('seller.chats.promotions', compact('promotions'));
    }

    public function view_plus_tab(Request $request)
    {
        return view('seller.chats.plus');
    }

    private function getAdminId()
    {
        return User::where('user_type', 'admin')->value('id');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'items'   => 'required|array|min:1',
            'items.*' => 'in:category,brand,color,attribute,unit,measurement_point,warranty',
            'message' => 'nullable|string',
        ]);

        $fieldMap = [
            'category'          => 'category_name',
            'brand'             => 'brand_name',
            'color'             => 'color_name',
            'attribute'         => 'attribute_name',
            'unit'              => 'unit_name',
            'measurement_point' => 'measurement_point_name',
            'warranty'          => 'warranty_name',
        ];

        $errors = [];
        $data   = [];

        foreach ($request->items as $item) {
            $field = $fieldMap[$item];
            $value = trim((string) $request->input($field));

            if ($value === '') {
                $errors[$field] = translate('This field is required');
            }

            $data[$field] = $value;
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors'  => $errors,
            ], 422);
        }

        SellerAdminRequest::create(array_merge($data, [
            'seller_id' => Auth::id(),
            'name'      => implode(', ', array_map(function ($item) {
                return ucfirst(str_replace('_', ' ', $item));
            }, $request->items)),
            'message'   => $request->message,
        ]));

        return response()->json([
            'success' => true,
            'message' => translate('Request has been sent successfully'),
        ]);
    }
}
