<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered recipes-packaging excel-table">
        <tbody>

        <tr style="background-color: #cccccc; height: 30px;">
            <th colspan="2">
                <h4>
                    {!! $recipe['name'] !!}
                </h4>
            </th>
            <th style="text-align: right">
                {!! trans('labels.recipe_orders') !!}:
            </th>
            <th style="text-align: right">
                <h4>{!! $recipe['recipes_count'] !!}</h4>
            </th>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>

        <tr>
            <th>@lang('labels.category')</th>
            <th style="width: {!! strlen($recipe['name']) * config('recipe.title_to_with_multiplier') !!}px">@lang('labels.package_&_ingredient')</th>
            <th style="width: 12px; text-align: center;">@lang('labels.count')</th>
            <th style="width: 20px; text-align: center;">@lang('labels.unit')</th>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>

        @php($category_width = 15)
        @php($ingredient_width = 40)
        @foreach($recipe['packages'] as $package => $ingredients)
            @if (count($ingredients))
                <tr>
                    <td></td>
                    <td><b>@lang('labels.package') {!! $package !!}</b></td>
                    <td colspan="2"></td>
                </tr>
                @foreach($ingredients as $ingredient)
                    <tr>
                        @php($category_width = max(cell_width($ingredient['category_name']), $category_width))
                        <td style="width: {!! $category_width !!}px">{!! $ingredient['category_name'] !!}</td>
                        @php($ingredient_name = $ingredient['name'].' '.
                                ($ingredient['parameter_name'] ? '('.$ingredient['parameter_name'].')':'').
                                ($ingredient['repacking'] ? '('.trans('labels.need_repacking').')':'')
                            )
                        @php($ingredient_width = max(cell_width($ingredient_name), $ingredient_width))
                        <td style="width: {!! $ingredient_width !!}px">
                            {!! $ingredient_name !!}
                        </td>
                        <td style="text-align: center;">{!! $ingredient['count'] !!}</td>
                        <td style="text-align: center;">{!! $ingredient['unit_name'] !!}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
            @endif
        @endforeach

        @unless (empty($recipe['ingredients']))
            <tr>
                <td colspan="4"><b>@lang('labels.additional_sets_of_ingredients')</b></td>
            </tr>
            @foreach($recipe['ingredients'] as $key => $ingredients)
                <tr>
                    <td style="height: {!! 17 * count($ingredients) !!}px; vertical-align: top;" colspan="4">
                        @foreach($ingredients as $ingredient)
                            - {!! $ingredient->name !!}
                            (@lang('labels.package') {!! $ingredient->package !!}
                            {!! $ingredient['parameter_name'] ? ', '.$ingredient['parameter_name'] : '' !!})
                            @if ($ingredient['repacking']) (@lang('labels.need_repacking')) @endif
                            - {!! $ingredient->count !!} {!! $ingredient->unit_name !!}
                            <br>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td>
                </tr>
            @endforeach
        @endunless

        </tbody>
    </table>
</div>

</body>
</html>