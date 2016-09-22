<script type="text/javascript">window.messages_timeout = 0;</script>

@if (isset($messages))
    @if ($messages->count())
        @foreach ($messages->getMessages() as $key => $message)
            @foreach ($message as $mess)
                <script type="text/javascript">
                    $(document).ready(function () {
                        window.messages_timeout = parseInt(window.messages_timeout) + 500;
                        setTimeout("popUp(lang_" + '{!! $key !!}' + ", '" + '{!! $mess !!}' + "')", window.messages_timeout);
                    });
                </script>
            @endforeach
        @endforeach
    @endif
@endif

