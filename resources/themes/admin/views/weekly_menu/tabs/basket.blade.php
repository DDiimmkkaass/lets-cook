<div class="recipes-add-control">
    <div class="form-group margin-bottom-25">
        <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
            @lang('labels.recipes')
        </div>
        <div class="col-sm-12 full-width-select">
            <select class="form-control select2 menu-recipe-select input-sm add-recipe" aria-hidden="true" data-basket="{!! $basket->id !!}">
                <option value="">@lang('labels.please_select_recipe')</option>
                @foreach($basket->allowed_recipes as $recipe)
                    <option value="{!! $recipe->id !!}">{!! $recipe->name !!}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="box-body no-padding">
    <h4 class="main-recipe-helper-message col-am-12 margin-bottom-15">
        @lang('messages.click on recipe for make him main')
    </h4>

    <div id="basket_recipes_{!! $basket->id !!}" class="menu-recipes-table">

        @if ($model->exists)
            @foreach($basket->recipes()->whereWeeklyMenuId($model->id)->get() as $recipe)

                <div id="recipe_{!! $recipe->recipe->id !!}" class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4 @if ($recipe->main) main @endif">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <div class="inner-block col-sm-8 no-padding">
                                <h5>{!! $recipe->recipe->name !!}</h5>

                                <p>
                                    @lang('labels.portions'): {!! $recipe->recipe->portions !!}
                                </p>
                            </div>

                            <div class="image col-sm-4 no-padding text-center">
                                @include('partials.image', ['src' => $recipe->recipe->image, 'attributes' => ['width' => 100, 'class' => 'img-circle']])
                            </div>

                            {!! Form::hidden('baskets['.$basket->id.'][old]['.$recipe->id.'][main]', $recipe->main ? 1 : 0, ['id' => 'baskets_'.$basket->id.'_old_'.$recipe->id.'_main', 'class' => 'main-checkbox']) !!}

                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 padding-10">
                            <div class="form-group required @if ($errors->has('baskets.'.$basket->id.'.old.'.$recipe->id.'.portions')) has-error @endif">
                                {!! Form::label('portions', trans('labels.portions'), ['class' => 'control-label col-sm-3']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('baskets['.$basket->id.'][old]['.$recipe->id.'][portions]', $recipe->portions, ['id' => 'baskets_'.$basket->id.'_old_'.$recipe->id.'_portions', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                                </div>
                            </div>

                            <div class="form-group margin-bottom-0 required @if ($errors->has('baskets.'.$basket->id.'.old.'.$recipe->id.'.position')) has-error @endif">
                                {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('baskets['.$basket->id.'][old]['.$recipe->id.'][position]', $recipe->position, ['id' => 'baskets_'.$basket->id.'_old_'.$recipe->id.'_position', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <a href="{!! route('admin.recipe.show', $recipe->recipe->id) !!}" target="_blank" class="small-box-footer lover-case">
                            @lang('labels.detailed') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $recipe->id !!}" data-name="baskets[{!! $basket->id !!}][remove][]"><i class="fa fa-remove"></i></a>
                    </div>
                </div>

            @endforeach
        @endif

        @if (count(old('baskets.'.$basket->id.'.new')))
            @foreach(old('baskets.'.$basket->id.'.new') as $recipe_key => $recipe)

                    <div id="recipe_{!! $recipe['recipe_id'] !!}" class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4 @if ($recipe['main']) main @endif">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <div class="inner-block col-sm-8 no-padding">
                                    <h5>{!! $recipe['name'] !!}</h5>

                                    <p>
                                        @lang('labels.portions'): {!! $recipe['recipe_portions'] !!}
                                    </p>
                                </div>

                                <div class="image col-sm-4 no-padding text-center">
                                    @include('partials.image', ['src' => $recipe['image'], 'attributes' => ['width' => 100, 'class' => 'img-circle']])
                                </div>

                                <input type="hidden" name="baskets[{!! $basket->id !!}][new][{!! $recipe_key !!}][recipe_id]" value="{!! $recipe['recipe_id'] !!}">
                                <input type="hidden" name="baskets[{!! $basket->id !!}][new][{!! $recipe_key !!}][name]" value="{!! $recipe['name'] !!}">
                                <input type="hidden" name="baskets[{!! $basket->id !!}][new][{!! $recipe_key !!}][image]" value="{!! $recipe['image'] !!}">
                                <input type="hidden" name="baskets[{!! $basket->id !!}][new][{!! $recipe_key !!}][recipe_portions]" value="{!! $recipe['recipe_portions'] !!}">

                                <input type="hidden" class="main-checkbox" name="baskets[{!! $basket->id !!}][new][{!! $recipe_key !!}][main]" value="{!! $recipe['main'] ? 1 : 0 !!}">

                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 padding-10">
                                <div class="form-group required @if ($errors->has('baskets.'.$basket->id.'.new.'.$recipe_key.'.position')) has-error @endif">
                                    {!! Form::label('portions', trans('labels.portions'), ['class' => 'control-label col-sm-3']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::text('baskets['.$basket->id.'][new]['.$recipe_key.'][portions]', $recipe['portions'], ['id' => 'baskets_'.$basket->id.'_new_'.$recipe_key.'_portions', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                                    </div>
                                </div>

                                <div class="form-group margin-bottom-0 required @if ($errors->has('baskets.'.$basket->id.'.new.'.$recipe_key.'.position')) has-error @endif">
                                    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::text('baskets['.$basket->id.'][new]['.$recipe_key.'][position]', $recipe['position'], ['id' => 'baskets_'.$basket->id.'_new_'.$recipe_key.'_portions', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
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
        @endif

    </div>
</div>