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
                <th style="width: {!! max(cell_width($basket['name']), 10) !!}px; background-color: #92D050;">
                    <div style="text-align: center;">
                        {!! $basket['name'] !!}
                    </div>
                </th>
            @endforeach
        </tr>

        <tr style="height: 16px;">
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.ingredient')</th>
            <th style="width: 11px; background-color: #FCD5B4; text-align: center;">@lang('labels.in_stock')</th>
            <th style="width: 10px; background-color: #FCD5B4; text-align: center;">@lang('labels.unit_short')</th>
            <th style="width: 11px; background-color: #FCD5B4; text-align: center;">@lang('labels.price') {!! $currency !!}</th>
            <th style="width: 12px; background-color: #FCD5B4; text-align: center;">@lang('labels.count')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.category')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.supplier')</th>

            @foreach($baskets as $basket)
                <th style="background-color: #FCD5B4; text-align: center;">{!! $basket['count'] !!}</th>
            @endforeach
        </tr>

        @php($i = 0)
        @php($supplier_id = 0)
        @php($ingredients_width = 25)
        @php($category_width = 15)
        @php($supplier_width = 15)
        @foreach($list as $ingredient)
            @php($ingredients_width = max(cell_width($ingredient['name']), $ingredients_width))
            @if (($i > 0 && $ingredient['supplier_id'] != $supplier_id))
                <tr style="height: 16px;">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @php($supplier_width = max(cell_width($supplier_name.' '.trans('labels.result'), true, 2), $supplier_width))
                    <th style="text-align: center">
                        {!! $supplier_name !!} @lang('labels.result')
                    </th>

                    @for ($_i = 0; $_i < $baskets_count; $_i++)
                        <th></th>
                    @endfor
                </tr>
            @endif

            <tr style="height: 15px;">
                <td>{!! $ingredient['name'] !!}</td>
                <td style="text-align: center">@if ($ingredient['in_stock']) @lang('labels.yes') @endif</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['unit'] !!}</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['price'] !!}</td>
                <td style="text-align: right;">{!! $ingredient['count'] !!}</td>
                @php($category_width = max(cell_width($ingredient['category_name']), $category_width))
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['category_name'] !!}</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['supplier_name'] !!}</td>

                @foreach($baskets as $basket)
                    <td style="text-align: center">
                        @if (isset($basket['ingredients'][$ingredient['ingredient_id']]))
                            {!! $basket['ingredients'][$ingredient['ingredient_id']] !!}
                        @endif
                    </td>
                @endforeach
            </tr>

            @if ($ingredients_count - 1 == $i)
                <tr style="height: 16px;">
                    <th style="width: {!! $ingredients_width !!}px"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="width: {!! $category_width !!}px"></th>
                    @php($supplier_width = max(cell_width($supplier_name.' '.trans('labels.result'), true, 2), $supplier_width))
                    <th style="width: {!! $supplier_width !!}px; text-align: center">
                        {!! $ingredient['supplier_name'] !!} @lang('labels.result')
                    </th>

                    @for ($_i = 0; $_i < $baskets_count; $_i++)
                        <th></th>
                    @endfor
                </tr>
            @endif

            @php($i++)
            @php($supplier_id = $ingredient['supplier_id'])
            @php($supplier_name = $ingredient['supplier_name'])
        @endforeach

        </tbody>
    </table>
</div>

</body>
</html>