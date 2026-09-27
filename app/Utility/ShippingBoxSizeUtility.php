<?php

namespace App\Utility;

use App\Models\ShippingBoxSize;

class ShippingBoxSizeUtility
{
    public static function delete_shipping_box_size($id)
    {
        $shipping_box_size = ShippingBoxSize::where('id', $id)->first();
        if (!is_null($shipping_box_size)) {
            $shipping_box_size->delete();
        }
    }
}