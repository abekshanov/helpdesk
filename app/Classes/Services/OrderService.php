<?php


namespace App\Classes\Services;


use App\Classes\Filters\OrderFilter;
use App\Exceptions\LogicException;
use App\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public static function getAll(Request $request): Collection
    {
//        $user = Auth::user();
        $user = $request->user();
        $orders = Order::with('users')->where('parent_id', 0);

//        if ($user->cannot('viewAny', $orders)) {
//            return $orders->where('author_id', Auth::id());
//        }

        $orders = (new OrderFilter($orders, $request))->apply()->get();

        return $orders;
    }

    public static function getById(Int $orderId): Order
    {
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
        $newOrder = Order::create($order);

        if ($order['parent_id'] == 0) {
            // если создана новая заявка
            if (UserService::isClient($order['author_id'])) {
                // создана клиентом
                MailService::sendToManagersCreatedOrder($newOrder);
            }
        } else {
            // если создан ответ к существующей заявке

            if (UserService::isClient($order['author_id'])) {
                // если ответ на заявку создан клиентом
                MailService::sendToManagerAnswer($newOrder);
            }elseif (UserService::isManager($order['author_id'])){
                // если ответ на заявку создан менеджером
                MailService::sendEmailToClientAnswer($newOrder);
            }
        }
    }

    public static function update(Int $orderId, Array $data): Order
    {
        $order = Order::find($orderId);
        $order->update($data);
        return $order;
    }

    public static function assignToManager(Int $orderId, Int $userId): Void
    {
        if (self::getById($orderId)->assignee_id) {
            throw new LogicException('Невозможно выполнить. Заявка уже назначена.');
        }
        if (UserService::isManager($userId)) {
            $data['assignee_id'] = $userId;
            self::update($orderId, $data);
        } else {
            throw new LogicException('У вас нет прав');
        }
    }

    public static function setClosedStatus(Int $orderId, String $status): Void
    {
        if (self::isOpen($orderId)) {
            // если заявка открыта, то меняет статус заявки на "closed"
            $data['status'] = $status;
            $order = self::update($orderId, $data);
            if (UserService::isClient($order['author_id'])) {
                // если заявку закрывает клиент
                MailService::sendToManagerClosedOrder($order);
            }
        }
    }

    public static function isOpen(Int $orderId): Bool
    {
        if (self::getById($orderId)->status != config('helpdesk.status.closed')) {
            return true;
        }
        return false;
    }

    public static function setViewedStatus(Int $orderId): Void
    {
        // если есть запись в связанной таблице, значит заявка была просмотрена
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
