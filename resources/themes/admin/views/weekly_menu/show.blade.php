@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, ['role' => 'form', 'method' => 'put', 'class' => 'form-horizontal weekly-menu-form', 'route' => ['admin.weekly_menu.update', $model->id]]) !!}

            @include('weekly_menu.partials._buttons', ['class' => 'buttons-top', 'show' => true])

            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-body">

                            <div class="form-group no-margin">
                                <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
                                    @lang('labels.week')
                                </div>
                                <div class="margin-bottom-10 col-xs-12 col-sm-3 col-md-2 col-lg-1">
                                    {!! Form::text('week', null, ['id' => 'week_menu_date', 'class' => 'form-control select2 pull-left input-sm', 'required' => true, 'readonly' => true]) !!}
                                </div>
                                <div class="margin-bottom-10 col-xs-12 col-sm-3 col-md-2 col-lg-1">
                                    {!! Form::text('year', null, ['id' => 'year_menu_date', 'class' => 'form-control select2 pull-left input-sm', 'required' => true, 'readonly' => true]) !!}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="clearfix margin-bottom-15"></div>

                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    @foreach($model->baskets as $key => $basket)
                                        <li @if ($key == 0) class="active" @endif>
                                            <a aria-expanded="false"
                                               href="#basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}"
                                               data-toggle="tab"
                                               class="pull-left">
                                                {!! $basket->basket->name !!}
                                                <span class="text-lowercase">
                                                    (@lang('labels.portions') : {!! $basket->portions !!})
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach($model->baskets as $key => $basket)
                                        <div class="tab-pane @if ($key == 0) active @endif"
                                             id="basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}">
                                            @include('weekly_menu.tabs.show_basket_content')
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            @include('weekly_menu.partials._buttons', ['show' => true])

            {!! Form::close() !!}
        </div>
    </div>

@stop