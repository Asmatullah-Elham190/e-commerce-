<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\SellerHubService;
use App\Models\SellerAdminRequest;
use App\Models\SellerAdminPromotionParticipate;
use App\Models\SellerAdminMessage;
use App\Models\SellerAdminConversation;

class AppServiceProvider extends ServiceProvider
{
  public function boot()
  {
    Schema::defaultStringLength(191);
    Paginator::useBootstrap();

    View::composer('seller.inc.seller_nav', function ($view) {
      if (Auth::check() && Auth::user()->user_type === 'seller') {
        $hubData = app(SellerHubService::class)->getHubData(Auth::id());
        $view->with('hasAnyUnseen', $hubData['hasAnyUnseen']);
      } else {
        $view->with('hasAnyUnseen', false);
      }
    });

    View::composer('backend.inc.admin_nav', function ($view) {
      if (Auth::check() && Auth::user()->user_type === 'admin') {
        $adminId = Auth::id();

        $hasUnseenRequests = SellerAdminRequest::where('seen', 0)->exists();
        $hasUnseenPromotionParticipates = SellerAdminPromotionParticipate::where('seen', 0)->exists();

        $conversationIds = SellerAdminConversation::where(function ($q) use ($adminId) {
          $q->where('sender_id', $adminId)->orWhere('receiver_id', $adminId);
        })->pluck('id');

        $hasUnseenMessages = SellerAdminMessage::whereIn('seller_admin_conversation_id', $conversationIds)
          ->where('user_id', '!=', $adminId)
          ->where('seen', 0)
          ->exists();

        $hasAnyUnseen = $hasUnseenRequests || $hasUnseenPromotionParticipates || $hasUnseenMessages;

        $view->with([
          'hasAnyUnseen'                   => $hasAnyUnseen,
          'hasUnseenRequests'              => $hasUnseenRequests,
          'hasUnseenPromotionParticipates' => $hasUnseenPromotionParticipates,
          'hasUnseenMessages'              => $hasUnseenMessages,
        ]);
      } else {
        $view->with([
          'hasAnyUnseen'                   => false,
          'hasUnseenRequests'              => false,
          'hasUnseenPromotionParticipates' => false,
          'hasUnseenMessages'              => false,
        ]);
      }
    });
  }

  public function register() {}
}
