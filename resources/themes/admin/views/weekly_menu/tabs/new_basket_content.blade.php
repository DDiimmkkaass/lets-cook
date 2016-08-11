<div class="weekly-menu-prices-box margin-bottom-15 margin-top-10">
    <div class="form-group">
        <label class="col-sm-2 control-label">@lang('labels.basket_price'):</label>
        <div class="col-sm-10">
            <div class="col-sm-1 with-after-helper currency-rub">
                <input type="text" readonly="readonly" class="form-control input-sm" value="{!! $basket['price'] !!}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">@lang('labels.internal_price'):</label>
        <div class="col-sm-10">
            <div class="col-sm-1 with-after-helper currency-rub">
                <input type="text" readonly="readonly" class="form-control input-sm basket-internal-price" value="0">
            </div>
        </div>
    </div>
</div>

<div class="recipes-add-control">
    <div class="form-group margin-bottom-25">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.recipes')
        </div>
        <div class="col-sm-12 full-width-select">
            <select class="form-control select2 menu-recipe-select input-sm add-recipe load" aria-hidden="true" data-portions="{!! $basket['portions'] !!}" data-basket="{!! $basket['id'] !!}">
                <option value="">@lang('labels.please_select_recipe')</option>
            </select>

            <input type="hidden" name="baskets[{!! $basket['id'] !!}_{!! $basket['portions'] !!}][name]" value="{!! $basket['name'] !!}">
            <input type="hidden" name="baskets[{!! $basket['id'] !!}_{!! $basket['portions'] !!}][id]" value="{!! $basket['id'] !!}">
            <input type="hidden" name="baskets[{!! $basket['id'] !!}_{!! $basket['portions'] !!}][portions]" value="{!! $basket['portions'] !!}">
            <input type="hidden" name="baskets[{!! $basket['id'] !!}_{!! $basket['portions'] !!}][price]" value="{!! $basket['price'] !!}">
        </div>
    </div>
</div>

<h4 class="main-recipe-helper-message col-am-12 margin-bottom-15">
    @lang('messages.click on recipe for make him main')
</h4>

<div id="basket_recipes_{!! $basket['id'] !!}_{!! $basket['portions'] !!}" class="menu-recipes-table">

    @foreach(old('baskets.'.$basket['id'].'_'.$basket['portions'].'.old', []) as $key => $recipe)
        <div id="recipe_{!! $recipe['recipe_id'] !!}" class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4 @if ($recipe['main']) main @endif">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <div class="inner-block col-sm-8 no-padding">
                        <h5>{!! $recipe['name'] !!}</h5>

                        <p>
                            @lang('labels.portions'): {!! $recipe['recipe_portions'] !!}
                        </p>
                        <p>
                            @lang('labels.price'): {!! $recipe['recipe_price'] !!} {!! $currency !!}
                        </p>
                    </div>

                    <div class="image col-sm-4 no-padding text-center">
                        @include('partials.image', ['src' => $recipe['image'], 'attributes' => ['width' => 100, 'class' => 'img-circle']])
                    </div>

                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][main]', $recipe['main'] ? 1 : 0, ['id' => 'baskets_'.$basket['id'].'_'.$basket['portions'].'_old_'.$key.'_main', 'class' => 'main-checkbox']) !!}

                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][image]', $recipe['image']) !!}
                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][name]', $recipe['name']) !!}
                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][recipe_id]', $recipe['recipe_id']) !!}
                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][recipe_portions]', $recipe['recipe_portions']) !!}
                    {!! Form::hidden('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][recipe_price]', $recipe['recipe_price'], ['class' => 'recipe-price']) !!}

                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12 padding-10">
                    <div class="form-group margin-bottom-0 required @if ($errors->has('baskets.'.$basket['id'].'_'.$basket['portions'].'.old.'.$key.'.position')) has-error @endif">
                        {!! Form::label('baskets_'.$basket['id'].'_'.$basket['portions'].'_old_'.$key.'_position', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}

                        <div class="col-sm-9">
                            {!! Form::text('baskets['.$basket['id'].'_'.$basket['portions'].'][old]['.$key.'][position]', $recipe['position'], ['id' => 'baskets_'.$basket['id'].'_'.$basket['portions'].'_old_'.$key.'_position', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <a href="{!! route('admin.recipe.show', $recipe['recipe_id']) !!}" target="_blank" class="small-box-footer lover-case">
                    @lang('labels.detailed') <i class="fa fa-arrow-circle-right"></i>
                </a>
                <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $key !!}" data-name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][remove][]"><i class="fa fa-remove"></i></a>
            </div>
        </div>
    @endforeach

    @foreach(old('baskets.'.$basket['id'].'_'.$basket['portions'].'.new', []) as $recipe_key => $recipe)
        <div id="recipe_{!! $recipe['recipe_id'] !!}" class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4 @if ($recipe['main']) main @endif">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <div class="inner-block col-sm-8 no-padding">
                        <h5>{!! $recipe['name'] !!}</h5>

                        <p>
                            @lang('labels.portions'): {!! $recipe['recipe_portions'] !!}
                        </p>
                        <p>
                            @lang('labels.price'): {!! $recipe['recipe_price'] !!} {!! $currency !!}
                        </p>
                    </div>

                    <div class="image col-sm-4 no-padding text-center">
                        @include('partials.image', ['src' => $recipe['image'], 'attributes' => ['width' => 100, 'class' => 'img-circle']])
                    </div>

                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][main]" value="{!! $recipe['main'] ? 1 : 0 !!}" class="main-checkbox">

                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][recipe_id]" value="{!! $recipe['recipe_id'] !!}">
                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][name]" value="{!! $recipe['name'] !!}">
                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][image]" value="{!! $recipe['image'] !!}">
                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][recipe_portions]" value="{!! $recipe['recipe_portions'] !!}">
                    <input type="hidden" name="baskets[{!! $basket['id'].'_'.$basket['portions'] !!}][new][{!! $recipe_key !!}][recipe_price]" value="{!! $recipe['recipe_price'] !!}" class="recipe-price">

                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12 padding-10">
                    <div class="form-group margin-bottom-0 required @if ($errors->has('baskets.'.$basket['id'].'_'.$basket['portions'].'.new.'.$recipe_key.'.position')) has-error @endif">
                        {!! Form::label('baskets['.$basket['id'].'_'.$basket['portions'].'][new]['.$recipe_key.'][position]', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}

                        <div class="col-sm-9">
                            {!! Form::text('baskets['.$basket['id'].'_'.$basket['portions'].'][new]['.$recipe_key.'][position]', $recipe['position'], ['id' => 'baskets_'.$basket['id'].'_'.$basket['portions'].'_new_'.$recipe_key.'_portions', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <a href="{!! route('admin.recipe.show', $recipe['recipe_id']) !!}" target="_blank" class="small-box-footer lover-case">
                    @lang('labels.detailed') <i class="fa fa-arrow-circle-right"></i>
                </a>
                <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
            </div>
        </div>
    @endforeach

</div>

<div class="clearfix"></div>

@include('weekly_menu.partials.remove_basket_button')