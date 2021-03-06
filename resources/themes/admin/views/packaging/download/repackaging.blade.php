<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered repackaging-packaging excel-table">
            <tbody>
            <tr style="background-color: #cccccc">
                <th style="width: 50px;">@lang('labels.categories_&_ingredients')</th>
                <th style="width: 20px; text-align: center">@lang('labels.packaging')</th>
                <th style="width: 20px; text-align: center">@lang('labels.count')(@lang('labels.count_short'))</th>
            </tr>

            <tr>
                <td colspan="3"></td>
            </tr>

            @foreach($list as $category)
                <tr>
                    <th colspan="3"><h4>{!! $category['name'] !!}</h4></th>
                </tr>

                <tr>
                    <td colspan="3"></td>
                </tr>

                @foreach($category['ingredients'] as $ingredient)
                    <tr>
                        <td>{!! $ingredient['name'] !!}</td>
                        <td style="text-align: center">{!! $ingredient['package'] !!} {!! $ingredient['unit_name'] !!}</td>
                        <td style="text-align: center">{!! $ingredient['count'] !!}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" style="height: 1px; border-bottom: 1px solid #000"></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>
@endif

</body>
</html>