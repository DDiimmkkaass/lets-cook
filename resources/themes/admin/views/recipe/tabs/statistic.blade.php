<div class="col-sm-5">
    <h4>@lang('labels.statistic_of_orders')</h4>
    @if (count($orders))
        <div class="box-body table-responsive no-padding col-sm-12">
            <table class="table table-hover table-bordered">
                <tbody>
                <tr>
                    <th class="col-sm-6 text-center">{!! trans('labels.week') !!}</th>
                    <th class="col-sm-6 text-center">{!! trans('labels.count_of_orders') !!}</th>
                </tr>

                @foreach($orders as $week => $count)
                    <tr>
                        <td class="text-center">{!! $week !!}</td>
                        <td class="text-center">{!! $count !!}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="clearfix"></div>
    @else
        <p class="help-block text-center">
            @lang('messages.this recipe has not ordered any time')
        </p>
    @endif
</div>
<div class="col-sm-5 col-sm-push-2">
    <h4>@lang('labels.statistic_of_uses')</h4>
    @if (count($uses))
        <div class="box-body table-responsive no-padding col-sm-12">
            <table class="table table-hover table-bordered">
                <tbody>
                <tr>
                    <th class="col-sm-6 text-center">{!! trans('labels.week') !!}, {!! trans('labels.year') !!}</th>
                </tr>

                @foreach($uses as $week => $name)
                    <tr>
                        <td class="text-center">
                            <a href="{!! route('admin.weekly_menu.show', $week) !!}">{!! $name !!}</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="clearfix"></div>
    @else
        <p class="help-block text-center">
            @lang('messages.this recipe has not uses in any week menu')
        </p>
    @endif
</div>

<div class="clearfix"></div>