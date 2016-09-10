@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
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
                                        <h3>@lang('labels.purchase_manager_ingredients')</h3>
                                    </th>
                                </tr>
                                @include('views.purchase.partials.supplier_categories', ['categories' => $list['categories']])
                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                            @endif

                            @foreach($list['suppliers'] as $supplier)
                                @if (count($supplier['categories']))
                                    <tr>
                                        <th colspan="6">
                                            <h3>{!! $supplier['name'] !!}</h3>
                                        </th>
                                    </tr>
                                    @include('views.purchase.partials.supplier_categories', ['categories' => $supplier['categories']])
                                @endif
                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop