<!doctype html>
<html lang="en">
<head>
    <title>{!! config('app.name') !!}</title>

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/favicon.ico" rel="shortcut icon"/>

    <link rel="stylesheet" href="{!! asset('assets/components//pikaday/css/pikaday.css') !!}"/>

    <link rel="stylesheet" href="{!! asset('assets/themes/'.config('app.theme').'/css/styles.css') !!}"/>
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