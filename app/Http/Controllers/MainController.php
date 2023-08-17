<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MainController extends Controller
{

    public function __construct()
    {

    }

    public function index() {
        $logoFiles = File::files(public_path('images/visuals/car-logos'));
        $logos = [];
        foreach ($logoFiles as $file) {
            array_push($logos, url('images/visuals/car-logos/' . $file->getFilename()));
        }

        return view('homepage', compact('logos'));
    }

}
