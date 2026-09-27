<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function dashboard()
    {
        return view('backend.marketing.dashboard');
    }
}
