<div id="recipe_{!! $model->id !!}" class="recipe-block col-xs-12 col-sm-6 col-md-6 col-lg-4">
    <div class="small-box bg-aqua @if ($model->main) main @endif">
        <div class="inner">
            <div class="inner-block col-sm-8 no-padding">
                <h4>{!! $model->name !!}</h4>

                <p class="font-size-12">
                    @lang('labels.portions_lowercase'): {!! $model->portions !!}
                </p>

                <input type="hidden" name="baskets[{!! $basket_id.'_'.$portions !!}][new][{!! $model->id !!}][recipe_id]" value="{!! $model->id !!}">
                <input type="hidden" name="baskets[{!! $basket_id.'_'.$portions !!}][new][{!! $model->id !!}][name]" value="{!! $model->name !!}">
                <input type="hidden" name="baskets[{!! $basket_id.'_'.$portions !!}][new][{!! $model->id !!}][image]" value="{!! $model->image !!}">
                <input type="hidden" name="baskets[{!! $basket_id.'_'.$portions !!}][new][{!! $model->id !!}][recipe_portions]" value="{!! $model->portions !!}">
            </div>

            <div class="image col-sm-4 no-padding text-center">
                @include('partials.image', ['src' => $model->image, 'attributes' => ['width' => 100, 'class' => 'img-circle']])
            </div>

            {!! Form::hidden('baskets['.$basket_id.'_'.$portions.'][new]['.$model->id.'][main]', 0, ['id' => 'baskets_'.$basket_id.'_'.$portions.'_new_'.$model->id.'_main', 'class' => 'main-checkbox']) !!}

            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 padding-10">
            <div class="form-group margin-bottom-0 required">
                {!! Form::label('baskets['.$basket_id.'_'.$portions.'][new]['.$model->id.'][position]', trans('labels.position'), ['class' => 'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('baskets['.$basket_id.'_'.$portions.'][new]['.$model->id.'][position]', 0, ['id' => 'baskets_'.$portions.'_'.$basket_id.'_new_'.$model->id.'_portions', 'class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <a href="{!! route('admin.recipe.show', $model->id) !!}" target="_blank" class="small-box-footer lover-case">
            @lang('labels.detailed') <i class="fa fa-arrow-circle-right"></i>
        </a>
        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
    </div>
</div>