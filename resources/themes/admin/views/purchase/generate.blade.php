@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">

            @include('purchase.partials._buttons', ['class' => 'buttons-top', 'generate' => true])

            @if (count($list['categories']))
                @include('purchase.partials.generate_purchase_manager_table')
            @endif

            @foreach($list['suppliers'] as $supplier_id => $supplier)

                @if ($supplier_id)
                    @include('purchase.partials.generate_supplier')
                @endif

            @endforeach

            @include('purchase.partials._buttons', ['generate' => true])

        </div>
    </div>

@stop