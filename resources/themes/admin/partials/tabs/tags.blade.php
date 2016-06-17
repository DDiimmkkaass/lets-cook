<div class="form-group">
    {!! Form::label(isset($array_name) ? $array_name : 'tags', trans('labels.tags'), ['class' => "control-label col-xs-4 col-sm-3 col-md-2"]) !!}

    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
        {!! Form::select((isset($array_name) ? $array_name : 'tags').'[]', isset($tags_array) ? $tags_array : [], isset($selected_tags) ? $selected_tags : [], ['class' => 'select2', 'multiple' => 'multiple']) !!}

        {!! $errors->first((isset($array_name) ? $array_name : 'tags').'[]', '<span class="error">:message</span>') !!}
    </div>
</div>