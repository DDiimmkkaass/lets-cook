@if (is_array($item))
    <div class="form-group">
        <div class="form-group required @if ($errors->has('items.'.$key.'.' .$id. '.'. $locale .'.name')) has-error @endif">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][name]', $item[$locale]['name'], ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => 'true']) !!}
        </div>

        <div class="form-group">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][title]', $item[$locale]['title'], ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.title', 'placeholder' => trans('labels.link_title'), 'class' => 'form-control input-sm']) !!}
        </div>
    </div>
@else
    <div class="form-group">
        <div class="form-group required @if ($errors->has('items.'.$key.'.' .$id. '.'. $locale .'.name')) has-error @endif">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][name]', isset($item->translate($locale)->name) ? $item->translate($locale)->name : '', ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => 'true']) !!}
        </div>

        <div class="form-group">
            {!! Form::text('items['.$key.'][' . $id . '][' . $locale. '][title]', isset($item->translate($locale)->title) ? $item->translate($locale)->title : '', ['id' => 'items.'.$key.'.' . $id . '.' . $locale. '.title', 'placeholder' => trans('labels.link_title'), 'class' => 'form-control input-sm']) !!}
        </div>
    </div>
@endif