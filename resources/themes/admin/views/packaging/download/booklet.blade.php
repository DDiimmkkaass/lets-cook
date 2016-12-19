<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered booklet-packaging excel-table">
        <tbody>

        <tr style="background-color: #cccccc; height: 27px">
            <th>
                @lang('labels.recipe')
            </th>
            <th style="width: 15px">
                @lang('labels.count')
            </th>
        </tr>

        @php($recipe_width = 10)
        @php($basket_count = count($list) - 1)
        @php($i = 0)
        @foreach($list as $key => $basket)
            @php($recipe_count = count($basket) - 1)
            @php($n = 0)
            @foreach($basket as $_key => $recipe)
                @php($recipe_width = max(cell_width($recipe['name']), $recipe_width))
                <tr>
                    <td @if ($basket_count == $i && $recipe_count == $n) style="width: {!! $recipe_width !!}px;" @endif>
                        {!! $recipe['name'] !!}
                    </td>
                    <td style="text-align: center">{!! $recipe['recipes_count'] !!}</td>
                </tr>
                @php($n++)
            @endforeach

            <tr>
                <td colspan="2"></td>
            </tr>
            @php($i++)
        @endforeach

        <tr>
            <td colspan="2"></td>
        </tr>

        <tr style="height: 20px;">
            <th colspan="2">
                @if ($booklet)
                    {!! $booklet->link !!}
                @else
                    @lang('messages.booklet not have a link')
                @endif
            </th>
        </tr>

        </tbody>
    </table>
</div>

</body>
</html>