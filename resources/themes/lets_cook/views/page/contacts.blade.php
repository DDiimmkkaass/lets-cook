@extends('layouts.master')

@section('content')

    <main class="main contacts-simple">
        <h1 class="contacts-simple__title static-georgia-title">Контакты</h1>

        <div class="contacts-simple__info">
            <ul class="contacts-simple__info-wrapper">
                <li class="contacts-simple__info-col">
                    @php($address = variable('address'))
                    @if ($address)
                        <div class="contacts-simple__info-row" data-contacts="address">
                            {!! $address !!}
                        </div>
                    @endif

                    @php($work_time = variable('work_time'))
                    @php($work_days = variable('work_days'))
                    @if ($work_time || $work_days)
                        <div class="contacts-simple__info-row" data-contacts="clock">
                            @if ($work_time)
                                <span class="contacts-simple__info-span">{!! $work_time !!}</span>
                            @endif
                            @if ($work_days)
                                <span class="contacts-simple__info-span">{!! $work_days !!}</span>
                            @endif
                        </div>
                    @endif
                </li>

                <li class="contacts-simple__info-col">
                    <div class="contacts-simple__info-row" data-contacts="phone">
                        @php($moscow_phone = variable('moscow_phone'))
                        @if ($moscow_phone)
                            <a class="contacts-simple__info-link"
                               href="tel:{!! preg_replace('/\D/', '', $moscow_phone) !!}">
                                {!! $moscow_phone !!}
                            </a>
                        @endif

                        @php($phone_2 = variable('phone_2'))
                        @if ($phone_2)
                            <a class="contacts-simple__info-link"
                               href="tel:{!! preg_replace('/\D/', '', $phone_2) !!}">
                                {!! $phone_2 !!}
                            </a>
                        @endif

                        @php($phone_3 = variable('phone_3'))
                        @if ($phone_3)
                            <a class="contacts-simple__info-link"
                               href="tel:{!! preg_replace('/\D/', '', $phone_3) !!}">
                                {!! $phone_3 !!}
                            </a>
                        @endif
                    </div>
                </li>

                <li class="contacts-simple__info-col">
                    @php($contact_email = variable('contact_email'))
                    @if ($contact_email)
                        <div class="contacts-simple__info-row" data-contacts="mail">
                            <a class="contacts-simple__info-link" href="mailto:{!! $contact_email !!}">
                                {!! $contact_email !!}</a>
                        </div>
                    @endif

                    @php($skype_name = variable('skype_name'))
                    @if ($skype_name)
                        <div class="contacts-simple__info-row" data-contacts="skype">
                            <a class="contacts-simple__info-link" href="skype:{!! $skype_name !!}?call">
                                {!! $skype_name !!}
                            </a>
                        </div>
                    @endif
                </li>
            </ul>
        </div>

        <div id="js-contacts-map" class="contacts-simple__map"></div>
        <script>
            function initMap() {
                var mapDiv = document.getElementById('js-contacts-map');
                var map = new google.maps.Map(mapDiv, {
                    center: {lat: 55.7494733, lng: 37.61232},
                    zoom: 10
                });
                var currCenter = map.getCenter();
                var marker = new google.maps.Marker({
                    position: {lat: 55.7494733, lng: 37.61232},
                    map: map,
                    icon: 'images/contacts-map-icon.png'
                });

                window.addEventListener('resize', function () {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(currCenter);
                });
            }
        </script>
    </main>

@endsection

@push('assets.bottom')
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMYhcceX0BQFFKq7sn5MI2VeL6XwYla8Q&callback=initMap">
</script>
@endpush