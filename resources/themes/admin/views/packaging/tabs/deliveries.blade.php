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
                    <th colspan="8">{!! $day !!} ({!! day_of_week($day, 'd-m-Y') !!})</th>
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
                    <th class="col-sm-3">
                        @lang('labels.baskets')
                    </th>
                    <th class="col-sm-1 text-center">
                        @lang('labels.payment')
                    </th>
                </tr>

                @foreach($orders as $key => $order)
                    <tr>
                        <td class="text-center">
                            {!! $key + 1 !!}
                        </td>
                        <td>
                            {!! $order->getUserFullName() !!}
                            [{!! $order->user->id !!}]
                            ({!! $order->user->orders()->finished()->count() !!})
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
                            @lang('labels.main_basket'): {!! $order->getMainBasketName(true) !!}<br>
                            @if (count($order->baskets))
                                @lang('labels.additional_baskets'):
                                @foreach($order->baskets as $basket)
                                    {!! $basket->name !!},
                                @endforeach
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($order->paymentMethod('cash'))
                                {!! $order->total !!} {!! $currency !!}
                            @endif
                        </td>
                    </tr>
                @endforeach

                <tr><td colspan="8"></td></tr>
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