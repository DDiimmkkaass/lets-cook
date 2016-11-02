@extends('layouts.profile')

@section('_content')

    <section class="profile-edit__section profile-edit-section card-create-section">
        {!! Form::model($card, ['route' => 'profiles.cards.store', 'method' => 'post', 'class' => 'card-create-form']) !!}

        @include('card.partials.form', ['button_type' => 'save-card'])

        {!! Form::close() !!}
    </section>

@endsection