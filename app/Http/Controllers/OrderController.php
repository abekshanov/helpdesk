<?php

namespace App\Http\Controllers;

use App\Classes\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = OrderService::getAll();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = $request->all();
        OrderService::create($order);
        return redirect()->route('orders.index');
    }

    public function show($orderId)
    {
        $orders = OrderService::getById($orderId);
        return view('orders.show', compact('orders'));
    }

    public function answer()
    {

    }
}
