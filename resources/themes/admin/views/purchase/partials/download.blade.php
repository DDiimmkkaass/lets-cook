<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

@php($baskets_count = count($baskets))
@php($ingredients_count = count($list))

<div class="box-body table-responsive no-padding excel-table">
    <table>
        <tbody>

        <tr style="height: 30px">
            <td colspan="7">
                <h3 style="background: #92D050; text-align: center; display: block">
                    <b>
                        {!! mb_strtoupper(trans('labels.week')) !!} {!! $week !!}
                        @if ($supplier_name) {!! $supplier_name !!} @endif
                    </b>
                </h3>
            </td>

            @foreach($baskets as $basket)
                <th style="width: {!! max(cell_width($basket['name'], false, false), 10) !!}px; background-color: #92D050;">
                    <div style="text-align: center;">
                        {!! $basket['name'] !!}
                    </div>
                </th>
            @endforeach
        </tr>

        <tr>
            <th style="width: 40px; background-color: #FCD5B4; text-align: center;">@lang('labels.ingredient')</th>
            <th style="width: 11px; background-color: #FCD5B4; text-align: center;">@lang('labels.in_stock')</th>
            <th style="width: 10px; background-color: #FCD5B4; text-align: center;">@lang('labels.unit_short')</th>
            <th style="width: 11px; background-color: #FCD5B4; text-align: center;">@lang('labels.price') {!! $currency !!}</th>
            <th style="width: 12px; background-color: #FCD5B4; text-align: center;">@lang('labels.count')</th>
            <th style="width: 15px; background-color: #FCD5B4; text-align: center;">@lang('labels.category')</th>
            <th style="width: 20px; background-color: #FCD5B4; text-align: center;">@lang('labels.supplier')</th>

            @foreach($baskets as $basket)
                <th style="background-color: #FCD5B4; text-align: center;">
                    {!! $basket['count'] !!}
                </th>
            @endforeach
        </tr>

        @php($ingredients_width = 40)
        @foreach($list as $key => $ingredient)
            @php($ingredients_width = max(cell_width($ingredient->ingredient->name), $ingredients_width))
            <tr>
                <td>{!! $ingredient->ingredient->name !!}</td>
                <td style="text-align: center">@if ($ingredient->in_stock) @lang('labels.yes') @endif</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient->getUnitName() !!}</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient->price !!}</td>
                <td style="text-align: right;">
                    @if (isset($ordered[$ingredient->ingredient_id.'_'.$ingredient->type]))
                        {!!  $ordered[$ingredient->ingredient_id.'_'.$ingredient->type]['count']  !!}
                    @else
                        {!! $ingredient->count !!}
                    @endif
                </td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient->ingredient->category->name !!}</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient->ingredient->supplier->name !!}</td>

                @foreach($baskets as $basket)
                    <td style="text-align: center">
                        @if (isset($basket['ingredients'][$ingredient->ingredient_id]))
                            {!! $basket['ingredients'][$ingredient->ingredient_id] !!}
                        @endif
                    </td>
                @endforeach
            </tr>

            @if ($ingredients_count - 1 == $key || $ingredient->ingredient->supplier_id != $list[$key + 1]->ingredient->supplier_id)
                <tr>
                    <th @if ($ingredients_count - 1 == $key) style="width: {!! $ingredients_width !!}px" @endif ></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">
                        {!! $ingredient->ingredient->supplier->name !!} @lang('labels.result')
                    </th>

                    @for ($i = 0; $i < $baskets_count; $i++)
                        <th></th>
                    @endfor
                </tr>
            @endif
        @endforeach

        </tbody>
    </table>
</div>

</body>
</html>