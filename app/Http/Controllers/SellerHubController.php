<?php

namespace App\Http\Controllers;

use App\Models\FlashDeal;
use App\Models\SellerAdminConversation;
use App\Models\SellerAdminMessage;
use App\Models\SellerAdminNotice;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerAdminPromotion;
use App\Models\SellerAdminPromotionParticipate;
use App\Models\SellerAdminRequest;

class SellerHubController extends Controller
{
    public function view_single_chat($sellerId)
    {
        $adminId = Auth::id();
        $shop = Shop::where('user_id', $sellerId)->firstOrFail();

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
                ->where('user_id', '!=', $adminId)
                ->where('seen', 0)
                ->update(['seen' => 1]);
                
            $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        }

        return view('backend.chats.single_chat', compact('shop', 'messages', 'sellerId'));
    }

    public function send_chat_message(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $adminId = Auth::id();
        $sellerId = $request->seller_id;

        $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
        })
            ->orWhere(function ($q) use ($adminId, $sellerId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
            })
            ->first();

        if (!$conversation) {
            $conversation = SellerAdminConversation::create([
                'sender_id' => $adminId,
                'receiver_id' => $sellerId,
            ]);
        } else {
            $conversation->touch();
        }

        $message = SellerAdminMessage::create([
            'seller_admin_conversation_id' => $conversation->id,
            'user_id' => $adminId,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->message,
            'time' => $message->created_at->format('M d Y, h:i a'),
        ]);
    }

    public function view_chat_list(Request $request)
    {
        $shops = Shop::all();
        $userId = Auth::id();

        $conversations = SellerAdminConversation::with(['messages'])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $conversations->each(function ($conversation) use ($userId) {
            $shopUserId = $conversation->sender_id == $userId
                ? $conversation->receiver_id
                : $conversation->sender_id;

            $conversation->seller_id = $shopUserId;
            $conversation->shop = Shop::where('user_id', $shopUserId)->first();
            $conversation->lastMessage = $conversation->messages->last();

            $conversation->unseenCount = $conversation->messages
                ->where('user_id', '!=', $userId)
                ->where('seen', 0)
                ->count();
        });

        return view('backend.chats.chat_list', compact('conversations', 'shops'));
    }

    public function view_notices_tab(Request $request)
    {
        $notices = SellerAdminNotice::with(['sellers', 'seenSellers'])
            ->where('status', 1)
            ->orderByRaw("notice_type = 'default' desc")
            ->orderBy('created_at', 'desc')
            ->get();
        return view('backend.chats.notices', compact('notices'));
    }

    public function view_requests_tab(Request $request)
    {
        $requests = SellerAdminRequest::with('seller.shop')
            ->orderBy('created_at', 'desc')
            ->get();

        SellerAdminRequest::where('seen', 0)->update(['seen' => 1]);    

        return view('backend.chats.requests', compact('requests'));
    }

    public function view_promotions_tab(Request $request)
    {
        $promotions = SellerAdminPromotion::with(['flashSale', 'sellers', 'respondedSellers'])
            ->orderBy('created_at', 'desc')
            ->get();

        SellerAdminPromotionParticipate::where('seen', 0)->update(['seen' => 1]);   
         
        return view('backend.chats.promotions', compact('promotions'));
    }

    public function view_preset_notice_tab(Request $request)
    {
        $notices = SellerAdminNotice::with(['sellers', 'seenSellers'])
            ->where('save_as_preset', 1)
            ->orderByRaw("notice_type = 'default' desc")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.chats.preset_notice', compact('notices'));
    }

    public function view_plus_tab(Request $request)
    {
        return view('backend.chats.plus');
    }

    public function view_plus_notice_tab(Request $request)
    {
        $shops = Shop::with('user')->get();
        return view('backend.chats.plus_notice', compact('shops'));
    }

    public function view_plus_message_tab(Request $request)
    {
        $shops = Shop::all();
        return view('backend.chats.plus_message', compact('shops'));
    }

    public function store_plus_message(Request $request)
    {
        $request->validate([
            'audience'    => 'required|in:all,specific',
            'content'     => 'required|string',
            'seller_ids'  => 'required_if:audience,specific|array',
        ]);

        $adminId = Auth::id();

        $sellerIds = $request->audience === 'all'
            ? Shop::pluck('user_id')->toArray()
            : $request->seller_ids;

        foreach ($sellerIds as $sellerId) {
            $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
                $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
            })
                ->orWhere(function ($q) use ($adminId, $sellerId) {
                    $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
                })
                ->first();

            if (!$conversation) {
                $conversation = SellerAdminConversation::create([
                    'sender_id'   => $adminId,
                    'receiver_id' => $sellerId,
                ]);
            } else {
                $conversation->touch();
            }

            SellerAdminMessage::create([
                'seller_admin_conversation_id' => $conversation->id,
                'user_id'                      => $adminId,
                'message'                      => $request->content,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => translate('Message sent successfully'),
        ]);
    }

    public function view_plus_promotion_tab(Request $request)
    {
        $shops = Shop::all();
        $flash_deals = FlashDeal::where('status', 1)->get();
        return view('backend.chats.plus_promotion', compact('shops', 'flash_deals'));
    }

    public function plusPromotion()
    {
        $shops = Shop::with('user')->get();
        $flash_deals = FlashDeal::orderBy('created_at', 'desc')->get();

        return view('backend.chats.plus_promotion', compact('shops', 'flash_deals'));
    }

    public function plusPromotionStore(Request $request)
    {
        $request->validate([
            'message'         => 'nullable|string',
            'audience'        => 'required|in:all,specific',
            'seller_ids'      => 'required_if:audience,specific|array',
            'promo_type'      => 'required|in:todays_deal,featured_products,flash_deals',
            'flash_sale_id'   => 'required_if:promo_type,flash_deals|nullable|exists:flash_deals,id',
        ]);

        $promotion = SellerAdminPromotion::create([
            'todays_deal'       => $request->promo_type === 'todays_deal' ? 1 : 0,
            'featured_products' => $request->promo_type === 'featured_products' ? 1 : 0,
            'flash_sale_id'     => $request->promo_type === 'flash_deals'
                ? $request->flash_sale_id
                : null,
            'message'           => $request->message,
        ]);

        $sellerIds = $request->audience === 'all'
            ? Shop::pluck('user_id')->toArray()
            : $request->seller_ids;

        $promotion->sellers()->attach($sellerIds);

        return response()->json([
            'success' => true,
            'message' => translate('Promotion has been sent successfully'),
        ]);
    }

    public function plusNoticeStore(Request $request)
    {
        $request->validate([
            'audience'         => 'required|in:all,specific',
            'seller_ids'       => 'required_if:audience,specific|array',
            'notice_type'      => 'required|in:permanent,temporary',
            'notice_datetime'  => 'required_if:notice_type,temporary|nullable|string',
            'message'          => 'required|string',
            'bg_color'         => 'required|string',
            'save_as_preset'   => 'nullable|boolean',
        ]);

        $notice = SellerAdminNotice::create([
            'notice_type'     => $request->notice_type,
            'notice_datetime' => $request->notice_type === 'temporary' ? $request->notice_datetime : null,
            'message'         => $request->message,
            'bg_color'        => $request->bg_color,
            'save_as_preset'  => $request->boolean('save_as_preset'),
        ]);

        if($notice->save_as_preset == 0){
            $notice->update([
                'status'  => 1,
            ]);
        }

        $sellerIds = $request->audience === 'all'
            ? Shop::pluck('user_id')->toArray()
            : $request->seller_ids;

        $notice->sellers()->attach($sellerIds);

        return response()->json([
            'success' => true,
            'message' => translate('Notice has been sent successfully'),
        ]);
    }

    public function presetNoticeToggleStatus(Request $request)
    {
        $request->validate([
            'notice_id' => 'required|integer|exists:seller_admin_notices,id',
            'status'    => 'required|boolean',
        ]);

        $notice = SellerAdminNotice::findOrFail($request->notice_id);
        $notice->status = $request->boolean('status') ? 1 : 0;
        $notice->save();

        return response()->json([
            'success' => true,
            'message' => translate('Status updated successfully'),
        ]);
    }

    public function presetNoticeDelete(Request $request)
    {
        $request->validate([
            'notice_id' => 'required|integer|exists:seller_admin_notices,id',
        ]);

        $notice = SellerAdminNotice::findOrFail($request->notice_id);
        $notice->sellers()->detach();
        $notice->delete();

        return response()->json([
            'success' => true,
            'message' => translate('Notice has been deleted successfully'),
        ]);
    }

    public function presetNoticeEdit(Request $request)
    {
        $request->validate([
            'notice_id' => 'required|integer|exists:seller_admin_notices,id',
        ]);

        $notice = SellerAdminNotice::with('sellers')->findOrFail($request->notice_id);

        $totalShops = Shop::count();
        $sellerIds  = $notice->sellers->pluck('id')->toArray();
        $audience   = ($totalShops > 0 && count($sellerIds) >= $totalShops) ? 'all' : 'specific';

        return response()->json([
            'success' => true,
            'notice'  => [
                'id'              => $notice->id,
                'notice_type'     => $notice->notice_type,
                'notice_datetime' => $notice->notice_datetime,
                'message'         => $notice->message,
                'bg_color'        => $notice->bg_color,
                'save_as_preset'  => (bool) $notice->save_as_preset,
                'audience'        => $audience,
                'seller_ids'      => $sellerIds,
            ],
        ]);
    }

    public function plusNoticeUpdate(Request $request)
    {
        $request->validate([
            'notice_id'        => 'required|integer|exists:seller_admin_notices,id',
            'audience'         => 'required|in:all,specific',
            'seller_ids'       => 'required_if:audience,specific|array',
            'notice_type'      => 'required|in:permanent,temporary',
            'notice_datetime'  => 'required_if:notice_type,temporary|nullable|string',
            'message'          => 'required|string',
            'bg_color'         => 'required|string',
            'save_as_preset'   => 'nullable|boolean',
        ]);

        $notice = SellerAdminNotice::findOrFail($request->notice_id);

        $notice->update([
            'notice_type'     => $request->notice_type,
            'notice_datetime' => $request->notice_type === 'temporary' ? $request->notice_datetime : null,
            'message'         => $request->message,
            'bg_color'        => $request->bg_color,
            'save_as_preset'  => $request->boolean('save_as_preset'),
        ]);

        if($notice->save_as_preset == 0){
            $notice->update([
                'status'  => 1,
            ]);
        }

        $sellerIds = $request->audience === 'all'
            ? Shop::pluck('user_id')->toArray()
            : $request->seller_ids;

        $notice->sellers()->sync($sellerIds);

        return response()->json([
            'success' => true,
            'message' => translate('Notice has been updated successfully'),
        ]);
    }

    public function seller_requests_index(Request $request)
    {
        return view('backend.support.seller_requests.index');
    }

    public function seller_requests_filter(Request $request)
    {
        $requests = SellerAdminRequest::with('seller');

        if ($request->filled('user_id')) {
            $requests->where('seller_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $typeFieldMap = [
                'category'          => 'category_name',
                'brand'             => 'brand_name',
                'color'             => 'color_name',
                'attribute'         => 'attribute_name',
                'unit'              => 'unit_name',
                'measurement point' => 'measurement_point_name',
                'measurement'       => 'measurement_point_name',
                'warranty'          => 'warranty_name',
            ];

            $matchedTypeField = null;
            foreach ($typeFieldMap as $typeKeyword => $field) {
                if (stripos($typeKeyword, $search) !== false || stripos($search, $typeKeyword) !== false) {
                    $matchedTypeField = $field;
                    break;
                }
            }

            $requests->where(function ($q) use ($search, $matchedTypeField) {
                $q->where('category_name', 'like', '%' . $search . '%')
                    ->orWhere('brand_name', 'like', '%' . $search . '%')
                    ->orWhere('color_name', 'like', '%' . $search . '%')
                    ->orWhere('attribute_name', 'like', '%' . $search . '%')
                    ->orWhere('unit_name', 'like', '%' . $search . '%')
                    ->orWhere('measurement_point_name', 'like', '%' . $search . '%')
                    ->orWhere('warranty_name', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhereHas('seller', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('seller.shop', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    });

                if ($matchedTypeField) {
                    $q->orWhereNotNull($matchedTypeField)->where($matchedTypeField, '!=', '');
                }
            });
        }

        $requests = $requests->orderBy('created_at', 'desc')->paginate(15);

        $view = view(
            'backend.support.seller_requests.table',
            compact('requests')
        )->render();

        return response()->json(['html' => $view]);
    }
}
