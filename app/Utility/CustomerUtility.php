<?php

namespace App\Utility;

use App\Models\User;

class CustomerUtility
{
    public static function delete_customer($id)
    {
        $customer = User::where('id', $id)->first();
        if (!is_null($customer)) {
            $customer->customer_products()->delete();
            $customer->delete();
        }
    }
}