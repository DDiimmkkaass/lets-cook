<!DOCTYPE html>
    <html lang="en-US">
    <head>
        <meta charset="utf-8">

        <style type="text/css">
            * {
                -webkit-text-size-adjust: none;
                -webkit-text-resize: 100%;
                text-resize: 100%;
                font-size: 13px;
            }
            .copy {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper email-wrapper">

            <div class="content">

                @yield('content')

            </div>

            <div class="copy">
                &copy; {!! link_to_route('home', config('app.name')) !!} {!! Carbon::now()->year !!}
            </div>
        </div>
    </body>
</html>