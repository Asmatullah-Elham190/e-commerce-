<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\CombinedOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Redirect;

class PhonepeController extends Controller
{
     public function pay( Request $request)
    {
        $phonepeVersion = get_setting('phonepe_version', '1');
        return $this->{"payByV$phonepeVersion"}($request);
    }



     public function payByV1($request) {   
        return response()->json(['result' => false, 'message' => translate('PhonePe V1 is deprecated, please use PhonePe V2')], 400);
    }

    public function payByV2($request)
    {
        $paymentType = $request->payment_type;
        $merchantUserId = $request->user_id;
        $amount = $request->amount;
        $userId = $request->user_id;

        if ($paymentType == 'cart_payment') {
            $combined_order = CombinedOrder::find($request->combined_order_id);
            $amount = $combined_order->grand_total;
            $merchantTransactionId = $paymentType . '-' . $combined_order->id . '-' . $userId . '-' . rand(0, 100000);
        } elseif ($paymentType == 'order_re_payment') {
            $order = Order::find($request->order_id);
            $amount = $order->grand_total;
            $merchantTransactionId = $paymentType . '-' . $order->id . '-' . $userId . '-' . rand(0, 100000);
        } elseif ($paymentType == 'wallet_payment') {
            $merchantTransactionId = $paymentType . '-' . $userId . '-' . $userId . '-' . rand(0, 100000);
        } elseif ($paymentType == 'seller_package_payment' || $paymentType == 'customer_package_payment') {
            $merchantTransactionId = $paymentType . '-' . $request->package_id . '-' . $userId . '-' . rand(0, 100000);
        }
        $isSandbox = get_setting('phonepe_sandbox') == 1;
        $tokenUrl = $isSandbox
            ? 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token'
            : 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';

        $payUrl = $isSandbox
            ? 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay'
            : 'https://api.phonepe.com/apis/pg/checkout/v2/pay';

        // Get OAuth2 Token
        $tokenResponse = Http::asForm()->post($tokenUrl, [
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
            'client_version' => env('PHONEPE_CLIENT_VERSION'),
        ]);

        $tokenData = $tokenResponse->json();

        if (!$tokenResponse->successful() || empty($tokenData['access_token'])) {
            \Log::error('PhonePe V2 Token Error', ['response' => $tokenData]);
            abort(500, 'PhonePe authentication failed');
        }

        $accessToken = $tokenData['access_token'];
        $payload = [
            'merchantOrderId' => $merchantTransactionId,
            'merchantUserId' => $merchantUserId,
            'amount' => $amount * 100,
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'message' => 'Proceeding with payment',
                'merchantUrls' => [
                    'redirectUrl' => route('phonepe.redirecturl'),
                    'callbackUrl' => route('phonepe.callbackUrl'),
                ],
            ],
            'metaInfo' => [
                'userId' => $userId,
                'paymentType' => $paymentType,
            ],
        ];

        $payResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'O-Bearer ' . $accessToken,
        ])->post($payUrl, $payload);

        $payData = $payResponse->json();

        $redirectUrl = $payData['redirectUrl'] ?? null;
        if ($redirectUrl) {
            $urlParts = parse_url($redirectUrl);
            $query = [];
            parse_str($urlParts['query'] ?? '', $query);
            $checkoutData = [
                'token' => $query['token'] ?? null,
                'orderId' => $payData['orderId'] ?? null,
                'accessToken' => $accessToken,
                'merchantTransactionId' => $merchantTransactionId 
            ];
            return $checkoutData;
        }
    }

    

    public function phonepe_redirecturl(Request $request)
    {
        $payment_type = explode("-", $request['transactionId']);
        // auth()->login(User::findOrFail($payment_type[2]));
        // dd($payment_type[0], $payment_type[1], $request['merchantId'], $request['transactionId'], $request->all());

        if ($request['code'] == 'PAYMENT_SUCCESS') {
            return response()->json(['result' => true, 'message' => translate("Payment is successful")]);
        }
        return response()->json(['result' => false, 'message' => translate("Payment is failed")]);
    }

    public function phonepe_callbackUrl(Request $request)
    {
        $res = $request->all();
        $response = $res['response'];
        $decodded_response = json_decode(base64_decode($response));

        $payment_type = explode("-", $decodded_response->data->merchantTransactionId);
        $moid = $decodded_response->data->merchantTransactionId;
        $accessToken =   $decodded_response->data->accessToken;
        $statusData = $this->getPhonePeOrderStatus($moid, $accessToken);
        $decoded_data = $statusData->getData();
        $payment_details= $this->paymentDetails($decoded_data->data);
        $payment_type = explode("-", $moid);
        $amount = $decodded_response->data->amount;


        if ($decoded_data->data->state == 'COMPLETED') {
            if ($payment_type[0] == 'cart_payment') {
                checkout_done($payment_type[1], json_encode($payment_details));
            }
            elseif ($payment_type[0] == 'order_re_payment') {
                order_re_payment_done($payment_type[1], 'phonepe', json_encode($payment_details));
            }
            elseif ($payment_type[0] == 'wallet_payment') {
                wallet_payment_done($payment_type[2], $amount, 'phonepe', json_encode($payment_details));
            }
            elseif ($payment_type[0] == 'seller_package_payment') {
                seller_purchase_payment_done($payment_type[2], $payment_type[1], 'phonepe', json_encode($payment_details));
            }
            elseif ($payment_type[0] == 'customer_package_payment') {
                customer_purchase_payment_done($payment_type[2], $payment_type[1], 'phonepe', json_encode($payment_details));
            }
        }else {
            return response()->json(['result' => false, 'message' => translate('Payment failed')]);
        }

    }


    private function getPhonePeOrderStatus($moid, $accessToken)
    {
        $isSandbox = get_setting('phonepe_sandbox') == 1;
        $checkStatusUrl = $isSandbox
            ? "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order/{$moid}/status"
            : "https://api.phonepe.com/apis/pg/checkout/v2/order/{$moid}/status";


        $url = $checkStatusUrl;
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'O-Bearer ' . $accessToken,
            ])->get($url);

            return response()->json([
                'status' => 'success',
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    function paymentDetails($data)
    {
        if (!isset($data->paymentDetails[0]) || !isset($data->paymentDetails[0]->splitInstruments[0])) {
            return null; 
        }

        $paymentDetail = $data->paymentDetails[0];
        $splitInstrument = $paymentDetail->splitInstruments[0];

        return [
            'orderId' => $data->orderId ?? null,
            'state' => $data->state ?? null,
            'amount' => $data->amount ?? null,
            'packageId' => $data->metaInfo->packageId ?? null,
            'userId' => $data->metaInfo->userId ?? null,
            'paymentType' => $data->metaInfo->paymentType ?? null,
            'paymentDetails' => [
                [
                    'paymentMode' => $paymentDetail->paymentMode ?? null,
                    'transactionId' => $paymentDetail->transactionId ?? null,
                    'amount' => $paymentDetail->amount ?? null,
                    'state' => $paymentDetail->state ?? null,
                    'paymentInstrument' => [
                        'type' => $splitInstrument->instrument->type ?? null,
                        'bankId' => $splitInstrument->instrument->bankId ?? null,
                    ]
                ]
            ]
        ];
    }


    public function getPhonePayCredentials()
    {
        $credentials = [
            'mode' => get_setting('phonepe_sandbox') ? "SANDBOX" : "PRODUCTION",
            'client_id' => env('PHONEPE_CLIENT_ID'),
            'client_secret' => env('PHONEPE_CLIENT_SECRET'),
            'client_version' => env('PHONEPE_CLIENT_VERSION'),
        ];
        return response()->json($credentials);
       
    }
}
