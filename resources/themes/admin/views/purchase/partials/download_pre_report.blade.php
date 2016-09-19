<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

@php($baskets_count = count($baskets))
@php($ingredients_count = count($list))

<div class="box-body table-responsive no-padding">
    <table>
        <tbody>

        <tr>
            <td colspan="7">
                <h3 style="background: #92D050; text-align: center; display: block">
                    <b>
                        {!! mb_strtoupper(trans('labels.week')) !!} {!! $week !!}
                        @if ($supplier_name) {!! $supplier_name !!} @endif
                    </b>
                </h3>
            </td>

            @foreach($baskets as $basket)
                <th style="background-color: #92D050;">
                    <div style="text-align: center;">
                        @lang($basket['name'])
                    </div>
                </th>
            @endforeach
        </tr>

        <tr>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.ingredient')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.in_stock')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.unit_short')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.price') {!! $currency !!}</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.count')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.category')</th>
            <th style="background-color: #FCD5B4; text-align: center;">@lang('labels.supplier')</th>

            @foreach($baskets as $basket)
                <th style="background-color: #FCD5B4; text-align: center;">{!! $basket['count'] !!}</th>
            @endforeach
        </tr>

        @php($i = 0)
        @php($supplier_id = 0)
        @foreach($list as $ingredient)
            <tr>
                <td>{!! $ingredient['name'] !!}</td>
                <td style="text-align: center">@if ($ingredient['in_stock']) @lang('labels.yes') @endif</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['unit'] !!}</td>
                <td style="background-color: #dddddd; text-align: center;">{!! $ingredient['price'] !!}</td>
                <td style="text-align: right;">{!! $ingredient['count'] !!}</td>
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

            @if (($i > 0 && $ingredient['supplier_id'] != $supplier_id) || $ingredients_count - 1 == $i)
                <tr>
                    <th></th><th></th><th></th><th></th><th></th><th></th>
                    <th style="text-align: center">
                        {!! $ingredient['supplier_name'] !!} @lang('labels.result')
                    </th>

                    @for ($i = 0; $i < $baskets_count; $i++)
                        <th></th>
                    @endfor
                </tr>
            @endif

            @php($i++)
            @php($supplier_id = $ingredient['supplier_id'])
        @endforeach

        </tbody>
    </table>
</div>