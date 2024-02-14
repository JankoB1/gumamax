<?php

namespace App\Http\Controllers;

use Delmax\Cart\Models\Order;
use Illuminate\Http\Request;
use Delmax\Webapp\Models\City;

class OrderController extends Controller
{

    public function showMakeOrder() {
        $cart = session()->get("cart");
        $srbCities = City::serbianCities();
        return view('make-order', compact("cart", "srbCities"));
    }

    public function apiUserOrders()
    {
        $query = Order::getUserOrders();

        $d = datatables()::of($query);

        return $d->make(true);

    }

    public function index($status)
    {

        return view('admin.orders.index', compact('status'));

    }

    public function apiDatatables($status)
    {
        $d = Order::getApiDataTablesData();

        return $d;

    }

    public function apiCount($period)
    {

        $count = Order::apiCount($period);

        return $this->respond(compact('count'));

    }

}
