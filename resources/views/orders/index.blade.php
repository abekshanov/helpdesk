@extends('layouts.app')

@section('content')
    <a type="button" href="{{route('orders.create')}}" class="btn btn-outline-primary">Создать</a>

    <p class="mt-5">Фильтр</p>
    <form method='GET' action="{{route('orders.index')}}" class="d-flex flex-row">
        <div class="form-group w-auto mr-2">
            <label><i>Просмотренные/непросмотренные</i></label>
            <select class="form-control" name="viewed">
                <option @if (empty(request('viewed'))) selected @endif value="">Все</option>
                <option @if (request('viewed') == 'viewed') selected @endif value="viewed"> Просмотренные</option>
                <option @if (request('viewed') == 'no-viewed') selected @endif value="no-viewed">Непросмотренные</option>
            </select>
        </div>

        <div class="form-group w-auto mr-2">
            <label><i>Закрытые/незакрытые</i></label>
            <select class="form-control" name="status">
                <option @if (empty(request('status'))) selected @endif value="">Все</option>
                <option @if (request('status') == 'closed') selected @endif value="closed">Закрытые</option>
                <option @if (request('status') == 'open') selected @endif value="open">Незакрытые</option>
            </select>
        </div>

        <div class="form-group w-auto mr-2">
            <label><i>С ответами/без ответов</i></label>
            <select class="form-control" name="hasManagerAnswer">
                <option @if (empty(request('hasManagerAnswer'))) selected @endif value="">Все</option>
                <option @if (request('hasManagerAnswer') == 'answered') selected @endif value="answered">С ответом</option>
                <option @if (request('hasManagerAnswer') == 'no-answered') selected @endif value="no-answered">Без ответа</option>
            </select>
        </div>

        <div class="form-group d-flex flex-wrap align-content-end mr-2">
            <button type="submit" class="btn btn-outline-primary">Применить</button>
        </div>
        <div class="form-group d-flex flex-wrap align-content-end mr-2">
            <a class="btn btn-outline-secondary" href="{{route('orders.index')}}">Сбросить</a>
        </div>

    </form>

    <table class="table table-hover my-3">
        <thead>
        <tr>
            <th>Номер заявки</th>
            <th>Тема</th>
            <th>Статус</th>
            <th>Назначена</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{$order->id}}</td>
                <td>
                    <a href="{{route('orders.show', $order->id)}}">{{$order->title}}</a>
                </td>
                <td>{{$order->status}}</td>
                <td>{{$order->assignee}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
