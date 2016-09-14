@unless (empty($model->main_basket))
    <div class="basket-row">
        <div class="pull-left">{!! $model->main_basket->getName() !!}</div>
        <div class="pull-right">{!! $model->main_basket->price !!}</div>
        <div class="clearfix"></div>
    </div>
@endunless
@foreach($model->additional_baskets as $basket)
    <div class="basket-row">
        <div class="pull-left">{!! $basket->getName() !!}</div>
        <div class="pull-right">{!! $basket->price !!}</div>
        <div class="clearfix"></div>
    </div>
@endforeach