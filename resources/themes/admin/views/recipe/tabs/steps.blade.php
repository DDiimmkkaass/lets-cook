<div class="form-group required @if ($errors->has('cooking_time')) has-error @endif">
    {!! Form::label('cooking_time', trans('labels.cooking_time'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-1 with-after-helper units-minutes">
        {!! Form::text('cooking_time', $model->cooking_time ?: 0, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('cooking_time', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('portions')) has-error @endif">
    {!! Form::label('portions', trans('labels.portions'), ['class' => 'control-label col-xs-12 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-2 col-md-1">
        {!! Form::select('portions', $portions, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('portions', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('helpful_hints')) has-error @endif">
    {!! Form::label('helpful_hints', trans('labels.helpful_hints'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('helpful_hints', null, ['id' => 'helpful_hints', 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('helpful_hints', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'helpful_hints'])

<div class="form-group @if ($errors->has('home_equipment')) has-error @endif">
    {!! Form::label('home_equipment', trans('labels.home_equipment'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea('home_equipment', null, ['id' => 'home_equipment', 'rows' => '3', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('home_equipment', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => 'home_equipment'])

<div class="box-body table-responsive no-padding recipe-steps-table">
    <table class="table table-hover table-bordered duplication">
        <tbody>
        <tr>
            <th class="col-md-3">{!! trans('labels.image') !!}</th>
            <th>{!! trans('labels.name') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.position') !!}<span class="required">*</span></th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->steps) && !isset($copy))
            @foreach($model->steps as $step)
                <tr class="duplication-row">
                    <td>
                        <div class="form-group required @if ($errors->has('steps.old.' .$step->id. '.image')) has-error @endif">
                            {!! Form::imageInput('steps[old][' .$step->id. '][image]', $step->image, ['width' => 100, 'height' => 100, 'required' => true]) !!}

                            {!! $errors->first('steps.old.' .$step->id. '.image', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div class="form-group required @if ($errors->has('steps.old.' .$step->id. '.name')) has-error @endif">
                                {!! Form::text('steps[old][' . $step->id . '][name]', $step->name, ['id' => 'steps.old.' . $step->id . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                {!! $errors->first('steps.old.' .$step->id. '.name', '<p class="help-block error">:message</p>') !!}
                            </div>

                            <div class="form-group required @if ($errors->has('steps.old.' .$step->id. '.description')) has-error @endif">
                                {!! Form::textarea('steps[old][' . $step->id . '][description]', $step->description, ['id' => 'steps.old.' . $step->id . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm', 'required' => true]) !!}

                                {!! $errors->first('steps.old.' .$step->id. '.description', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group required @if ($errors->has('steps.old.' .$step->id. '.position')) has-error @endif">
                            {!! Form::text('steps[old][' .$step->id. '][position]', $step->position, ['id' => 'steps.old.' .$step->id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}

                            {!! $errors->first('steps.old.' .$step->id. '.position', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </td>
                    <td class="coll-actions text-center">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $step->id !!}"
                           data-name="steps[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('steps.new')))
            @foreach(old('steps.new') as $step_key => $step)
                @if ($step_key !== 'replaseme')
                    <tr class="duplication-row">
                        <td>
                            <div class="form-group required @if ($errors->has('steps.new.' .$step_key. '.image')) has-error @endif">
                                {!! Form::imageInput('steps[new][' .$step_key. '][image]', $step['image'], ['width' => 100, 'height' => 100, 'required' => true]) !!}

                                {!! $errors->first('steps.new.' .$step_key. '.image', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="form-group required @if ($errors->has('steps.new.' .$step_key. '.name')) has-error @endif">
                                    {!! Form::text('steps[new][' . $step_key . '][name]', $step['name'], ['id' => 'steps.new.' . $step_key . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                    {!! $errors->first('steps.new.' .$step_key. '.name', '<p class="help-block error">:message</p>') !!}
                                </div>

                                <div class="form-group required @if ($errors->has('steps.new.' .$step_key. '.description')) has-error @endif">
                                    {!! Form::textarea('steps[new][' . $step_key . '][description]', $step['description'], ['id' => 'steps.new.' . $step_key . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm', 'required' => true]) !!}

                                    {!! $errors->first('steps.new.' .$step_key. '.description', '<p class="help-block error">:message</p>') !!}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('steps.new.' .$step_key. '.position')) has-error @endif">
                                {!! Form::text('steps[new][' .$step_key. '][position]', $step['position'], ['id' => 'steps.new.' .$step_key. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}

                                {!! $errors->first('steps.new.' .$step_key. '.position', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </td>
                        <td class="coll-actions text-center">
                            <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        @if (isset($copy) && !count(old('steps.new')))
            @foreach($model->steps as $step)
                <tr class="duplication-row">
                    <td>
                        <div class="form-group required">
                            {!! Form::imageInput('steps[new][' .$step->id. '][image]', $step->image, ['width' => 100, 'height' => 100, 'required' => true]) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div class="form-group required">
                                {!! Form::text('steps[new][' . $step->id . '][name]', $step->name, ['id' => 'steps.new.' . $step->id . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>

                            <div class="form-group required">
                                {!! Form::textarea('steps[new][' . $step->id . '][description]', $step->description, ['id' => 'steps.new.' . $step->id . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group required">
                            {!! Form::text('steps[new][' .$step->id. '][position]', $step->position, ['id' => 'steps.new.' .$step->id. '.position', 'class' => 'form-control input-sm', 'required' => true]) !!}
                        </div>
                    </td>
                    <td class="coll-actions text-center">
                        <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        <tr class="duplication-button">
            <td colspan="4" class="text-center">
                <div class="form-group @if ($errors->has('steps')) has-error @endif">
                    {!! $errors->first('steps', '<div class="margin-bottom-25 text-center position-relative error-block"><p class="help-block error">:message</p></div>') !!}

                    <a name="@lang('labels.add_one_more')" class="btn btn-flat btn-primary btn-sm action create">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>
                </div>
            </td>
        </tr>

        <tr class="duplication-row duplicate">
            <td>
                <div class="form-group required">
                    {!! Form::imageInput('', '', ['width' => 100, 'height' => 100, 'data-related-image' => 'stepsnewreplasemeimage', 'data-name' => 'steps[new][replaseme][image]', 'data-required' => 'required']) !!}
                </div>
            </td>
            <td>
                <div class="form-group">
                    <div class="form-group required">
                        <input data-name="steps[new][replaseme][name]"
                               placeholder="@lang('labels.name')"
                               class="form-control input-sm"
                               data-required="required">
                    </div>

                    <div class="form-group required">
                        <textarea data-name="steps[new][replaseme][description]"
                                  placeholder="@lang('labels.description')"
                                  rows="5"
                                  class="form-control input-sm"
                                  data-required="required"></textarea>
                    </div>
                </div>
            </td>
            <td>
                <div class="form-group required">
                    <input data-name="steps[new][replaseme][position]" value="0" data-required="required"
                           class="form-control input-sm">
                </div>
            </td>
            <td class="coll-actions text-center">
                <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
            </td>
        </tr>

        </tbody>
    </table>
</div>