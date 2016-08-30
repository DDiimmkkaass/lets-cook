<div class="row form-group">
    <div class="col-xs-12 col-sm-4 col-md-2">
        <div class="input-group bootstrap-timepicker timepicker">
            {!! Form::text('value', null, ['id' => 'value', 'placeholder' => '00:00', 'required' => true, 'class' => 'form-control input-sm timepicker inputmask-timepicker']) !!}

            <span class="input-group-addon pointer">
                <i class="timepicker-icon fa fa-clock-o" aria-hidden="true"></i>
            </span>
        </div>
    </div>
</div>