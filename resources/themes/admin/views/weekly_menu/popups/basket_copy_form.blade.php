<h5><span class="lover-case">@lang('labels.basket'):</span> <b>{!! $model->name !!}</b></h5>
<h5><span class="lover-case">@lang('labels.portions'):</span> <b>{!! $portions !!}</b></h5>

<div class="box-body">
    <form action="#" id="basket_copy_form" class="margin-top-20 basket-copy-form">
        <input type="hidden" name="basket_id" id="basket_id" value="{!! $model->id !!}">

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

        <div class="clearfix"></div>
    </form>
</div>