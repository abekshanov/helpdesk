@extends('layouts.app')

@section('content')
<div class="container">
    <p><b>Создать заявку:</b></p>

    @include('orders.form', ['parentId' => 0, 'status' => config('helpdesk.default.status')])
</div>
@endsection
