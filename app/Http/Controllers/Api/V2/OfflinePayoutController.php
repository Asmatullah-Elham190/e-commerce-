<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\OfflinePayoutMethod;

class OfflinePayoutController extends Controller
{
    public function list()
    {
        try {
            $payout_list = OfflinePayoutMethod::select('id', 'type', 'name', 'image')->get();

            return response()->json([
                'success' => true,
                'message' => 'Offline payout methods fetched successfully',
                'data'    => $payout_list
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}