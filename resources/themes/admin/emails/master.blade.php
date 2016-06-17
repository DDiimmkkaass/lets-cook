<!DOCTYPE html>
    <html lang="en-US">
    <head>
        <meta charset="utf-8">

        <style type="text/css">
            * {
                -webkit-text-size-adjust: none;
                -webkit-text-resize: 100%;
                text-resize: 100%;
            }
            .email-wrapper .row {
                font-size: 13px;
                margin-bottom: 5px;
            }
            .email-wrapper .row span {
                font-weight: bold;
            }
            .email-wrapper table.products {
                font-size: 13px;
                font-weight: normal;
            }
            .email-wrapper table.products tr {
                font-weight: bold;
                text-align: center;
                padding: 5px 10px;
                border-bottom: 1px solid #ccc;
            }
            .email-wrapper table.products td {
                text-align: left;
                padding: 5px 10px;
            }
            .email-wrapper table.products td.numeric {
                text-align: right;
            }
            .email-wrapper table.products td.total {
                text-align: right;
                font-size: 14px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="wrapper email-wrapper">

            @yield('content')

        </div>
    </body>
</html>