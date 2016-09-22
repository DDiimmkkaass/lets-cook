<!doctype html>
<html lang="{!! $lang !!}">
<head>
    @include('partials.head')

    @stack('assets.top')
</head>
<body>
<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="app">

    @include('partials.modules.popup')

    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    @include('partials.modules.messages')

</div>

@include('partials.foot')

</body>
</html>
