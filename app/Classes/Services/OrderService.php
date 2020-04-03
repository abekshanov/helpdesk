<?php


namespace App\Classes\Services;


use App\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Psy\Util\Str;

class OrderService
{
    public static function getAll(): Collection
    {
        $orders = Order::where('parent_id', 0)->get();
        return $orders;
    }

    public static function getById(Int $orderId): Order
    {
        $order = Order::findOrFail($orderId);
        return $order;
    }

    public static function create(Request $request): Void
    {
        $order = $request->all();
        $order['file_link'] = self::uploadFile($request);
        Order::create($order);
    }

    public static function update(Int $orderId, Array $data): Void
    {
        Order::find($orderId)->update($data);
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

    public static function downloadFile(String $pathToFile)
    {
        return response()->download($pathToFile);
    }

    public static function viewFile(String $pathToFile)
    {
        return response()->file($pathToFile);
    }
}
