<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body>
<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="app h-404">

    <section class="main">
        <h1 class="let-cook__subTitle h-500-title">Произошла ошибка, но мы с ней уже разбираемся, зайдите позже.</h1>

        <a class="black-long-button" title="На главную" href="{!! localize_route('home') !!}">На главную</a>
    </section>

</div>

</body>
</html>