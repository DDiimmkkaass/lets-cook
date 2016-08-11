@if (count($orders))
    <div class="box-body table-responsive no-padding col-sm-3">
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