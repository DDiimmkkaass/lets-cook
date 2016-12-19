<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="{!! public_path('/assets/themes/'.$theme.'/css/excel_styles.css') !!}"/>
</head>
<body>

@if (count($list))
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered recipes-packaging excel-table">
            <tbody>

            @php($i = 0)
            @php($width = 10)
            @php($users_count = count($list) - 1)
            @foreach($list as $key => $user)
                @php($recipes_count = count($user['recipes']) - 1)
                @php($baskets_count = count($user['baskets']) - 1)
                @php($ingredients_count = count($user['ingredients']) - 1)

                <tr style="vertical-align: bottom; height: 30px;">
                    @php($user_name = $user['full_name'].' (#'.$user['user_id'].')')
                    @php($width = max(cell_width($user_name), $width))
                    <th style="background-color: #cccccc;" colspan="4">
                        {!! $user_name !!})
                    </th>
                </tr>

                <tr>
                    <td style="background-color: #dddddd" colspan="4">{!! $user['address'] !!}</td>
                </tr>

                @unless(empty($user['comment']))
                    @php($width = max(cell_width($user['comment']), $width))
                    <tr>
                        <td style="background-color: #dddddd" colspan="4">
                            {!! $user['comment'] !!}
                        </td>
                    </tr>
                @endunless

                <tr>
                    <td colspan="4"></td>
                </tr>

                @if (count($user['recipes']))
                    <tr>
                        <th colspan="4">@lang('labels.recipes')</th>
                    </tr>
                    @php($recipe_width = 40)
                    @foreach($user['recipes'] as $_key => $recipe)
                        @php($recipe_width = max(cell_width($recipe['name']), $recipe_width))
                        @php($width = max($recipe_width, $width))
                        <tr @if (!count($user['baskets']) && !count($user['ingredients']) && $users_count == $i && $recipes_count == $_key)
                                style="width: {!! $width !!}px"
                            @endif>
                            <td colspan="4">
                                {!! $recipe['name'] !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td>
                    </tr>
                @endif

                <tr>
                    <td colspan="4"></td>
                </tr>

                @if (count($user['ingredients']))
                    <tr>
                        <th colspan="4">@lang('labels.additional_ingredients')</th>
                    </tr>
                    <tr>
                        <th style="text-align: center">
                            @lang('labels.ingredient')
                        </th>
                        <th style="width: 12px; text-align: center">
                            @lang('labels.count')
                        </th>
                        <th style="width: 20px; text-align: center">
                            @lang('labels.unit')
                        </th>
                        <th style="text-align: center">
                            @lang('labels.recipe')
                        </th>
                    </tr>

                    @php($ingredient_width = 15)
                    @php($ingredient_recipe_width = 40)
                    @foreach($user['ingredients'] as $_key => $ingredients)
                        <tr>
                            @php($ingredient_name = $ingredients['name'].
                                    ($ingredients['repacking'] ? ' ('.trans('labels.need_repacking').')' : ''))
                            @php($ingredient_width = max(cell_width($ingredient_name), $ingredient_width))
                            <td @if ($users_count == $i && $ingredients_count == $_key)
                                    style="width: {!! $ingredient_width !!}px"
                                @endif>
                                {!! $ingredient_name !!}
                            </td>
                            <td style="width: 15px; text-align: center">
                                {!! $ingredients['count'] !!}
                            </td>
                            <td style="width: 25px; text-align: center">
                                {!! $ingredients['unit'] !!}
                            </td>
                            @php($ingredient_recipe_width = max(cell_width($ingredients['recipe']), $ingredient_recipe_width))
                            <td @if ($users_count == $i && $ingredients_count == $_key)
                                    style="width: {!! $ingredient_recipe_width !!}px; text-align: center"
                                @endif style="text-align: center">
                                {!! $ingredients['recipe'] !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td>
                    </tr>
                @endif

                <tr>
                    <td colspan="4"></td>
                </tr>

                @if (count($user['baskets']))
                    <tr>
                        <th colspan="4">@lang('labels.additional_baskets')</th>
                    </tr>
                    @php($basket_width = 40)
                    @foreach($user['baskets'] as $_key => $basket)
                        @php($basket_width = max(cell_width($basket['name']), $basket_width))
                        @php($width = max($basket_width, $width))
                        <tr @if (!count($user['ingredients']) && $users_count == $i && $baskets_count == $_key)
                                style="width: {!! $width !!}px"
                            @endif>
                            <td colspan="4">{!! $basket['name'] !!}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td>
                    </tr>
                @endif

                <tr>
                    <td style="height: 25px;" colspan="4"></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endif

</body>
</html>