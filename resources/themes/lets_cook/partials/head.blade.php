<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{!! config('app.name') !!}</title>

{!! Meta::render() !!}

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="/favicon.ico" rel="shortcut icon"/>

<link rel="stylesheet" href="{!! asset('assets/components//pikaday/css/pikaday.css') !!}"/>

<link rel="stylesheet" href="{!! Theme::asset('css/styles.css', null, true) !!}"/>

@include('partials.vars')

<script type="text/javascript" src="{!! asset('assets/components/jquery/dist/jquery.js') !!}"></script>

<script src="//yastatic.net/share2/share.js" async="async"></script>