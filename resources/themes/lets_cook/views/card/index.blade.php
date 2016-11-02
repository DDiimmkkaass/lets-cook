@extends('layouts.profile')

@section('_content')

    <ul class="profile-subscribe__table subscribe-table  @if (!$cards->count()) h-flex @endif">
        @if ($cards->count())
            @foreach($cards as $card)
                <li class="subscribe-table__row">
                    <div class="subscribe-table__basket h-card-number-col">
                        <span>
                            {!! $card->name !!}
                        </span>
                    </div>

                    <div class="subscribe-table__basket h-card-number-col">
                        <span>
                            @if ($card->number) ****{!! $card->number !!} @endif
                        </span>
                    </div>

                    <div class="subscribe-table__when">
                        {!! empty($card->invoice_id) ? 'Не подключена' : '' !!}
                    </div>

                    <div class="subscribe-table__when">
                        {!! $card->default ? 'По умолчанию' : '' !!}
                    </div>

                    <div class="subscribe-table__functional">
                        <a href="{!! localize_route('profiles.cards.edit', $card->id) !!}"
                           class="subscribe-table__change h-margin-right-10">
                            Изменить
                        </a>
                        <div title="Удалить" class="subscribe-table__delete delete-card"
                             data-card_id="{!! $card->id !!}"
                             data-token="{!! csrf_token() !!}">
                        </div>
                        @if (empty($card->invoice_id))
                            <a href="#"
                               data-card_id="{!! $card->id !!}"
                               data-token="{!! csrf_token() !!}"
                               class="subscribe-table__connect h-margin-left-10 connect-card">
                                Подключить
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
        @else
            <li class="subscribe-table__row h-no-user-cards">
                Вы пока не зарегистрировали ни одной карты
            </li>
        @endif
    </ul>

    <section class="profile-main__contacts profile-contacts">
        <div class="profile-contacts__buttons">
            <a href="{!! localize_route('profiles.cards.create') !!}"
               class="profile-contacts__buttons-link black-long-button">
                Добавить карту
            </a>
        </div>
    </section>

@endsection