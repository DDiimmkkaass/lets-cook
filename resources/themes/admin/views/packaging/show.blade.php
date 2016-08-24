@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12 packaging-page">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs packaging-tabs">
                    <li class="active">
                        <a aria-expanded="false" href="#repacking" class="ajax-tab"
                           data-href="{!! route('admin.packaging.tab', ['repackaging', $year, $week]) !!}"
                           data-toggle="tab">@lang('labels.tab_repackaging')</a>
                    </li>
                    <li>
                        <a aria-expanded="false" href="#recipes" class="ajax-tab"
                           data-href="{!! route('admin.packaging.tab', ['recipes', $year, $week]) !!}"
                           data-toggle="tab">@lang('labels.tab_packaging_recipes')</a>
                    </li>
                    <li>
                        <a aria-expanded="false" href="#users" class="ajax-tab"
                           data-href="{!! route('admin.packaging.tab', ['users', $year, $week]) !!}"
                           data-toggle="tab">@lang('labels.tab_packaging_users')</a>
                    </li>
                    <li>
                        <a aria-expanded="false" href="#deliveries" class="ajax-tab"
                           data-href="{!! route('admin.packaging.tab', ['deliveries', $year, $week]) !!}"
                           data-toggle="tab">@lang('labels.tab_packaging_deliveries')</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="repacking"></div>

                    <div class="tab-pane" id="recipes"></div>

                    <div class="tab-pane" id="users"></div>

                    <div class="tab-pane" id="deliveries"></div>
                </div>
            </div>
        </div>
    </div>

@stop