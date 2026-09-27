<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerWithdrawRequest;
use App\Models\User;
use Auth;

class SellerWithdrawRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_seller_payout_requests'])->only('index');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $seller_withdraw_request_tabs = ['All Seller Withdraw Request'];

        $seller_withdraw_requests = SellerWithdrawRequest::latest()->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;

            $seller_ids = User::where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('shop', function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%');
                })
                ->pluck('id');

            $seller_withdraw_requests = $seller_withdraw_requests->whereIn('user_id', $seller_ids);
        }

        $seller_withdraw_requests = $seller_withdraw_requests->paginate(15);
        return view('backend.sellers.seller_withdraw_requests.index', compact('seller_withdraw_requests', 'sort_search', 'seller_withdraw_request_tabs'));
    }

    public function filter(Request $request)
    {
        $seller_withdraw_requests = SellerWithdrawRequest::latest()->orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;

            $seller_ids = User::where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('shop', function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%');
                })
                ->pluck('id');

            $seller_withdraw_requests = $seller_withdraw_requests->whereIn('user_id', $seller_ids);
        }

        $seller_withdraw_requests = $seller_withdraw_requests->paginate(15);
        $view = view(
            'backend.sellers.seller_withdraw_requests.table',
            compact('seller_withdraw_requests', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create() {}

    public function store(Request $request)
    {
        $seller_withdraw_request = new SellerWithdrawRequest;
        $seller_withdraw_request->user_id = Auth::user()->shop->id;
        $seller_withdraw_request->amount = $request->amount;
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        if ($seller_withdraw_request->save()) {
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('withdraw_requests.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function payment_modal(Request $request)
    {
        $user = User::findOrFail($request->id);
        $seller_withdraw_request = SellerWithdrawRequest::where('id', $request->seller_withdraw_request_id)->first();
        return view('backend.sellers.seller_withdraw_requests.payment_modal', compact('user', 'seller_withdraw_request'));
    }

    public function message_modal(Request $request)
    {
        $seller_withdraw_request = SellerWithdrawRequest::findOrFail($request->id);
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.partials.withdraw_message_modal', compact('seller_withdraw_request'));
        } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.sellers.seller_withdraw_requests.withdraw_message_modal', compact('seller_withdraw_request'));
        }
    }
}
