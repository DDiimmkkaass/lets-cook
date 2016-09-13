<div class="padding-15 order-additional-baskets">
    @foreach($additional_baskets as $basket)
        <div class="form-group @if ($errors->has('baskets['.$basket->id.']')) has-error @endif">
            <div class="col-xs-12 col-sm-6">
                <label for="baskets_{!! $basket->id !!}" class="checkbox-label">
                    {!! Form::checkbox('baskets['.$basket->id.']', $basket->id, isset($selected_baskets[$basket->id]) ? true : false, ['id' => 'baskets_'.$basket->id, 'class' => 'square']) !!}
                </label>
                <span class="margin-left-10 basket-name"><b>{!! link_to_route('admin.basket.show', $basket->name, [$basket->id, 'type' => 'additional'], ['target' => '_blank']) !!}</b> ({!! isset($selected_baskets[$basket->id]) ? $selected_baskets[$basket->id]['price'] : $basket->price !!} {!! $currency !!}) </span>

                {!! $errors->first('baskets['.$basket->id.']', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    @endforeach
</div>