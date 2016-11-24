@if (!empty($model->main_basket))
    <div class="basket-row">
        <div class="pull-left">{!! $model->main_basket->getName() !!}</div>
        <div class="pull-right">{!! $model->main_basket->price !!}</div>
        <div class="clearfix"></div>
    </div>
@else
    @if ($model->isStatus('tmpl'))
        @if (isset($model->user->subscribe))
            <div class="basket-row">
                <div class="pull-left">{!! $model->user->subscribe->basket->name !!}</div>
                <div class="pull-right">0</div>
                <div class="clearfix"></div>
            </div>
        @endif
    @endif
@endif
@foreach($model->additional_baskets as $basket)
    <div class="basket-row">
        <div class="pull-left">{!! $basket->getName() !!}</div>
        <div class="pull-right">{!! $basket->price !!}</div>
        <div class="clearfix"></div>
    </div>
@endforeach
@foreach($model->ingredients as $ingredient)
    <div class="basket-row">
        <div class="pull-left">{!! $ingredient->getName() !!}&nbsp;
            ({!! $ingredient->count !!}&nbsp;{!! $ingredient->getSaleUnit() !!})
        </div>
        <div class="pull-right">{!! $ingredient->getPriceInOrder() !!}</div>
        <div class="clearfix"></div>
    </div>
@endforeach