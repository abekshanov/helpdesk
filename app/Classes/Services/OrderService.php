<?php


namespace App\Classes\Services;


use App\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public static function getAll(): Collection
    {
        $user = Auth::user();
        $orders = Order::where('parent_id', 0)->get();

        if ($user->cannot('viewAll', $orders)) {
            return $orders->where('author_id', Auth::id());
        }

        return $orders;
    }

    public static function getById(Int $orderId): Order
    {
        self::setViewedStatus($orderId);
        $order = Order::findOrFail($orderId);
        return $order;
    }

    public static function getOrderAnswers(Int $orderId): ?Collection
    {
        $answers = Order::where('parent_id', $orderId)->get();
        return $answers;
    }

    public static function create(Request $request): Void
    {
        $order = $request->all();
        $order['file_link'] = self::uploadFile($request);
        $user = $request->user();

        if ($user->can('create', $order)) {
            Order::create($order);
        }
    }

    public static function update(Int $orderId, Array $data): Void
    {
        Order::find($orderId)->update($data);
    }

    public static function setViewedStatus(Int $orderId): Void
    {
        $userId = Auth::id();
        $user = Auth::user();
        if ($user->hasRole('manager')) {

            $order = Order::find($orderId);
            $count = $order->users()->where('user_id', $userId)->count();
            if (!$count) {
                $order->users()->attach($userId);
            }
        }
    }

    public static function getViewedStatus(Int $orderId): Bool
    {
        $userId = Auth::id();
        $order = Order::find($orderId);
        $count = $order->users()->find($userId)->count();
        if($count) {
            return true;
        }
        return false;
    }

    public static function uploadFile(Request $request): ?String
    {
        if ($request->hasFile('customFile')) {
            //проверяем: если файл получен
            //сохраняем файл по указанному пути, запоминаем путь
            $path = $request->file('customFile')->store('upload', 'public');
            $pathImg = 'storage/' . $path;
            $path = asset($pathImg); //получаем src в виде url к img

            return $path;
        }

        return null;
    }

}
