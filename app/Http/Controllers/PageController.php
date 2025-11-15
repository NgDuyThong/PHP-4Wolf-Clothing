<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function shippingInfo()
    {
        return view('client.shipping-info');
    }

    public function privacyPolicy()
    {
        return view('client.privacy-policy');
    }

    public function termsConditions()
    {
        return view('client.terms-conditions');
    }

    public function contact()
    {
        return view('client.contact');
    }
    
    public function storeLocations()
    {
        return view('client.store-locations');
    }
}
