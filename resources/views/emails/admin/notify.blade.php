@extends('emails.master')

@php($context = unserialize($context))

@section('content')
    {!! $_message !!}

    <hr><pre>
        {!! print_r((array) $context) !!}
    </pre><hr>
@endsection