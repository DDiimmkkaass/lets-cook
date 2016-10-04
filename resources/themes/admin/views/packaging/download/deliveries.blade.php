<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered deliveries-packaging">
            <tbody>
            @php($total = 0)
            @foreach($list as $day => $orders)
                <tr style="height: 25px;">
                    <th style="background-color: #3a9a18; height: 20px; vertical-align: bottom" colspan="8">{!! $day !!}
                        ({!! day_of_week($day, 'd-m-Y') !!})
                    </th>
                    <th style="background-color: #d6b505; vertical-align: bottom" colspan="2">
                        <div style="text-align: center;">@lang('labels.filled_by_the_user')</div>
                    </th>
                </tr>
                <tr style="font-size: 14px; height: 20px">
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

                @php($i = 1)
                @foreach($orders as $order)
                    @php($heigth = 15)
                    @php($baskets = '<div>'.$order->main_basket->getName().'</div>')
                    @if ($order->additional_baskets->count())
                        @foreach($order->additional_baskets as $basket)
                            @php($baskets .= '<br><div>'.$basket->getName().',</div>')
                            @php($heigth += 15)
                        @endforeach
                    @endif

                    @php($heigth = max($heigth, 30))

                    @php($background = $i % 2 == 0 ? ' background-color: #dddddd; ' : '')
                    <tr style="height: {!! $heigth !!}px">
                        <td style="text-align: center; {!! $background !!}">
                            {!! $i !!}
                        </td>
                        <td style="width: 40px; {!! $background !!}">
                            {!! $order->getUserFullName() !!}
                            [{!! $order->user->id !!}]
                            ({!! $order->user->orders()->finished()->count() !!})
                        </td>
                        <td style="text-align: center; {!! $background !!}">
                            {!! $order->delivery_time !!}
                        </td>
                        <td style="width: 60px; {!! $background !!}">
                            {!! $order->getFullAddress() !!}
                        </td>
                        <td style="width: 25px; text-align: left; {!! $background !!}">
                            {!! $order->getPhones() !!}
                        </td>
                        <td style="width: 30px; {!! $background !!}">
                            {!! $order->comment !!}
                        </td>
                        <td style="width: 30px; {!! $background !!}">
                            {!! $baskets !!}
                        </td>
                        <td style="width: 15px; text-align: center; {!! $background !!}">
                            {!! $order->getPlacesCount() !!} {!! $heigth !!}
                        </td>
                        <td style="width: 15px; text-align: center; color: #ff0000; {!! $background !!}">
                            @if ($order->paymentMethod('cash'))
                                {!! $order->total !!} {!! $currency !!}

                                @php($total += $order->total)
                            @endif
                        </td>
                        <td style="width: 15px; {!! $background !!}">

                        </td>
                    </tr>
                    @php($i++)
                @endforeach

                <tr>
                    <td colspan="10"></td>
                </tr>
                <tr>
                    <td colspan="10"></td>
                </tr>
                <tr>
                    <td colspan="10"></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="10"></td>
            </tr>

            <tr style="height: 20px">
                <td colspan="6" style="text-align: right">@lang('labels.approve')</td>
                <td colspan="2" style="text-align: right">@lang('labels.ceo')</td>
                <td colspan="2"></td>
            </tr>
            <tr style="height: 20px">
                <td colspan="6"></td>
                <td colspan="2" style="text-align: right">{!! variable('ceo') !!}</td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td colspan="10"></td>
            </tr>
            <tr>
                <td colspan="10"></td>
            </tr>
            <tr>
                <td colspan="10"></td>
            </tr>

            <tr style="height: 30px;">
                <td colspan="6"></td>
                <td colspan="4" style="text-align: right; color: #ff0000; font-size: 17px; border: 1px solid #000000">
                    @lang('labels.cash_total'): {!! $total !!} {!! $currency !!}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
@endif