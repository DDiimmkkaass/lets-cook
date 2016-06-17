@extends('layouts.main')

@section('assets.top')
    @parent
    <script src="{!! asset('assets/themes/admin/vendor/adminlte/plugins/ckeditor/ckeditor.js') !!}"></script>

    <script src="{!! asset('assets/components/sysTranslit/js/jquery.synctranslit.min.js') !!}"></script>
@stop
