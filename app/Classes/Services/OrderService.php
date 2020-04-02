<?php


namespace App\Classes\Services;


use App\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    public static function getAll(): Collection
    {
        $orders = Order::all();
        return $orders;
    }

    public static function getById(Int $orderId): Order
    {
        $order = Order::findOrFail($orderId);
        return $order;
    }

    public static function create(Array $order): Void
    {
        $newOrder = Order::created($order);
    }
}
