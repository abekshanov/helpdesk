<?php


namespace App\Classes\Services;


use App\Classes\Filters\OrderFilter;
use App\Exceptions\LogicException;
use App\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderService
{
    public static function getAll(Request $request): Collection
    {
        $user = $request->user();
        $orders = Order::with('users')->where('parent_id', 0);

        if ($user->cannot('viewAny', Order::class)) {
            return $orders->where('author_id', $user->id)->get();
        }

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


        if ($order['parent_id'] == 0) {
            // если создается новая заявка
            if (self::timeFromLastOrderByUserId($request->user()->id)<1) {
                throw new LogicException('Вы не можете создавать заявки более одного раза в сутки');
            }
            self::createNewOrder($order);
        } else {
            // если создается ответ к существующей заявке
            self::createAnswer($order);

        }
    }

    protected static function createNewOrder(Array $order): Void
    {
        $user = UserService::getUserById($order['author_id']);
        if ($user->cannot('create', Order::class)) {
            throw new LogicException('У вас нет прав');
        }
        $newOrder = Order::create($order);
        MailService::sendToManagersCreatedOrder($newOrder);

    }

    protected static function createAnswer(Array $order): Void
    {
        $newAnswer = Order::create($order);

        if (UserService::isClient($order['author_id'])) {
            // если ответ на заявку создан клиентом
            MailService::sendToManagerAnswer($newAnswer);
        }elseif (UserService::isManager($order['author_id'])){
            // если ответ на заявку создан менеджером
            MailService::sendToClientAnswer($newAnswer);
        }

    }

    public static function update(Int $orderId, Array $data): Order
    {
        $order = Order::find($orderId);
        $order->update($data);
        return $order;
    }

    public static function accept(Int $orderId, Int $userId): Void
    {
        if (self::getById($orderId)->assignee_id) {
            throw new LogicException('Невозможно выполнить. Заявка уже назначена.');
        }

        if (UserService::getUserById($userId)->cannot('accept', Order::class)) {
            throw new LogicException('У вас нет прав');
        }

        $data['assignee_id'] = $userId;
        self::update($orderId, $data);
    }

    public static function setClosedStatus(Int $orderId, String $status): Void
    {
        if (Gate::denies('close', Order::class)) {
            throw new LogicException('У вас нет прав');
        }
        if (self::isOpen($orderId)) {
            // если заявка открыта, то меняет статус заявки на "closed"
            $data['status'] = $status;
            $order = self::update($orderId, $data);
            MailService::sendToManagerClosedOrder($order);
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

    protected static function timeFromLastOrderByUserId(Int $userId): Int
    {
        $latestOrder = Order::where('author_id', $userId)->latest()->first();
        if (!$latestOrder) {
            return 1;
        }
        $date = Carbon::now();
        return $date->diffInDays($latestOrder->created_at, false);
    }

}
