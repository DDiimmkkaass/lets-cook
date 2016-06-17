<header class="main-header">
    <div class="logo position-relative">
        <a href="{!! route('admin.home') !!}" class="logo-link">
            <span class="logo-mini upper-case">{!! str_limit(config('app.name'), 3, '') !!}</span>

            <span class="logo-lg upper-case">
                {!! config('app.name') !!}
            </span>
        </a>

        <div class="front-home-link" data-href="{!! route('home') !!}" title="@lang('labels.go_to_front')">
            <i class="fa fa-external-link"></i>
        </div>
    </div>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">@lang('labels.toggle_navigation')</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @include('partials.image', ['src' => $user->avatar, 'attributes' => ['width' => 160, 'height' => 160, 'class' => 'user-image']])

                        <span class="hidden-xs">{!! $user->getFullName() !!}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            @include('partials.image', ['src' => $user->avatar, 'attributes' => ['width' => 160, 'height' => 160, 'class' => 'img-circle']])
                            <p>
                                {!! $user->getFullName() !!} - {!! $user->groups()->first()->name !!}
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{!! route('admin.user.edit', $user->id) !!}" class="btn btn-default btn-sm btn-flat">@lang('labels.profile')</a>
                            </div>
                            <div class="pull-right">
                                <a href="{!! route('admin.logout') !!}" class="btn btn-default btn-sm btn-flat">@lang('labels.sign_out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

