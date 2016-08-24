@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">

            @if (count($list['categories']))
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('labels.purchase_manager_ingredients')</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        @include('views.purchase.partials.supplier_categories', ['categories' => $list['categories']])
                    </div>
                </div>
            @endif

            @foreach($list['suppliers'] as $supplier)
                @if (count($supplier['categories']))
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
                            @include('views.purchase.partials.supplier_categories', ['categories' => $supplier['categories']])
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>

@stop