<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\PaymentInformation;
use Illuminate\Http\Request;  

class PaymentInformationController extends Controller
{
    public function list()
    {
        try {
            $payment_info_list = PaymentInformation::where('user_id', auth()->user()->id)->select('id', 'user_id', 'payment_type','payment_name', 'payment_instruction', 'bank_name', 'account_name', 'account_number', 'routing_number', 'set_default')->get();

            return response()->json([
                'success' => true,
                'message' => 'Payment Informations fetched successfully',
                'data'    => $payment_info_list
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $payment_information = new PaymentInformation;

            if ($request->bank_name === 'other_bank') {
                $payment_information->bank_name = $request->other_bank_name;
            } else {
                $payment_information->bank_name = $request->bank_name;
            }

            if ($request->payment_name === 'other_method') {
                $payment_information->payment_name = $request->other_payment_method;
            } else {
                $payment_information->payment_name = $request->payment_name;
            }

            $payment_information->user_id              = auth()->user()->id;
            $payment_information->payment_type         = $request->payment_type;
            $payment_information->payment_instruction  = $request->payment_instructions;
            $payment_information->account_name         = $request->account_name;
            $payment_information->account_number       = $request->account_number;
            $payment_information->routing_number       = $request->routing_number;
            $payment_information->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment info stored successfully',
                'data'    => $payment_information
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $payment_information = PaymentInformation::find($id);

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

        return response()->json([
            'status' => true,
            'message' => 'Payment information updated successfully',
            'data' => $payment_information
        ]);
    }
}