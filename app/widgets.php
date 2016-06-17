<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 03.02.16
 * Time: 14:06
 */

Widget::register('widget__text_widget', 'App\Widgets\TextWidget\TextWidgetWidget@index');

Widget::register('widget__tags_filter', 'App\Widgets\TagsFilter\TagsFilterWidget@index');

Widget::register('widget__search_form', 'App\Widgets\SearchForm\SearchFormWidget@index');

Widget::register('widget__like_button', 'App\Widgets\LikeButton\LikeButtonWidget@index');

Widget::register('widget__menu', 'App\Widgets\Menu\MenuWidget@index');

Widget::register('widget__random_news', 'App\Widgets\RandomNews\RandomNewsWidget@index');

Widget::register('widget__banner', 'App\Widgets\Banner\BannerWidget@index');

Widget::register('widget__last_news', 'App\Widgets\LastNews\LastNewsWidget@index');