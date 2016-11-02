@if (count($list))
    <div class="box-body table-responsive no-padding">
        <div class="margin-bottom-10">
            <div class="col-sm-12 text-right">
                <a class="download btn btn-flat btn-success btn-sm" target="_blank"
                   href="{!! route('admin.packaging.download', ['deliveries', $year, $week]) !!}">
                    @lang('labels.download_xlsx_file')
                </a>
            </div>

            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered deliveries-packaging">
            <tbody>

            @foreach($list as $day => $orders)
                <tr class="day-title">
                    <th colspan="9">{!! $day !!} ({!! day_of_week($day, 'd-m-Y') !!})</th>
                </tr>
                <tr class="title">
                    <th class="text-center">
                        @lang('labels.id')
                    </th>
                    <th class="col-sm-2">
                        @lang('labels.user')
                    </th>
                    <th class="col-sm-1">
                        @lang('labels.time')
                    </th>
                    <th class="col-sm-2">
                        @lang('labels.address')
                    </th>
                    <th class="col-sm-1">
                        @lang('labels.phone')
                    </th>
                    <th class="col-sm-2">
                        @lang('labels.comment')
                    </th>
                    <th class="col-sm-2">
                        @lang('labels.baskets')
                    </th>
                    <th class="col-sm-1">
                        @lang('labels.places')
                    </th>
                    <th class="col-sm-1 text-center">
                        @lang('labels.payment')
                    </th>
                </tr>

                @php($i = 1)
                @foreach($orders as $order)
                    <tr>
                        <td class="text-center">
                            {!! $i !!}
                        </td>
                        <td>
                            {!! $order->getUserFullName() !!}
                            [#{!! $order->user->id !!}]
                            ({!! $order->user->orders()->finished()->count() + $order->user->old_site_orders_count !!})
                        </td>
                        <td>
                            {!! $order->delivery_time !!}
                        </td>
                        <td>
                            {!! $order->getFullAddress() !!}
                        </td>
                        <td>
                            {!! $order->getPhones() !!}
                        </td>
                        <td>
                            {!! $order->comment !!}
                        </td>
                        <td>
                            @lang('labels.main_basket'): {!! $order->main_basket->getName() !!}<br>
                            @if ($order->additional_baskets->count())
                                @lang('labels.additional_baskets'):
                                @foreach($order->additional_baskets as $basket)
                                    {!! $basket->getName() !!},
                                @endforeach
                            @endif
                        </td>
                        <td class="text-center">
                            {!! $order->getPlacesCount() !!}
                        </td>
                        <td class="text-center">
                            @if ($order->paymentMethod('cash'))
                                {!! $order->total !!} {!! $currency !!}
                            @endif
                        </td>
                    </tr>
                    @php($i++)
                @endforeach

                <tr><td colspan="9"></td></tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
@else
    <p class="help-block text-center">
        @lang('messages.no orders')
    </p>
@endif