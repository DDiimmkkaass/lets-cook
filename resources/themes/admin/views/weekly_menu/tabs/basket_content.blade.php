<div class="weekly-menu-prices-box margin-bottom-40 margin-top-10">
    <div class="form-group">
        <label class="col-sm-2 control-label">@lang('labels.basket_price'):</label>
        <div class="col-sm-10">
            <table class="table table-bordered no-margin">
                <tbody>
                <tr>
                    @foreach($basket->getWeekPrice() as $day => $price)
                        <td class="text-center">
                            {!! $day . ' ' . trans_choice('labels.count_of_days', $day) !!}:
                            {!! $price !!} {!! $currency !!}

                            <input type="hidden"
                                   name="baskets[{!! $basket->basket_id !!}_{!! $basket->portions !!}][prices][{!! $day !!}]"
                                   value="{!! $day . ' ' . trans_choice('labels.count_of_days', $day) !!}: {!! $price !!} {!! $currency !!}">
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">@lang('labels.internal_price'):</label>
        <div class="col-sm-10">
            <div class="col-sm-1 with-after-helper currency-rub">
                <input type="text" readonly="readonly" class="form-control input-sm basket-internal-price"
                       value="{!! $basket->getInternalPrice() !!}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">@lang('labels.delivery_date'):</label>
        <div class="col-sm-10">
            <div class="col-sm-2">
                <div class="input-group">
                    <input type="text" class="form-control input-sm date inputmask-birthday datepicker-birthday"
                           name="baskets[{!! $basket->basket_id !!}_{!! $basket->portions !!}][delivery_date]"
                           value="{!! $basket->getDeliveryDate() !!}">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="basket_recipes_{!! $basket->basket_id !!}_{!! $basket->portions !!}" class="menu-recipes-table">

    @if (isset($model) && $model->exists)
        @foreach($basket->recipes as $recipe)
            <div id="recipe_{!! $recipe->recipe->id !!}"
                 class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <div class="inner-block col-sm-8 no-padding">
                            <h5>{!! $recipe->recipe->name !!}</h5>

                            <p>
                                @lang('labels.portions'): {!! $recipe->recipe->portions !!}
                            </p>
                            <p>
                                @lang('labels.price'): {!! $recipe->recipe->getPrice() !!} {!! $currency !!}
                            </p>
                        </div>

                        <div class="image col-sm-4 no-padding text-center">
                            @include('partials.image', ['src' => $recipe->recipe->image, 'attributes' => ['width' => 100, 'class' => 'img-circle']])
                        </div>

                        {!! Form::hidden('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][image]', $recipe->recipe->image) !!}
                        {!! Form::hidden('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][name]', $recipe->recipe->name) !!}
                        {!! Form::hidden('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][recipe_id]', $recipe->recipe->id) !!}
                        {!! Form::hidden('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][recipe_portions]', $recipe->recipe->portions) !!}
                        {!! Form::hidden('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][recipe_price]', $recipe->recipe->getPrice(), ['class' => 'recipe-price']) !!}

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 padding-10">
                        <div class="form-group margin-bottom-0 required @if ($errors->has('baskets.'.$basket->basket_id.'.'.$basket->portions.'.old.'.$recipe->id.'.position')) has-error @endif">
                            {!! Form::label('baskets_'.$basket->basket_id.'_'.$basket->portions.'_old_'.$recipe->id.'_position', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('baskets['.$basket->basket_id.'_'.$basket->portions.'][old]['.$recipe->id.'][position]', $recipe->position, ['id' => 'baskets_'.$basket->basket_id.'_'.$basket->portions.'_old_'.$recipe->id.'_position', 'class' => 'form-control input-sm position-input', 'aria-hidden' => 'true', 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <a href="{!! route('admin.recipe.show', $recipe->recipe->id) !!}" target="_blank"
                       class="small-box-footer lover-case">
                        @lang('labels.detailed') <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $recipe->id !!}"
                       data-name="baskets[{!! $basket->basket_id.'_'.$basket->portions !!}][remove][]"><i
                                class="fa fa-remove"></i></a>
                </div>
            </div>

        @endforeach
    @endif

</div>

<div class="clearfix"></div>

<div class="recipes-add-control">
    <div class="form-group">
        <div class="col-sm-12">
            @include('weekly_menu.partials.recipes_select', ['basket_id' => $basket->basket_id, 'portions' => $basket->portions])

            <input type="hidden" name="baskets[{!! $basket->basket_id !!}_{!! $basket->portions !!}][name]"
                   value="{!! $basket->basket->name !!}">
            <input type="hidden" name="baskets[{!! $basket->basket_id !!}_{!! $basket->portions !!}][id]"
                   value="{!! $basket->basket_id !!}">
            <input type="hidden" name="baskets[{!! $basket->basket_id !!}_{!! $basket->portions !!}][portions]"
                   value="{!! $basket->portions !!}">
        </div>
    </div>
</div>

<div class="clearfix"></div>

@include('weekly_menu.partials.remove_basket_button')