@if (count($list))
    <div class="box-body table-responsive no-padding">
        <div class="margin-bottom-10">
            <div class="col-sm-12 text-right">
                <a class="download btn btn-flat btn-success btn-sm" target="_blank"
                   href="{!! route('admin.packaging.download', ['recipes', $year, $week]) !!}">
                    @lang('labels.download_xlsx_file')
                </a>
            </div>

            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered recipes-packaging">
            <tbody>

            @foreach($list as $recipe)
                <tr class="recipe-name">
                    <th colspan="3" class="col-sm-10 text-center">
                        <h5>
                            {!! link_to_route('admin.recipe.show', $recipe['name'], $recipe['recipe_id'], ['target' => '_blank']) !!}
                            (@lang('labels.portions'): {!! $recipe['portions'] !!})
                        </h5>
                    </th>
                    <th colspan="2" class="col-sm-2 text-right">
                        <div class="col-sm-6 padding-top-8">
                            {!! trans('labels.recipe_orders') !!}:
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control input-sm text-center" type="text" readonly="readonly"
                                   value="{!! $recipe['recipes_count'] !!}">
                        </div>
                    </th>
                </tr>

                <tr class="title">
                    <td>@lang('labels.category')</td>
                    <td>@lang('labels.package_&_ingredient')</td>
                    <td class="text-center">@lang('labels.count')</td>
                    <td class="text-center">@lang('labels.unit')</td>
                    <td class="text-center">@lang('labels.total')</td>
                </tr>

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
                                    ({!! $ingredient['parameter_name'] !!})
                                    @if ($ingredient['repacking']) (@lang('labels.need_repacking')) @endif
                                </td>
                                <td class="text-center">{!! $ingredient['count'] !!}</td>
                                <td class="text-center">{!! $ingredient['unit_name'] !!}</td>
                                <td class="text-center">{!! $ingredient['total'] !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="spliter"></td>
                        </tr>
                    @endif
                @endforeach

                @unless (empty($recipe['ingredients']))
                    <tr>
                        <td colspan="5"><b>@lang('labels.additional_sets_of_ingredients')</b></td>
                    </tr>
                    @php($i = 1)
                    @foreach($recipe['ingredients'] as $key => $order)
                        <tr>
                            <td class="ingredients-sets" colspan="5">
                                {!! $i !!}.
                                @foreach($order as $ingredient)
                                    <span class="ingredient">
                                        {!! $ingredient->name !!}
                                        (@lang('labels.package') {!! $ingredient->package !!}
                                        , {!! $ingredient['parameter_name'] !!})
                                        @if ($ingredient['repacking']) (@lang('labels.need_repacking')) @endif
                                        - {!! $ingredient->count !!} {!! $ingredient->unit_name !!}
                                    </span>
                                @endforeach
                                <br>
                            </td>
                        </tr>
                        @php($i++)
                    @endforeach
                    <tr>
                        <td colspan="5" class="spliter"></td>
                    </tr>
                @endunless

                <tr>
                    <td colspan="5"></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
@else
    <p class="help-block text-center">
        @lang('messages.no orders')
    </p>
@endif