<div class="box-body">
    @foreach($groups as $key => $group_name)
        <div class="form-group col-sm-12">
            <label class="checkbox-label" for="'groups[{!! $key !!}]'">
                {!! Form::checkbox('groups['.$key.']', $key,
                !empty($user_groups) ? in_array($key, $user_groups) :
                    Request::old('groups['.$key.']') ?: false,
                array('class' => 'square')) !!}

                <span class="title">{!! $group_name !!}</span>
            </label>
        </div>
    @endforeach

    @if ($errors->has('groups'))
        <div class="row has-error">
            <div class="col-xs-12">
                {!! $errors->first('groups', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    @endif
</div>
