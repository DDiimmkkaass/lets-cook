<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered stickers-packaging">
        <tbody>
        <tr>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.ingredient')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.in_stock')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.category')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.supplier')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.price') {!! $currency !!}</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.count')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.unit')</th>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>

        @foreach($list as $ingredient)
            <tr>
                <td>{!! $ingredient['ingredient'] !!}</td>
                <td style="text-align: center">@if ($ingredient['in_stock']) @lang('labels.yes') @endif</td>
                <td style="background-color: #dddddd;">{!! $ingredient['category'] !!}</td>
                <td style="background-color: #dddddd;">{!! $ingredient['supplier'] !!}</td>
                <td style="background-color: #dddddd;">{!! $ingredient['price'] !!}</td>
                <td>{!! $ingredient['count'] !!}</td>
                <td style="background-color: #dddddd;">{!! $ingredient['unit'] !!}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>