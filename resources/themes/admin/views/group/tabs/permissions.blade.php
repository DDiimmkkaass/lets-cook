@foreach ($permissions as $key => $value)
    <ul class="form-group">
        <li>
            <div class="row">
                <?php $pt = empty($path) ? $key : $path . '_' . $key; ?>

                <label class="label btn btn-flat">{!! $key !!}</label>

                @if (is_array($value))
                    @include('group.tabs.permissions', ['permissions' => $value, 'path' => $pt])
                @else
                    <div class="col-md-4 {!! $value ? 'text-green' : 'text-red' !!}">
                        {!! Form::select('permissions['.$pt.']', ['1' => trans('labels.allow'), '0' => trans('labels.deny')], $value, ['class' => 'form-control select2', 'aria-hidden' => 'true']) !!}
                    </div>

                    @if (isset($permissions_description[$pt]))
                        <div class="row">
                            <div class="col-md-12">
                                <p class="help-block">
                                    ({!! $permissions_description[$pt] !!})
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </li>
    </ul>
@endforeach
