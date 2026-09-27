<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\SellerAdminNotice;
use App\Models\SellerAdminPromotion;
use App\Models\SellerAdminRequest;
use App\Models\SellerAdminConversation;
use App\Models\SellerAdminMessage;
use App\Models\User;
use Carbon\Carbon;

class SellerHubService
{
    public function getHubData($sellerId)
    {
        $shop = Shop::where('user_id', $sellerId)->first();
        $admin = User::where('user_type', 'admin')->first();

        $conversations = SellerAdminConversation::with(['messages'])
            ->where(function ($query) use ($sellerId) {
                $query->where('sender_id', $sellerId)
                    ->orWhere('receiver_id', $sellerId);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $conversations->each(function ($conversation) use ($sellerId) {
            $shopUserId = $conversation->sender_id == $sellerId
                ? $conversation->receiver_id
                : $conversation->sender_id;

            $conversation->seller_id = $shopUserId;
            $conversation->shop = Shop::where('user_id', $shopUserId)->first();
            $conversation->lastMessage = $conversation->messages->last();
            $conversation->lastSellerMessage = $conversation->messages
                ->where('user_id', '!=', $sellerId)
                ->last();
        });

        $hasUnseenMessages = SellerAdminMessage::whereIn(
            'seller_admin_conversation_id',
            $conversations->pluck('id')
        )
            ->where('user_id', '!=', $sellerId)
            ->where('seen', 0)
            ->exists();

        $promotions = SellerAdminPromotion::with(['flashSale', 'respondedSellers'])
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $hasUnseenPromotions = SellerAdminPromotion::whereHas('sellers', function ($q) use ($sellerId) {
            $q->where('seller_admin_promotion_sellers.seller_id', $sellerId)
            ->where('seller_admin_promotion_sellers.seen', 0);
        })->exists();

        $requests = SellerAdminRequest::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->get();

        $notices = SellerAdminNotice::with(['sellers', 'seenSellers'])
            ->where('save_as_preset', 0)
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_notice_sellers.seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $hasUnseenNotices = SellerAdminNotice::whereHas('sellers', function ($q) use ($sellerId) {
            $q->where('seller_admin_notice_sellers.seller_id', $sellerId)
            ->where('seller_admin_notice_sellers.seen', 0);
        })->exists();    

        $presetNotices = $this->getPresetNotices($shop);
        $allNotices = $presetNotices->concat($notices);

        $activities = $this->buildActivities($promotions, $conversations, $requests, $allNotices);

        $hasAnyUnseen = $hasUnseenMessages || $hasUnseenNotices || $hasUnseenPromotions;

        return compact('conversations', 'promotions', 'requests', 'allNotices', 'activities', 'hasUnseenMessages', 'hasUnseenNotices', 'hasUnseenPromotions', 'hasAnyUnseen', 'shop', 'admin');
    }

    protected function getPresetNotices($shop)
    {
        $presetNotices = collect();

        if (!$shop) {
            return $presetNotices;
        }

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
            $expiryDate = Carbon::parse($shop->package_invalid_at);
            $today = Carbon::today();

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

        return $presetNotices;
    }

    protected function buildActivities($promotions, $conversations, $requests, $allNotices)
    {
        $activities = collect();

        foreach ($promotions as $promotion) {
            $activities->push(['type' => 'promotion', 'data' => $promotion, 'sort_time' => $promotion->created_at]);
        }
        foreach ($conversations as $conversation) {
            if ($conversation->lastSellerMessage) {
                $activities->push(['type' => 'message', 'data' => $conversation, 'sort_time' => $conversation->lastSellerMessage->created_at]);
            }
        }
        foreach ($requests as $req) {
            $activities->push(['type' => 'request', 'data' => $req, 'sort_time' => $req->created_at]);
        }
        foreach ($allNotices as $notice) {
            $activities->push(['type' => 'notice', 'data' => $notice, 'sort_time' => $notice->created_at]);
        }

        return $activities->sortByDesc('sort_time')->values();
    }
}