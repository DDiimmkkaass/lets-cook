<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered deliveries-packaging">
            <tbody>

            @foreach($list as $day => $orders)
                <tr>
                    <th style="background-color: #3a9a18; height: 20px; vertical-align: bottom" colspan="8">{!! $day !!} ({!! day_of_week($day, 'd-m-Y') !!})</th>
                    <th style="background-color: #d6b505; vertical-align: bottom" colspan="2">@lang('labels.filled_by_the_user')</th>
                </tr>
                <tr style="font-size: 14px">
                    <th style="text-align: center">
                        @lang('labels.id')
                    </th>
                    <th>
                        @lang('labels.user')
                    </th>
                    <th>
                        @lang('labels.time')
                    </th>
                    <th>
                        @lang('labels.address')
                    </th>
                    <th>
                        @lang('labels.phone')
                    </th>
                    <th>
                        @lang('labels.comment')
                    </th>
                    <th>
                        @lang('labels.baskets')
                    </th>
                    <th style="text-align: center">
                        @lang('labels.places')
                    </th>
                    <th style="text-align: center">
                        @lang('labels.payment')
                    </th>
                    <th>
                        @lang('labels.autograph')
                    </th>
                </tr>

                @foreach($orders as $key => $order)
                    @php($background = $key % 2 == 0 ? ' background-color: #dddddd; ' : '')
                    <tr>
                        <td style="text-align: center; {!! $background !!}">
                            {!! $key + 1 !!}
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->getUserFullName() !!}
                            [{!! $order->user->id !!}]
                            ({!! $order->user->orders()->finished()->count() !!})
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->delivery_time !!}
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->getFullAddress() !!}
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->getPhones() !!}
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->comment !!}
                        </td>
                        <td style="{!! $background !!}">
                            {!! $order->main_basket->getName() !!}<br>
                            @if ($order->additional_baskets->count())
                                @foreach($order->additional_baskets as $basket)
                                    {!! $basket->getName() !!},
                                @endforeach
                            @endif
                        </td>
                        <td style="text-align: center; {!! $background !!}">
                            {!! $order->getPlacesCount() !!}
                        </td>
                        <td style="text-align: center; color: #ff0000; {!! $background !!}">
                            @if ($order->paymentMethod('cash'))
                                {!! $order->total !!} {!! $currency !!}
                            @endif
                        </td>
                        <td style="{!! $background !!}">

                        </td>
                    </tr>
                @endforeach

                <tr><td colspan="10"></td></tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endif