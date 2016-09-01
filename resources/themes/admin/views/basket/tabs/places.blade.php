<div class="box-body table-responsive no-padding">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th class="text-center">@lang('labels.portions')</th>
                @foreach(range(1, config('weekly_menu.menu_days')) as $day)
                    <th class="text-center">{!! $day . ' ' . trans_choice('labels.count_of_days', $day) !!}</th>
                @endforeach
            </tr>

            @foreach(config('recipe.available_portions') as $portion)
                <tr>
                    <td class="text-center">
                        <label class="control-label margin-top-5">{!! $portion !!}</label>
                    </td>
                    @foreach(range(1, config('weekly_menu.menu_days')) as $day)
                        <td>
                            <div class="form-group required @if ($errors->has('places.'.$portion.'.'.$day)) has-error @endif">
                                <div class="col-sm-6 col-sm-push-3">
                                    {!! Form::select('places['.$portion.']['.$day.']', $places, old('places.'.$portion.'.'.$day) ?: (isset($model->places[$portion][$day]) ? $model->places[$portion][$day] : 0), ['id' => "places_".$portion."_".$day, 'class' => 'form-control select2 input-sm margin-top-5', 'aria-hidden' => 'true', 'required' => true]) !!}
                                </div>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>