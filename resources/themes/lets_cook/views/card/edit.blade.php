@extends('layouts.profile')

@section('_content')

    <section class="profile-edit__section profile-edit-section card-edit-section">
        {!! Form::model($card, ['route' => ['profiles.cards.update', $card->id], 'method' => 'post', 'class' => 'card-edit-form']) !!}

        @include('card.partials.form')

        {!! Form::close() !!}
    </section>

@endsection