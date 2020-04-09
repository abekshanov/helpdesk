@extends('layouts.app')

@section('content')
    <p><b>Создать заявку:</b></p>

    @include('orders.form', ['parentId' => 0, 'status' => config('helpdesk.default.status')])
@endsection
