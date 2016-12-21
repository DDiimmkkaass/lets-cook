<div class="row form-group">
    <div class="col-xs-12 col-sm-4 col-md-2">
        {!! Form::select('value', ['percentage' => trans('labels.discount_discount_type_percentage'), 'absolute' => trans('labels.discount_discount_type_absolute')], null, ['id' => 'value', 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true']) !!}
    </div>
</div>