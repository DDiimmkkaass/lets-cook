<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header">
                <h4>@lang('labels.statistic_of_current_week')</h4>
            </div>
            <div class="box-body">
                <div class="col-xs-6 orders-summary-table padding-left-0">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th class="text-center"><b>@lang('labels.basket_&_recipe')</b></th>
                            <th class="text-center"><b>@lang('labels.count')</b></th>
                        </tr>
                        @foreach($statistic['baskets'] as $basket)
                            <tr>
                                <td colspan="2"><b>{!! $basket['name'] !!}</b></td>
                            </tr>
                            @foreach($basket['recipes'] as $recipe)
                                <tr>
                                    <td>{!! $recipe['recipe']->getName() !!}</td>
                                    <td class="text-center">{!! $recipe['count'] !!}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                        @endforeach

                        @if (count($statistic['additional_baskets']))
                            <tr>
                                <td colspan="2"><b>@lang('labels.additional_baskets')</b></td>
                            </tr>
                            @foreach($statistic['additional_baskets'] as $basket)
                                <tr>
                                    <td>{!! $basket['name'] !!}</td>
                                    <td class="text-center">{!! $basket['count'] !!}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                        @endif

                        @if (count($statistic['additional_ingredients']))
                            <tr>
                                <td colspan="2"><b>@lang('labels.additional_ingredients')</b></td>
                            </tr>
                            @foreach($statistic['additional_ingredients'] as $ingredient)
                                <tr>
                                    <td>{!! $ingredient['name'] !!}</td>
                                    <td class="text-center">{!! $ingredient['count'] !!}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                @if (!empty($home))
                    <div class="col-xs-6 pull-right padding-right-0">
                        <div class="days-statistic-chart text-center">
                            <div class="col-sm-12 margin-bottom-20">
                                <h5 class="margin-left-30">@lang('labels.count_of_deliveries')</h5>

                                <canvas id="days_statistic_chart_count"></canvas>
                            </div>

                            <div class="col-sm-12">
                                <h5 class="margin-left-25">@lang('labels.sum_of_orders')</h5>

                                <canvas id="days_statistic_chart_sum"></canvas>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(document).on('ready', function() {
                                Statistic.data = {!! json_encode($statistic['days']) !!};
                            });
                        </script>
                    </div>
                @endif

                <div class="orders-totals-table">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>@lang('labels.day')</th>
                            <th class="text-center">@lang('labels.count')</th>
                            <th class="text-center">@lang('labels.sum')</th>
                            <th class="text-center">@lang('labels.sum_with_discount')</th>
                        </tr>
                        @foreach($statistic['days'] as $day)
                            <tr>
                                <td><b>{!! $day['title'] !!}</b> ({!! get_localized_date($day['day'], 'd-m-Y') !!})</td>
                                <td class="text-center">{!! $day['count'] !!}</td>
                                <td class="text-center">{!! $day['sum'] !!} {!! $currency !!}</td>
                                <td class="text-center">{!! $day['sum_with_discount'] !!} {!! $currency !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <th class="text-right"><b>@lang('labels.in_total'):</b></th>
                            <th class="text-right"><b>{!! $statistic['count'] !!}</b></th>
                            <th class="text-right"><b>{!! $statistic['sum'] !!} {!! $currency !!}</b></th>
                            <th class="text-right"><b>{!! $statistic['sum_with_discount'] !!} {!! $currency !!}</b></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>