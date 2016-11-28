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

            @foreach($list as $user)
                <tr style="vertical-align: bottom; height: 30px; width: 70px">
                    <th style="background-color: #cccccc;" colspan="4">
                        {!! $user['full_name'] !!} (#{!! $user['user_id']  !!})
                    </th>
                </tr>

                <tr>
                    <td style="background-color: #dddddd" colspan="4">{!! $user['address'] !!}</td>
                </tr>

                @unless(empty($user['comment']))
                    <tr>
                        <td style="background-color: #dddddd" colspan="4">{!! $user['comment'] !!}</td>
                    </tr>
                @endunless

                <tr>
                    <td colspan="4"></td>
                </tr>

                @if (count($user['recipes']))
                    <tr>
                        <th colspan="4">@lang('labels.recipes')</th>
                    </tr>
                    @foreach($user['recipes'] as $recipe)
                        <tr>
                            <td colspan="4">{!! $recipe['name'] !!}</td>
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
                        <th style="text-align: center">
                            @lang('labels.count')
                        </th>
                        <th style="text-align: center">
                            @lang('labels.unit')
                        </th>
                        <th style="text-align: center">
                            @lang('labels.recipe')
                        </th>
                    </tr>
                    @foreach($user['ingredients'] as $ingredients)
                        <tr>
                            <td style="width: 50px;">
                                {!! $ingredients['name'] !!}
                                @if ($ingredients['repacking']) (@lang('labels.need_repacking')) @endif
                            </td>
                            <td style="width: 15px; text-align: center">
                                {!! $ingredients['count'] !!}
                            </td>
                            <td style="width: 25px; text-align: center">
                                {!! $ingredients['unit'] !!}
                            </td>
                            <td style="width: 50px; text-align: center">
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
                    @foreach($user['baskets'] as $basket)
                        <tr>
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