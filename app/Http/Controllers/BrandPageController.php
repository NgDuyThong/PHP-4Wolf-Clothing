<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandPageController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name', 'asc')->paginate(12);
        return view('client.brands', compact('brands'));
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        $products = $brand->products()->paginate(12);
        return view('client.brand-detail', compact('brand', 'products'));
    }
}
