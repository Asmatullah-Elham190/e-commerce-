<?php

namespace App\Http\Controllers;

use App\Models\ShippingSystem;
use Illuminate\Http\Request;

class ShippingSystemController extends Controller
{
    public function shiprocket_configuration()
    {
        $shipping_system = ShippingSystem::where('addon_identifier', 'shiprocket')->first();
        return view('backend.shipping_system.index', compact('shipping_system'));
    }

    public function steadfast_configuration()
    {
        $shipping_system = ShippingSystem::where('addon_identifier', 'steadfast')->first();
        return view('backend.shipping_system.index', compact('shipping_system'));
    }

    public function pathao_configuration()
    {
        $shipping_system = ShippingSystem::where('addon_identifier', 'pathao')->first();
        return view('backend.shipping_system.index', compact('shipping_system'));
    }

    public function redx_configuration()
    {
        $shipping_system = ShippingSystem::where('addon_identifier', 'redx')->first();
        return view('backend.shipping_system.index', compact('shipping_system'));
    }
}
