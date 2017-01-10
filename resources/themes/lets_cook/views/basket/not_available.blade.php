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

<div class="app h-404 not-available">

    <section class="main">
        <h2 class="let-cook__subTitle">
            @lang('front_texts.basket not available on this week')
        </h2>

        <a class="black-long-button"
           title="@lang('front_labels.go_to_baskets')"
           href="{!! localize_route('baskets.index', 'current') !!}">
            @lang('front_labels.our_baskets')
        </a>
    </section>

</div>

</body>
</html>