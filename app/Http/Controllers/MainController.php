<?php

namespace App\Http\Controllers;

use Gumamax\Products\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MainController extends Controller
{

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index() {

        $bestsellingDimens = [
            "165/70/R14",
            "175/65/R14",
            "185/60/R14",
            "185/60/R15",
            "185/65/R15",
            "195/65/R15",
            "205/60/R16",
            "205/55/R16",
            "225/45/R17"
        ];

        $logoFiles = File::files(public_path('images/visuals/car-logos'));
        $logos = [];
        foreach ($logoFiles as $file) {
            array_push($logos, url('images/visuals/car-logos/' . $file->getFilename()));
        }

        $featured = $this->productRepository->getBestsellers(date("Ymd"),4);

        return view('homepage', compact('logos', 'bestsellingDimens', 'featured'));
    }

}
