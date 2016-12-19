<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered deliveries-packaging excel-table">
            <tbody>
            @php($user_width = 15)
            @php($address_width = 15)
            @php($comment_width = 24)
            @php($basket_width = 10)
            @php($days_count = count($list))
            @php($d = 1)

            @foreach($list as $day => $orders)
                @php($orders_count = count($orders))

                <tr style="height: 25px;">
                    <th style="background-color: #3a9a18; height: 20px; vertical-align: bottom" colspan="8">{!! $day !!}
                        ({!! day_of_week($day, 'd-m-Y') !!})
                    </th>
                    <th style="background-color: #d6b505; vertical-align: bottom" colspan="2">
                        <div style="text-align: center;">@lang('labels.filled_by_the_user')</div>
                    </th>
                </tr>
                <tr style="font-size: 14px; height: 20px">
                    <th style="width: 3px; text-align: center;">
                        @lang('labels.id')
                    </th>
                    <th>
                        @lang('labels.user')
                    </th>
                    <th style="width: 8px; text-align: center">
                        @lang('labels.time')
                    </th>
                    <th>
                        @lang('labels.address')
                    </th>
                    <th style="width: 19px; text-align: center">
                        @lang('labels.phone')
                    </th>
                    <th>
                        @lang('labels.order_comments')
                    </th>
                    <th>
                        @lang('labels.baskets')
                    </th>
                    <th style="width: 7px; text-align: center">
                        @lang('labels.places')
                    </th>
                    <th style="width: 12px; text-align: center">
                        @lang('labels.payment')
                    </th>
                    <th style="width: 12px;">
                        @lang('labels.autograph')
                    </th>
                </tr>

                @php($i = 1)
                @foreach($orders as $order)
                    @php($height = 15)
                    @php($baskets = '<div>'.$order->main_basket->getName().'</div>')
                    @php($basket_width = max(cell_width($order->main_basket->getName(), false, 2), $basket_width))
                    @if ($order->additional_baskets->count())
                        @foreach($order->additional_baskets as $basket)
                            @php($baskets .= '<br><div>'.$basket->getName().',</div>')
                            @php($basket_width = max(cell_width($basket->getName(), false, 2), $basket_width))
                            @php($height += 15)
                        @endforeach
                    @endif
                    @if ($order->ingredients->count())
                        @foreach($order->ingredients as $ingredient)
                            @php($ingredient_name = $ingredient->getName().'('.$ingredient->count.$ingredient->getSaleUnit().')')
                            @php($baskets .= '<br><div>'.$ingredient_name.',</div>')
                            @php($basket_width = max(cell_width($ingredient_name, false, 2), $basket_width))
                            @php($height += 15)
                        @endforeach
                    @endif

                    @php($height = max($height, 30))

                    @php($background = $i % 2 == 0 ? ' background-color: #dddddd; ' : '')
                    <tr style="height: {!! $height !!}px; vertical-align: top">
                        <td style="text-align: center; {!! $background !!}">
                            {!! $i !!}
                        </td>
                        @php($user_name = $order->getUserFullName().' [#'.$order->user->id.'] ('.($order->user->orders()->finished()->count() + $order->user->old_site_orders_count).')')
                        @php($user_width = max(cell_width($user_name), $user_width))
                        <td style="@if ($days_count == $d && $orders_count == $i) width: {!! $user_width - $user_width * 0.4 !!}px; @endif {!! $background !!}">
                            {!! $user_name !!}
                        </td>
                        <td style="text-align: center; {!! $background !!}">
                            {!! $order->delivery_time !!}
                        </td>
                        @php($address = $order->getFullAddress())
                        @php($address_width = max(cell_width($address), $address_width))
                        <td style="@if ($days_count == $d && $orders_count == $i) width: {!! $address_width - $address_width * 0.4 !!}px; @endif {!! $background !!}">
                            {!! $address !!}
                        </td>
                        <td style="text-align: left; {!! $background !!}">
                            {!! $order->getPhones() !!}
                        </td>
                        @php($comment_width = max(cell_width($order->comment), $comment_width))
                        <td style="@if ($days_count == $d && $orders_count == $i) width: {!! $comment_width - $comment_width * 0.4 !!}px; @endif {!! $background !!}">
                            {!! $order->comment !!}
                        </td>
                        <td style="@if ($days_count == $d && $orders_count == $i) width: {!! $basket_width !!}px; @endif {!! $background !!}">
                            {!! $baskets !!}
                        </td>
                        <td style="text-align: center; {!! $background !!}">
                            {!! $order->getPlacesCount() !!}
                        </td>
                        <td style="text-align: center; color: #ff0000; {!! $background !!}">
                            @if ($order->paymentMethod('cash'))
                                {!! $order->total !!}
                            @endif
                        </td>
                        <td style="{!! $background !!}">

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

                @php($d++)
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
                <td colspan="2" style="text-align: right; color: #ff0000; font-size: 17px; border: 1px solid #000000;">
                    @lang('labels.cash_total'):
                </td>
                <td style="text-align: right; color: #ff0000; font-size: 17px; border: 1px solid #000000"></td>
                <td style="text-align: left; color: #ff0000; font-size: 17px; border: 1px solid #000000">
                    {!! $currency !!}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
@endif

</body>
</html>