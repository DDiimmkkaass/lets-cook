<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered stickers-packaging">
        <tbody>
        @if ($pre_report)
            <tr>
                <td colspan="7">
                    <div style="color: #ff0000; text-align: center"><b>{!! $title !!}</b></div>
                </td>
            </tr>
        @endif

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
            <td colspan="7"></td>
        </tr>

        @foreach($list as $ingredient)
            <tr>
                <td>{!! $ingredient->ingredient->name !!}</td>
                <td style="text-align: center">@if ($ingredient->in_stock) @lang('labels.yes') @endif</td>
                <td style="background-color: #dddddd;">{!! $ingredient->ingredient->category->name !!}</td>
                <td style="background-color: #dddddd;">{!! $ingredient->ingredient->supplier->name !!}</td>
                <td style="text-align: right;">{!! $ingredient->price !!}</td>
                <td style="text-align: right;">{!! $ingredient->count !!}</td>
                <td style="background-color: #dddddd; text-align: left;">{!! $ingredient->getUnitName() !!}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>