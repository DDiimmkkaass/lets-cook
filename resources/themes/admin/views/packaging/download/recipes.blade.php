<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered recipes-packaging">
        <tbody>

        <tr style="background-color: #cccccc">
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

        <tr><td colspan="4"></td></tr>

        <tr>
            <th>@lang('labels.category')</th>
            <th style="width: {!! strlen($recipe['name']) * config('recipe.title_to_with_multiplier') !!}px">@lang('labels.package_&_ingredient')</th>
            <th style="text-align: center;">@lang('labels.count')</th>
            <th style="text-align: center;">@lang('labels.unit')</th>
        </tr>

        <tr><td colspan="4"></td></tr>

        @foreach($recipe['packages'] as $package => $ingredients)
            @if (count($ingredients))
                <tr>
                    <td></td>
                    <td><b>@lang('labels.package') {!! $package !!}</b></td>
                    <td colspan="3"></td>
                </tr>
                @foreach($ingredients as $ingredient)
                    <tr>
                        <td>{!! $ingredient['category_name'] !!}</td>
                        <td>
                            {!! $ingredient['name'] !!}
                            @if ($ingredient['parameter_name']) ({!! $ingredient['parameter_name'] !!}) @endif
                            @if ($ingredient['repacking']) (@lang('labels.need_repacking')) @endif
                        </td>
                        <td style="text-align: center;">{!! $ingredient['count'] !!}</td>
                        <td style="text-align: center;">{!! $ingredient['unit_name'] !!}</td>
                    </tr>
                @endforeach
                <tr><td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td></tr>
                <tr><td colspan="4"></td></tr>
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
                <tr><td colspan="4" style="height: 1px; border-bottom: 1px solid #000"></td></tr>
            @endforeach
        @endunless

        </tbody>
    </table>
</div>