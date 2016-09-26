<div class="row form-group">
    <div class="col-xs-12 col-sm-4 col-md-2">
        {!! Form::select('value', ['0' => trans('labels.status_off'), '1' => trans('labels.status_on')], null, ['id' => 'value', 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}
    </div>
</div>