<?php

namespace App\Http\Controllers;

use Delmax\Cart\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function showMakeOrder() {
        return view('make-order');
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
