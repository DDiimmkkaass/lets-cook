<footer class="footer">
    @widget__menu('footer_additional')

    @include('partials.modules.social_counters')

    <div class="footer__section-3 copy-info">
        <span>© 2013-{!! carbon()->now()->year !!} {!! variable('full_company_name') !!}.</span>
        <span>&nbsp;Все права защищены.</span>
    </div>
</footer>