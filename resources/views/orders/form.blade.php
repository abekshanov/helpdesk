<div  class="orderForm col">
    {{--Вывод ошибок --}}
    <ul class=" alert alert-danger validationErrors"  style="display: none">
    </ul>

    <form method="post" enctype="multipart/form-data" action="{{route('orders.store')}}">
        <input type="text" name="title" class="form-control" id="" placeholder="Тема">
        <textarea rows="5" name="description" id="" class="form-control m-2" type="text" placeholder="Сообщение" ></textarea>
        <div class="custom-file m-2">
            <input type="file" class="customFile custom-file-input" id="" name="customFile" />
            <label class="custom-file-label" for="customFile">Выберите файл</label>
        </div>
        <div class="m-2">
            <input hidden name="status" value="{{config('helpdesk.default.status')}}">
            <input type="button" name="send"  class="send btn btn-outline-primary" value="Отправить" />
            <button type="button" class="btn-outline-secondary">Назад</button>
        </div>
    </form>
</div>
