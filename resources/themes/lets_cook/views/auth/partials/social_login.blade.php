@if (config('oauth-5-laravel.consumers.Vkontakte.client_id') || config('oauth-5-laravel.consumers.Facebook.client_id'))
<div class="social-login">
    @if (config('oauth-5-laravel.consumers.Vkontakte.client_id'))
        <a class="social-login-item vkontakte" href="{!! localize_route('auth.social', 'vkontakte') !!}"></a>
    @endif

    @if (config('oauth-5-laravel.consumers.Facebook.client_id'))
        <a class="social-login-item facebook" href="{!! localize_route('auth.social', 'facebook') !!}"></a>
    @endif
</div>
@endif