<div class="footer__section-2 social-info">
    <div class="social-info__list ya-share2"
         data-services="facebook,vkontakte,gplus,odnoklassniki"
         data-lang="{!! $lang !!}"
         @unless (empty($home_page))
             data-title="{!! $home_page->getMetaTitle() !!}"
             data-description="{!! $home_page->getMetaDescription() !!}"
             data-image="{!! $home_page->getMetaImage() !!}"
             data-url="{!! $home_page->getUrl() !!}"
         @endunless
    ></div>
</div>