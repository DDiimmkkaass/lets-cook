<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered stickers-packaging excel-table">
        <tbody>
        <tr>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.basket')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.recipe')</th>
            <th style="width: 12px; background-color: #cccccc; text-align: center;">@lang('labels.portions')</th>
            <th style="width: 18px; background-color: #cccccc; text-align: center;">@lang('labels.number_of_recipe')</th>
            <th style="width: 14px; background-color: #cccccc; text-align: center;">@lang('labels.count')</th>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        @php($basket_width = 30)
        @php($recipe_width = 40)
        @php($list_count = count($list) - 1)
        @foreach($list as $key => $basket)
            @php($basket_width = max(cell_width($basket['name']), $basket_width))
            @foreach($basket['recipes'] as $recipe)
                @php($recipe_width = max(cell_width($recipe['name']), $recipe_width))
                @if (count($recipe['ingredients']))
                    @foreach($recipe['ingredients'] as $ingredients)
                        @php($_ingredients = '')
                        @foreach($ingredients as $ingredient)
                            @php($_ingredients .= $ingredient->name.' - '.$ingredient->count.$ingredient->unit_name.'; ')
                        @endforeach
                        @php($recipe_name = $recipe['name'].' ('.trim($_ingredients, '; ').')')
                        @php($recipe_width = max(cell_width($recipe_name), $recipe_width))
                        <tr>
                            <td>{!! $basket['name'] !!}</td>
                            <td style="min-height: 35px;">
                                {!! $recipe_name !!}
                            </td>
                            <td style="text-align: center;">{!! $basket['portions'] !!}</td>
                            <td style="text-align: center;">{!! $recipe['position'] !!}</td>
                            <td style="text-align: center;">1</td>
                        </tr>
                    @endforeach
                @endif

                @if ($recipe['recipes_count'] > 0)
                    <tr>
                        <td>{!! $basket['name'] !!}</td>
                        <td>
                            {!! $recipe['name'] !!}
                        </td>
                        <td style="text-align: center;">{!! $basket['portions'] !!}</td>
                        <td style="text-align: center;">{!! $recipe['position'] !!}</td>
                        <td style="text-align: center;">{!! $recipe['recipes_count'] !!}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                @if ($key == $list_count)
                    <td style="width: {!! $basket_width !!}px"></td>
                    <td style="width: {!! $recipe_width !!}px"></td>
                    <td colspan="3"></td>
                @else
                    <td colspan="5"></td>
                @endif
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

</body>
</html>