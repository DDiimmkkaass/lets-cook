@if (count($model->fields))
    @foreach($model->fields as $field)
        <div class="form-group field-row">
            {!! Form::label('fields_old_' . $field->id, trans('labels.' . $field->getStringType()), ['class' => 'col-md-3 control-label']) !!}

            {!! Form::hidden('fields[old]['.$field->id.'][type]', $field->type) !!}

            <div class="col-md-3">
                {!! Form::text('fields[old]['.$field->id.'][value]', $field->value, ['class' => 'form-control input-sm input-mask data-mask-'.$field->type]) !!}
            </div>
            <div class="col-md-1">
                <label data-id="{!! $field->id !!}" data-name="fields[remove][]" class="remove-field-button control-label red pointer">
                    <i class="fa fa-remove"></i>
                </label>
            </div>
        </div>
    @endforeach
@endif

@if (count(Request::old('fields.new')))
    @foreach(Request::old('fields.new') as $key => $field)
        @if (!empty($field['type']))
            <div class="form-group field-row">
                <div class="col-md-3">
                    {!! Form::select('fields[new]['.$key.'][type]', $field_types, $field['type'], ['class' => 'form-control select2', 'aria-hidden' => 'true']) !!}
                </div>

                <div class="col-md-3">
                    {!! Form::text('fields[new]['.$key.'][value]', $field['value'], ['class' => 'form-control input-sm input-mask inputmask-'.$field['type']]) !!}
                </div>
                <div class="col-md-1">
                    <label data-id="" class="remove-field-button control-label red pointer">
                        <i class="fa fa-remove"></i>
                    </label>
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="form-group add-field-button-block">
    <div class="col-md-6">
        <div class="btn btn-flat btn-sm btn-primary add-field-button">
            @lang('labels.add_new_field')

            <div class="duplicate form-group field-row">
                <div class="col-md-3">
                    {!! Form::select('fields[new][replaceme][type]', $field_types, null, ['class' => 'form-control select2 ',  'aria-hidden' => 'true']) !!}
                </div>

                <div class="col-md-3">
                    {!! Form::text('fields[new][replaceme][value]', '', ['class' => 'form-control input-sm input-mask']) !!}
                </div>

                <div class="col-md-1">
                    <label data-id="" class="remove-field-button control-label red pointer">
                        <i class="fa fa-remove"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>