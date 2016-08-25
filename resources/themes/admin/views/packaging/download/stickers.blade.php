<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div class="box-body table-responsive no-padding">
    <table class="table table-bordered stickers-packaging">
        <tbody>
        <tr>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.basket')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.recipe')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.portions')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.number_of_recipe')</th>
            <th style="background-color: #cccccc; text-align: center;">@lang('labels.count')</th>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        @foreach($list as $basket)
            @foreach($basket['recipes'] as $recipe)
                @if (count($recipe['ingredients']))
                    @foreach($recipe['ingredients'] as $ingredients)
                        @php($_ingredients = '')
                        @foreach($ingredients as $ingredient)
                            @php($_ingredients .= $ingredient->name.' - '.$ingredient->count.$ingredient->unit_name.'; ')
                        @endforeach
                        <tr>
                            <td>{!! $basket['name'] !!}</td>
                            <td style="min-height: 35px;">
                                {!! $recipe['name'] !!} ({!! trim($_ingredients, '; ') !!})
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
                <td colspan="5"></td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>