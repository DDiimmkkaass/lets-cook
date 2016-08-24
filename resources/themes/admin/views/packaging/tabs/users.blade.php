@if (count($list))
    <div class="box-body table-responsive no-padding">
        <div class="margin-bottom-10">
            <div class="col-sm-12 text-right">
                <a class="download btn btn-flat btn-success btn-sm" target="_blank"
                   href="{!! route('admin.packaging.download', ['users', $year, $week]) !!}">
                    @lang('labels.download_xlsx_file')
                </a>
            </div>

            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered recipes-packaging">
            <tbody>

            @foreach($list as $user)
                <tr class="user-title">
                    <th colspan="4">
                        <h4 class="margin-top-0 margin-bottom-0">
                            <b>{!! $user['full_name'] !!} ({!! $user['user_id']  !!})</b>
                        </h4>
                    </th>
                </tr>

                <tr class="title">
                    <td colspan="4">{!! $user['address'] !!}</td>
                </tr>

                @unless(empty($user['comment']))
                    <tr class="title">
                        <td colspan="4">{!! $user['comment'] !!}</td>
                    </tr>
                @endunless

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
                        <td colspan="4" class="spliter"></td>
                    </tr>
                @endif

                @if (count($user['ingredients']))
                    <tr>
                        <th colspan="4">@lang('labels.additional_ingredients')</th>
                    </tr>
                    <tr>
                        <th class="col-sm-6 text-center">
                            @lang('labels.ingredient')
                        </th>
                        <th class="col-sm-1 text-center">
                            @lang('labels.count')
                        </th>
                        <th class="col-sm-2 text-center">
                            @lang('labels.unit')
                        </th>
                        <th class="col-sm-3 text-center">
                            @lang('labels.recipe')
                        </th>
                    </tr>
                    @foreach($user['ingredients'] as $ingredients)
                        <tr>
                            <td>
                                {!! $ingredients['name'] !!}
                                @if ($ingredients['repacking']) (@lang('labels.need_repacking')) @endif
                            </td>
                            <td class="text-center">
                                {!! $ingredients['count'] !!}
                            </td>
                            <td class="text-center">
                                {!! $ingredients['unit'] !!}
                            </td>
                            <td class="text-center">
                                {!! $ingredients['recipe'] !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="spliter"></td>
                    </tr>
                @endif

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
                        <td colspan="4" class="spliter"></td>
                    </tr>
                @endif

                <tr>
                    <th colspan="4"></th>
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