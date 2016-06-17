@include('user.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                @include('partials.tabs.user_avatar')

                <h3 class="profile-username text-center">{!! $model->getFullName() !!}</h3>

                @if (!empty($model->groups()->first()))
                    <p class="text-muted text-center">{!! $model->groups()->first()->name !!} </p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-9">
        @include('user.partials._tabs')
    </div>
</div>

@include('user.partials._buttons')
