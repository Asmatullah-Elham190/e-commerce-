<?php

namespace App\Http\Controllers;

use App\Models\PaymentInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentInformationController extends Controller
{
    public function create()
    {
        return view('frontend.partials.payment_information.payment_information_modal');
    }

    public function ajax_create()
    {
        return view('frontend.partials.payment_information.ajax_payment_information_modal');
    }

    public function store(Request $request)
    {
        $payment_information = new PaymentInformation;

        if ($request->bank_name === 'other_bank') {
            $payment_information->bank_name = $request->other_bank_name;
        } else {
            $payment_information->bank_name       = $request->bank_name;
        }

        if ($request->payment_name === 'other_method') {
            $payment_information->payment_name = $request->other_payment_method;
        } else {
            $payment_information->payment_name       = $request->payment_name;
        }

        $payment_information->user_id   = Auth::user()->id;
        $payment_information->payment_type       = $request->payment_type;
        $payment_information->payment_instruction       = $request->payment_instructions;
        $payment_information->account_name       = $request->account_name;
        $payment_information->account_number       = $request->account_number;
        $payment_information->routing_number       = $request->routing_number;
        $payment_information->save();

        flash(translate('Payment info Stored successfully'))->success();
        return back();
    }

    public function ajax_store(Request $request)
    {
        $payment_information = new PaymentInformation;

        if ($request->bank_name === 'other_bank') {
            $payment_information->bank_name = $request->other_bank_name;
        } else {
            $payment_information->bank_name       = $request->bank_name;
        }

        if ($request->payment_name === 'other_method') {
            $payment_information->payment_name = $request->other_payment_method;
        } else {
            $payment_information->payment_name       = $request->payment_name;
        }

        $payment_information->user_id   = Auth::user()->id;
        $payment_information->payment_type       = $request->payment_type;
        $payment_information->payment_instruction       = $request->payment_instructions;
        $payment_information->account_name       = $request->account_name;
        $payment_information->account_number       = $request->account_number;
        $payment_information->routing_number       = $request->routing_number;
        $payment_information->save();

        flash(translate('Payment info Stored successfully'))->success();
        return back();
    }

    public function edit(Request $request)
    {
        $payment_information = PaymentInformation::findOrFail($request->payment_information_id);
        return view('frontend.partials.payment_information.payment_information_edit_modal', [
            'payment_information' => $payment_information,
        ]);
    }

    public function ajax_edit(Request $request)
    {
        $payment_information = PaymentInformation::findOrFail($request->payment_information_id);
        return view('frontend.partials.payment_information.ajax_payment_information_edit_modal', [
            'payment_information' => $payment_information,
        ]);
    }

    public function ajax_list()
    {
        $payment_information_id = Auth::user()->payment_informations->first()->id ?? null;

        return view('frontend.partials.payment_information.payment_info', [
            'payment_information_id' => $payment_information_id
        ]);
    }

    public function update(Request $request)
    {
        $payment_information = PaymentInformation::findOrFail($request->payment_information_id);

        $payment_information->payment_type = $request->payment_type;

        if ($request->payment_type === 'bank_transfer') {

            if ($request->bank_name === 'other_bank') {
                $payment_information->bank_name = $request->other_bank_name;
            } else {
                $payment_information->bank_name = $request->bank_name;
            }

            $payment_information->account_name   = $request->account_name;
            $payment_information->account_number = $request->account_number;
            $payment_information->routing_number = $request->routing_number;

            $payment_information->payment_name        = null;
            $payment_information->payment_instruction = null;
        }

        elseif ($request->payment_type === 'others') {

            if ($request->payment_name === 'other_method') {
                $payment_information->payment_name = $request->other_payment_method;
            } else {
                $payment_information->payment_name = $request->payment_name;
            }

            $payment_information->payment_instruction = $request->payment_instructions;

            $payment_information->bank_name      = null;
            $payment_information->account_name   = null;
            $payment_information->account_number = null;
            $payment_information->routing_number = null;
        }

        $payment_information->save();

        flash(translate('Payment information updated successfully'))->success();
        return back();
    }

    public function ajax_update(Request $request)
    {
        $payment_information = PaymentInformation::findOrFail($request->payment_information_id);

        $payment_information->payment_type = $request->payment_type;

        if ($request->payment_type === 'bank_transfer') {

            if ($request->bank_name === 'other_bank') {
                $payment_information->bank_name = $request->other_bank_name;
            } else {
                $payment_information->bank_name = $request->bank_name;
            }

            $payment_information->account_name   = $request->account_name;
            $payment_information->account_number = $request->account_number;
            $payment_information->routing_number = $request->routing_number;

            $payment_information->payment_name        = null;
            $payment_information->payment_instruction = null;
        }

        elseif ($request->payment_type === 'others') {

            if ($request->payment_name === 'other_method') {
                $payment_information->payment_name = $request->other_payment_method;
            } else {
                $payment_information->payment_name = $request->payment_name;
            }

            $payment_information->payment_instruction = $request->payment_instructions;

            $payment_information->bank_name      = null;
            $payment_information->account_name   = null;
            $payment_information->account_number = null;
            $payment_information->routing_number = null;
        }

        $payment_information->save();

        flash(translate('Payment information updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        $payment_information = PaymentInformation::findOrFail($id);
        if (!$payment_information->set_default) {
            $payment_information->delete();
            return back();
        }
        flash(translate('Default Payment information cannot be deleted'))->warning();
        return back();
    }

    public function set_default($id)
    {
        foreach (Auth::user()->payment_informations as $key => $payment_information) {
            $payment_information->set_default = 0;
            $payment_information->save();
        }
        $payment_information = PaymentInformation::findOrFail($id);
        $payment_information->set_default = 1;
        $payment_information->save();

        return back();
    }
}
