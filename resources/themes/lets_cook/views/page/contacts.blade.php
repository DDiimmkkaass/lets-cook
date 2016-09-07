@extends('layouts.master')

@section('content')

    <main class="main contacts-simple">
        <h1 class="contacts-simple__title static-georgia-title">Контакты</h1>

        <div class="contacts-simple__info">
            <ul class="contacts-simple__info-wrapper">
                <li class="contacts-simple__info-col">
                    <div class="contacts-simple__info-row" data-contacts="address">
                        {!! variable('address') !!}
                    </div>
                    <div class="contacts-simple__info-row" data-contacts="clock">
                        <span class="contacts-simple__info-span">{!! variable('work_time') !!}</span>
                        <span class="contacts-simple__info-span">{!! variable('work_days') !!}</span>
                    </div>
                </li>

                <li class="contacts-simple__info-col">
                    <div class="contacts-simple__info-row" data-contacts="phone">
                        <a class="contacts-simple__info-link" href="tel:{!! preg_replace('/\D/', '', variable('moscow_phone')) !!}">
                            {!! variable('moscow_phone') !!}
                        </a>
                        <a class="contacts-simple__info-link" href="tel:{!! preg_replace('/\D/', '', variable('phone_2')) !!}">
                            {!! variable('phone_2') !!}
                        </a>
                        <a class="contacts-simple__info-link" href="tel:{!! preg_replace('/\D/', '', variable('phone_3')) !!}">
                            {!! variable('phone_3') !!}
                        </a>
                    </div>
                </li>

                <li class="contacts-simple__info-col">
                    <div class="contacts-simple__info-row" data-contacts="mail">
                        <a class="contacts-simple__info-link" href="mailto:{!! variable('contact_email') !!}">
                            {!! variable('contact_email') !!}</a>
                    </div>

                    <div class="contacts-simple__info-row" data-contacts="skype">
                        <a class="contacts-simple__info-link" href="skype:{!! variable('skype_name') !!}?call">
                            {!! variable('skype_name') !!}
                        </a>
                    </div>
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