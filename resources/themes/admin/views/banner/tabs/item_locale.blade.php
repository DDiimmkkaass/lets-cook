@if (is_array($item))
    <div class="form-group">
        <div class="form-group">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][title]', $item[$locale]['title'], ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.title', 'placeholder' => trans('labels.title'), 'class' => 'form-control input-sm']) !!}
        </div>

        <div class="form-group @if ($errors->has('items.'.$key.'.' .$id. '.'. $locale .'.text')) has-error @endif">
            {!! Form::textarea('items['.$key.'][' . $id . '][' . $locale. '][text]', $item[$locale]['text'], ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.text', 'placeholder' => trans('labels.text'), 'rows' => 2, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('items.new.' .$id. '.text', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@else
    <div class="form-group">
        <div class="form-group">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][title]', isset($item->translate($locale)->title) ? $item->translate($locale)->title : '', ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.title', 'placeholder' => trans('labels.title'), 'class' => 'form-control input-sm']) !!}
        </div>

        <div class="form-group @if ($errors->has('items.'.$key.'.' .$id. '.'. $locale .'.text')) has-error @endif">
            {!! Form::textarea('items['.$key.'][' . $id . '][' . $locale. '][text]', isset($item->translate($locale)->text) ? $item->translate($locale)->text : '', ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.text', 'placeholder' => trans('labels.text'), 'rows' => 2, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('items.new.' .$id. '.text', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>
@endif