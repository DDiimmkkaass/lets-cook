<h5><span class="lover-case">@lang('labels.recipe'):</span> <b>{!! $model->name !!}</b></h5>
<h5><span class="lover-case">@lang('labels.portions'):</span> <b>{!! $model->portions !!}</b></h5>

<div class="box-body">
    <form action="{!! route('admin.recipe.copy', $model->id) !!}" id="recipe_copy_form" class="margin-top-20 recipe-copy-form">
        <div class="form-group margin-bottom-10">
            <div class="col-xs-3">
                <select class="form-control select2 basket-select" name="portions" id="portions">
                    @foreach(config('recipe.available_portions') as $portion)
                        <option value="{!! $portion !!}">
                            {!! $portion !!} @choice('labels.count_of_portions', $portion)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-9">
                <label for="portions" class="control-label margin-top-4">@lang('labels.new_count_of_portion')</label>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="form-group margin-bottom-10">
            <div class="col-xs-3 text-right">
                <label for="bind" class="checkbox-label">
                    {!! Form::checkbox('bind', 1, false, ['id' => 'bind', 'class' => 'square']) !!}
                </label>

            </div>
            <div class="col-xs-9">
                <label for="bind" class="control-label margin-top-4">@lang('labels.bind_recipes')</label>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
    </form>
</div>