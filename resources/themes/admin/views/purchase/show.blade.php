@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">

            @foreach($list['suppliers'] as $supplier)
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{!! $supplier['name'] !!}</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        @foreach($supplier['categories'] as $category)
                            <h4>{!! $category['name'] !!}</h4>
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>@lang('labels.ingredient')</th>
                                        <th class="text-center">@lang('labels.unit')</th>
                                        <th class="text-center">@lang('labels.price')</th>
                                        <th class="text-center">@lang('labels.count')</th>
                                        <th class="text-center">@lang('labels.in_stock')</th>
                                        <th class="text-center">@lang('labels.buy_count')</th>
                                        <th class="text-center">@lang('labels.purchase_manager')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($category['ingredients'] as $ingredient)
                                        <tr>
                                            <td>{!! link_to_route('admin.ingredient.edit', $ingredient->ingredient->name, [$ingredient->ingredient_id], ['target' => '_blank']) !!}</td>
                                            <td class="text-center">{!! $ingredient->ingredient->unit->name !!}</td>
                                            <td class="text-center">{!! $ingredient->price !!}</td>
                                            <td class="text-center">{!! $ingredient->count !!}</td>
                                            <td class="text-center">
                                                <label class="checkbox-label">
                                                    <input readonly="readonly" type="checkbox" class="square" @if ($ingredient->in_stock) checked="checked" @endif />
                                                </label>
                                            </td>
                                            <td class="text-center">{!! $ingredient->buy_count !!}</td>
                                            <td class="text-center">
                                                <label class="checkbox-label">
                                                    <input readonly="readonly" type="checkbox" class="square" @if ($ingredient->purchase_manager) checked="checked" @endif />
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@stop