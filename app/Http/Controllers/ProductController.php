<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function showSingleProduct() {
        return view('single-product');
    }

    public function showShop() {
        return view('shop');
    }

    public function showCompare() {
        return view('compare');
    }

}
