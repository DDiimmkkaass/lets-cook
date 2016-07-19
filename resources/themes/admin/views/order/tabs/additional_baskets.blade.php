<div class="padding-15 order-additional-baskets">
    @foreach($additional_baskets as $basket)
        <div class="form-group @if ($errors->has('baskets['.$basket->id.']')) has-error @endif">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                <label for="baskets_{!! $basket->id !!}" class="checkbox-label">
                    {!! Form::checkbox('baskets['.$basket->id.']', $basket->id, isset($selected_baskets[$basket->id]) ? true : false, ['id' => 'baskets_'.$basket->id, 'class' => 'square']) !!}
                </label>
                <span class="margin-left-10 basket-name"><b>{!! $basket->name !!}</b> ({!! $basket->price !!} {!! $currency !!}) </span>

                {!! $errors->first('baskets['.$basket->id.']', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    @endforeach
</div>