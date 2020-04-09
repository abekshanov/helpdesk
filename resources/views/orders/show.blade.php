@extends('layouts.app')

@section('content')
    <p><b>Номер заявки: </b> {{$order->id}}</p>
    <p><b>Тема:</b> {{$order->title}}</p>
    <p><b>Сообщение: </b>{{$order->description}}</p>
    <p>
        <b>Влолжение: </b>
        @if (isset($order->file_link))
            <a href="{{$order->file_link}}"  target="_blank"  type="button"
               class="badge badge-pill badge-primary ml-2">Просмотр</a>
        @else
            Отсутствует
        @endif
    </p>
    <p>
        <b>Назначена: </b>
        @if ($order->assignee_id)
            {{$order->users()->find($order->assignee_id)->name}}
        @else
            Нет исполнителя
        @endif
        <a href="{{route('orders.accept', ['id' => $order->id, 'user_id' => Auth::id()])}}" type="button"
           class="btn btn-success ml-2">Принять на выполнение</a>
    </p>
    <p>
        <b>Статус: </b>{{$order->status}}
        <a href="{{route('orders.close', ['id' => $order->id, 'status' => config('helpdesk.status.closed')])}}" type="button"
           class="btn btn-outline-success ml-2">Закрыть заявку</a>
    </p>

    <div>
        <hr>
        @if ($answers->count())
            <p><b>Вложенные ответы >>></b></p>
            @foreach ($answers as $answer)
                <i>Тема: {{$answer->title}}</i><br>
                <i>Сообщение: {{$answer->description}}</i><br>
                <i>Влолжение: </i>
                @if (isset($answer->file_link))
                    <a href="{{$answer->file_link}}"  target="_blank"  type="button"
                       class="badge badge-pill badge-primary ml-2">Просмотр</a>
                @else
                    Отсутствует
                @endif
                <hr>
            @endforeach
        @endif
    </div>

    <p><b>Ответить на заявку:</b></p>
    @include('orders.form', ['parentId' => $order->id, 'status' => config('helpdesk.status.answer')])
@endsection
