<div class="box-body table-responsive no-padding recipe-files-table">
    <table class="table table-hover table-bordered duplication">
        <tbody>
        <tr>
            <th class="col-sm-5">{!! trans('labels.file') !!}<span class="required">*</span></th>
            <th class="col-sm-5">{!! trans('labels.name') !!}<span class="required">*</span></th>
            <th class="col-sm-1">{!! trans('labels.position') !!}</th>
            <th class="col-sm-1 text-center">{!! trans('labels.delete') !!}</th>
        </tr>

        @if (count($model->files) && !isset($copy))
            @foreach($model->files as $file)
                <tr class="duplication-row">
                    <td>
                        <div class="form-group required @if ($errors->has('files.old.' .$file->id. '.src')) has-error @endif">
                            {!! Form::elfinderInput('files[old][' .$file->id. '][src]', $file->src, ['required' => true, 'readonly' => true]) !!}

                            {!! $errors->first('files.old.' .$file->id. '.src', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div class="form-group required @if ($errors->has('files.old.' .$file->id. '.name')) has-error @endif">
                                {!! Form::text('files[old][' . $file->id . '][name]', $file->name, ['id' => 'files.old.' . $file->id . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                {!! $errors->first('files.old.' .$file->id. '.name', '<p class="help-block error">:message</p>') !!}
                            </div>

                            <div class="form-group @if ($errors->has('files.old.' .$file->id. '.description')) has-error @endif">
                                {!! Form::textarea('files[old][' . $file->id . '][description]', $file->description, ['id' => 'files.old.' . $file->id . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm']) !!}

                                {!! $errors->first('files.old.' .$file->id. '.description', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group @if ($errors->has('files.old.' .$file->id. '.position')) has-error @endif">
                            {!! Form::text('files[old][' .$file->id. '][position]', $file->position, ['id' => 'files.old.' .$file->id. '.position', 'class' => 'form-control input-sm']) !!}

                            {!! $errors->first('files.old.' .$file->id. '.position', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </td>
                    <td class="coll-actions text-center">
                        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $file->id !!}"
                           data-name="files[remove][]"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count(old('files.new')))
            @foreach(old('files.new') as $file_key => $file)
                @if ($file_key !== 'replaseme')
                    <tr class="duplication-row">
                        <td>
                            <div class="form-group required @if ($errors->has('files.new.' .$file_key. '.src')) has-error @endif">
                                {!! Form::elfinderInput('files[new][' .$file_key. '][src]', $file['src'], ['required' => true, 'readonly' => true]) !!}

                                {!! Form::hidden('files[new][' .$file_key. '][type]', $file['type']) !!}

                                {!! $errors->first('files.new.' .$file_key. '.src', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="form-group required @if ($errors->has('files.new.' .$file_key. '.name')) has-error @endif">
                                    {!! Form::text('files[new][' . $file_key . '][name]', $file['name'], ['id' => 'files.new.' . $file_key . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}

                                    {!! $errors->first('files.new.' .$file_key. '.name', '<p class="help-block error">:message</p>') !!}
                                </div>

                                <div class="form-group @if ($errors->has('files.new.' .$file_key. '.description')) has-error @endif">
                                    {!! Form::textarea('files[new][' . $file_key . '][description]', $file['description'], ['id' => 'files.new.' . $file_key . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm']) !!}

                                    {!! $errors->first('files.new.' .$file_key. '.description', '<p class="help-block error">:message</p>') !!}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group @if ($errors->has('files.new.' .$file_key. '.position')) has-error @endif">
                                {!! Form::text('files[new][' .$file_key. '][position]', $file['position'], ['id' => 'files.new.' .$file_key. '.position', 'class' => 'form-control input-sm']) !!}

                                {!! $errors->first('files.new.' .$file_key. '.position', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </td>
                        <td class="coll-actions text-center">
                            <a class="btn btn-flat btn-danger btn-xs action destroy"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        @if (isset($copy) && !count(old('files.new')))
            @foreach($model->files as $file)
                <tr class="duplication-row">
                    <td>
                        <div class="form-group required">
                            {!! Form::elfinderInput('files[new][' .$file->id. '][src]', $file->src, ['required' => true, 'readonly' => true]) !!}

                            {!! Form::hidden('files[new][' .$file->id. '][type]', 3) !!}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div class="form-group required">
                                {!! Form::text('files[new][' . $file->id . '][name]', $file->name, ['id' => 'files.new.' . $file->id . '.name', 'placeholder' => trans('labels.name'), 'class' => 'form-control input-sm', 'required' => true]) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::textarea('files[new][' . $file->id . '][description]', $file->description, ['id' => 'files.new.' . $file->id . '.description', 'placeholder' => trans('labels.description'), 'rows' => 5, 'class' => 'form-control input-sm']) !!}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {!! Form::text('files[new][' .$file->id. '][position]', $file->position, ['id' => 'files.new.' .$file->id. '.position', 'class' => 'form-control input-sm']) !!}
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
                <div class="form-group @if ($errors->has('files')) has-error @endif">
                    {!! $errors->first('files', '<div class="margin-bottom-25 text-center position-relative error-block"><p class="help-block error">:message</p></div>') !!}

                    <a name="@lang('labels.add_one_more')" class="btn btn-flat btn-primary btn-sm action create">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>
                </div>
            </td>
        </tr>

        <tr class="duplication-row duplicate">
            <td>
                <div class="form-group">
                    {!! Form::elfinderInput('', '', ['data-name' => 'files[new][replaseme][src]', 'data-required' => true, 'readonly' => true]) !!}

                    <input type="hidden" class="form-control hidden" data-name="files[new][replaseme][type]" value="3">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <div class="form-group required">
                        <input data-name="files[new][replaseme][name]"
                               placeholder="@lang('labels.name')"
                               class="form-control input-sm"
                               data-required="required">
                    </div>

                    <div class="form-group">
                        <textarea data-name="files[new][replaseme][description]"
                                  placeholder="@lang('labels.description')"
                                  rows="5"
                                  class="form-control input-sm"></textarea>
                    </div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input data-name="files[new][replaseme][position]" value="0"
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