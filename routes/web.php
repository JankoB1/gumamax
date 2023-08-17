<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [MainController::class, 'index'])->name('show-homepage');
Route::get('/proizvod', [ProductController::class, 'showSingleProduct'])->name('show-single-product');
Route::get('/prodavnica', [ProductController::class, 'showShop'])->name('show-shop');
Route::get('/uporedi', [ProductController::class, 'showCompare'])->name('show-compare');
Route::get('/mreza-partnera', [PartnerController::class, 'showPartners'])->name('show-partners');
Route::get('/partner', [PartnerController::class, 'showSinglePartner'])->name('show-single-partner');
Route::get('/porudzbina', [OrderController::class, 'showMakeOrder'])->name('show-make-order');
