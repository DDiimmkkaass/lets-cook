@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            @include('purchase.partials._buttons', ['class' => 'buttons-top', 'generate' => true])

            <div class="box box-info">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>@lang('labels.ingredient')</th>
                                <th class="text-center">@lang('labels.unit')</th>
                                <th class="text-center">@lang('labels.price')</th>
                                <th class="text-center">@lang('labels.count')</th>
                                <th class="text-center">@lang('labels.in_stock')</th>
                                <th class="text-center">@lang('labels.purchase_manager')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if (count($list['categories']))
                                <tr>
                                    <th colspan="6">
                                        <h3>
                                            @lang('labels.purchase_manager_ingredients')

                                            <span class="label label-success margin-right-10 pointer download-purchase-list pull-right font-size-12 text-normal">
                                                <a target="_blank"
                                                   href="{!! route('admin.purchase.download_pre_report', [$list['year'], $list['week'], 0]) !!}">
                                                    @lang('labels.download_xlsx_file')
                                                </a>
                                            </span>
                                        </h3>
                                    </th>
                                </tr>
                                @include('purchase.partials.generate_purchase_manager_table')
                            @endif

                            @foreach($list['suppliers'] as $supplier_id => $supplier)
                                @if ($supplier_id && count($supplier['categories']))
                                    <tr>
                                        <th colspan="6">
                                            <h3>
                                                {!! $supplier['name'] !!}

                                                <span class="label label-success margin-right-10 pointer download-purchase-list pull-right font-size-12 text-normal">
                                                    <a target="_blank"
                                                       href="{!! route('admin.purchase.download_pre_report', [$list['year'], $list['week'], $supplier_id]) !!}">
                                                        @lang('labels.download_xlsx_file')
                                                    </a>
                                                </span>
                                            </h3>
                                        </th>
                                    </tr>
                                    @include('purchase.partials.generate_supplier')
                                @endif
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include('purchase.partials._buttons', ['generate' => true])
        </div>
    </div>

@stop