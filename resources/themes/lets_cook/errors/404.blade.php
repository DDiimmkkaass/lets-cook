<!doctype html>
<html lang="{!! $lang !!}">
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
        <div class="h-main-title">404</div>

        <h1 class="let-cook__subTitle">Страница не найдена</h1>

        <h2 class="let-cook__subTitle">Но мы, не оставим Вас голодными =)</h2>

        <a class="black-long-button" title="На главную" href="{!! localize_route('home') !!}">На главную</a>
    </section>

</div>

</body>
</html>