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
        OrderService::create($request);
        return redirect()->route('orders.index');
    }

    public function show(Int $orderId)
    {
        $order = OrderService::getById($orderId);
        $answers = OrderService::getOrderAnswers($orderId);
        return view('orders.show', compact('order', 'answers'));
    }

    public function update($orderId, $status)
    {
        $order['status'] = $status;
        OrderService::update($orderId, $order);
        return redirect()->route('orders.index');
    }
}
