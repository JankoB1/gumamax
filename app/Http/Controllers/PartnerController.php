<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerController extends Controller
{

    public function showPartners() {
        return view('partners');
    }

    public function showSinglePartner() {
        return view('single-partner');
    }

}
