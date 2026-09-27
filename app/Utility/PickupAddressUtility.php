<?php

namespace App\Utility;

use App\Models\PickupAddress;

class PickupAddressUtility
{
    public static function delete_pickup_address($id)
    {
        $pickup_address = PickupAddress::where('id', $id)->first();
        if (!is_null($pickup_address)) {
            $pickup_address->delete();
        }
    }
}
