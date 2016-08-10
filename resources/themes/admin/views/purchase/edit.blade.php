@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['role' => 'form', 'method' => 'post', 'class' => 'form-horizontal purchase-form']) !!}

                @include('purchase.partials.purchase_manager_table')

                @foreach($list['suppliers'] as $supplier_id => $supplier)

                    @include('purchase.partials.supplier')

                @endforeach

            {!! Form::close() !!}
        </div>
    </div>

@stop