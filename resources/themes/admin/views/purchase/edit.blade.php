@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">

            @include('purchase.partials._buttons', ['class' => 'buttons-top'])

            {!! Form::open(['role' => 'form', 'method' => 'post', 'class' => 'form-horizontal purchase-form']) !!}

                @include('purchase.partials.purchase_manager_table')

                @foreach($list['suppliers'] as $supplier_id => $supplier)

                    @if ($supplier_id)
                        @include('purchase.partials.supplier')
                    @endif

                @endforeach

            {!! Form::close() !!}

            @include('purchase.partials._buttons')

        </div>
    </div>

@stop