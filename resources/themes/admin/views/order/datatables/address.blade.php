{!! $model->getFullAddress() !!} <br>

@if ($model->comment)
    <div class="nowrap">-----------------</div>
    {!! $model->comment !!}
@endif