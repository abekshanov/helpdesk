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

    public function index(Request $request)
    {
        $orders = OrderService::getAll($request);
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        OrderService::create($request);
        return redirect()->route('orders.index')->with('systemMessage', 'Данные по заявке сохранены');
    }

    public function show(Int $orderId)
    {
        $order = OrderService::getById($orderId);
        $answers = OrderService::getOrderAnswers($orderId);
        OrderService::setViewedStatus($orderId);
        return view('orders.show', compact('order', 'answers'));
    }

    public function close($orderId, $status)
    {
        OrderService::setClosedStatus($orderId, $status);
        return redirect()->route('orders.index')->with('systemMessage', 'Заявка закрыта');
    }

    public function accept($orderId, $userId)
    {
        OrderService::accept($orderId, $userId);
        return redirect()->route('orders.index')->with('systemMessage', 'Заявка принята в работу');
    }
}
