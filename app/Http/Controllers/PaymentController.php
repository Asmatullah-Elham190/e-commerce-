<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:seller_payment_history'])->only('payment_histories');
    }

    public function payment_histories(Request $request)
    {
        $sort_search = null;
        $payment_tabs = ['All Seller Payments'];

        $payments = Payment::orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;

            $seller_ids = User::where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('shop', function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%');
                })
                ->pluck('id');

            $payments = $payments->whereIn('seller_id', $seller_ids);
        }

        $payments = $payments->paginate(15);
        return view('backend.sellers.payment_histories.index', compact('payments', 'payment_tabs', 'sort_search'));
    }

    public function filter(Request $request)
    {
        $payments = Payment::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;

            $seller_ids = User::where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('shop', function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%');
                })
                ->pluck('id');

            $payments = $payments->whereIn('seller_id', $seller_ids);
        }

        $payments = $payments->paginate(15);
        $view = view(
            'backend.sellers.payment_histories.table',
            compact('payments', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Request $request, $id)
    {
        $sort_search = null;
        $payment_tabs = ['All Payment History'];

        $user = User::find(decrypt($id));

        $payments = Payment::where('seller_id', $user->id)->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $payments = $payments->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ['%' . $sort_search . '%']);
        }

        if ($payments->count() > 0) {
            $payments = $payments->paginate(15);
            return view('backend.sellers.payment', compact('payments', 'user', 'payment_tabs'));
        }

        flash(translate('No payment history available for this seller'))->warning();
        return back();
    }

    public function history_filter(Request $request, $id)
    {
        $sort_search = null;

        $user = User::find(decrypt($id));

        $payments = Payment::where('seller_id', $user->id)->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $payments = $payments->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ['%' . $sort_search . '%']);
        }

        $payments = $payments->paginate(15);
        $view = view(
            'backend.sellers.payment_table',
            compact('payments', 'sort_search', 'user')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
