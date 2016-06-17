@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="translations-table">

                        <form action="{!! route('admin.translation.update', $group) !!}" method="post" role="form"
                              class="without-js-validation">

                            {!! csrf_field() !!}

                            <input type="hidden" name="page" value="{!! $page !!}">

                            <table class="table table-bordered table-striped">
                                <tbody>
                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        @include('translation.partials.buttons')
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        {!! $list->links() !!}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="col-sm-3">@lang('labels.key')</th>

                                    @foreach($locales as $locale)
                                        <th class="col-sm-3">{!! trans('labels.tab_' . $locale) !!}</th>
                                    @endforeach
                                </tr>
                                </tbody>

                                @foreach($list as $key => $items)
                                    <tr>
                                        <td class="col-sm-3">
                                            {!! $key !!}
                                        </td>

                                        @foreach($locales as $locale)
                                            <td class="col-sm-3 form-group
                                            @if ($errors->has($locale.'.'.$key) || empty($items[$locale])) has-error @endif">
                                            <textarea
                                                    name="{!! $locale !!}[{!! $key !!}]"
                                                    id="{!! $locale !!}_{!! str_replace(' ', '_', $key) !!}"
                                                    class="form-control input-sm"
                                            >{!! isset($items[$locale]) ? $items[$locale] : null !!}</textarea>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        {!! $list->links() !!}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="{!! count($locales) + 1 !!}">
                                        @include('translation.partials.buttons')
                                    </td>
                                </tr>
                            </table>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop