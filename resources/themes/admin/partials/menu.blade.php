<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">@lang('labels.menu')</li>
            @if ($user->hasAccess('category.read'))
                <li class="{!! active_class('admin.category*') !!}">
                    <a href="{!! route('admin.category.index') !!}">
                        <i class="fa fa-sitemap"></i>
                        <span>@lang('labels.categories')</span>

                        @if ($user->hasAccess('category.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_category')"
                                   data-href="{!! route('admin.category.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('unit.read'))
                <li class="{!! active_class('admin.unit*') !!}">
                    <a href="{!! route('admin.unit.index') !!}">
                        <i class="fa fa-balance-scale"></i>
                        <span>@lang('labels.units')</span>

                        @if ($user->hasAccess('unit.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_unit')"
                                   data-href="{!! route('admin.unit.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('parameter.read'))
                <li class="{!! active_class('admin.parameter*') !!}">
                    <a href="{!! route('admin.parameter.index') !!}">
                        <i class="fa fa-check-square-o"></i>
                        <span>@lang('labels.parameters')</span>

                        @if ($user->hasAccess('parameter.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_parameter')"
                                   data-href="{!! route('admin.parameter.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('nutritional_value.read'))
                <li class="{!! active_class('admin.nutritional_value*') !!}">
                    <a href="{!! route('admin.nutritional_value.index') !!}">
                        <i class="fa fa-tachometer"></i>
                        <span>@lang('labels.nutritional_values')</span>

                        @if ($user->hasAccess('nutritional_value.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_nutritional_value')"
                                   data-href="{!! route('admin.nutritional_value.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('ingredient.read'))
                <li class="{!! active_class('admin.ingredient*', 'active', 'admin.ingredient.incomplete*') !!}">
                    <a href="{!! route('admin.ingredient.index') !!}">
                        <i class="icon-carrot"></i>
                        <span>@lang('labels.ingredients')</span>

                        @if ($user->hasAccess('ingredient.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_ingredient')"
                                   data-href="{!! route('admin.ingredient.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{!! active_class('admin.ingredient.incomplete*') !!}">
                    <a href="{!! route('admin.ingredient.incomplete') !!}">
                        <i class="fa fa-frown-o"></i>
                        <span>@lang('labels.incomplete_ingredients')</span>
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('basket.read'))
                <li class="{!! in_get('type', 'basic') ? 'active' : '' !!}">
                    <a href="{!! route('admin.basket.index', ['type' => 'basic']) !!}">
                        <i class="fa fa-shopping-basket"></i>
                        <span>@lang('labels.baskets')</span>

                        @if ($user->hasAccess('basket.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_basket')"
                                   data-href="{!! route('admin.basket.create', ['type' => 'basic']) !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{!! in_get('type', 'additional') ? 'active' : '' !!}">
                    <a href="{!! route('admin.basket.index', ['type' => 'additional']) !!}">
                        <span class="icon-shoppingbag"></span>
                        <span>@lang('labels.additional_baskets')</span>

                        @if ($user->hasAccess('basket.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_additional_baskets')"
                                   data-href="{!! route('admin.basket.create', ['type' => 'additional']) !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('recipe.read'))
                <li class="{!! active_class('admin.recipe*') !!}">
                    <a href="{!! route('admin.recipe.index') !!}">
                        <i class="icon-chef"></i>
                        <span>@lang('labels.recipes')</span>

                        @if ($user->hasAccess('recipe.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_recipe')"
                                   data-href="{!! route('admin.recipe.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('weeklymenu.read'))
                <li class="{!! active_class('admin.weekly_menu.current*') !!}">
                    <a href="{!! route('admin.weekly_menu.current') !!}">
                        <i class="icon-list-alt"></i>
                        <span>@lang('labels.current_week_menu')</span>
                    </a>
                </li>

                <li class="{!! active_class('admin.weekly_menu*', 'active', 'admin.weekly_menu.current*') !!}">
                    <a href="{!! route('admin.weekly_menu.index') !!}">
                        <i class="fa fa-list"></i>
                        <span>@lang('labels.weekly_menus')</span>

                        @if ($user->hasAccess('weeklymenu.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.weekly_menus')"
                                   data-href="{!! route('admin.weekly_menu.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.orders')</li>
            @if ($user->hasAccess('coupon.read'))
                <li class="{!! active_class('admin.coupon*') !!}">
                    <a href="{!! route('admin.coupon.index') !!}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span>@lang('labels.coupons')</span>

                        @if ($user->hasAccess('coupon.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_coupon')"
                                   data-href="{!! route('admin.coupon.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('order.read'))
                <li class="{!! active_class('admin.order*', 'active', ['admin.order.history*']) !!}">
                    <a href="{!! route('admin.order.index') !!}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span>@lang('labels.orders')</span>

                        @if ($user->hasAccess('order.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_order')"
                                   data-href="{!! route('admin.order.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{!! active_class('admin.order.history') !!}">
                    <a href="{!! route('admin.order.history') !!}">
                        <i class="fa fa-history" aria-hidden="true"></i>
                        <span>@lang('labels.orders_history')</span>
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.purchase')</li>
            @if ($user->hasAccess('supplier.read'))
                <li class="{!! active_class('admin.supplier*') !!}">
                    <a href="{!! route('admin.supplier.index') !!}">
                        <i class="fa fa-truck"></i>
                        <span>@lang('labels.suppliers')</span>

                        @if ($user->hasAccess('supplier.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_supplier')"
                                   data-href="{!! route('admin.supplier.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('purchase.read'))
                <li class="{!! active_class('admin.purchase.edit') !!}">
                    <a href="{!! route('admin.purchase.edit', [active_week()->year, active_week()->weekOfYear]) !!}">
                        <i class="fa fa-list-ol"></i>
                        <span>@lang('labels.list_of_purchasing')</span>
                    </a>
                </li>
                <li class="{!! active_class('admin.purchase*', 'active', 'admin.purchase.edit*') !!}">
                    <a href="{!! route('admin.purchase.index') !!}">
                        <i class="fa fa-history"></i>
                        <span>@lang('labels.history_of_purchasing')</span>
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.packaging')</li>
            @if ($user->hasAccess('packaging.read'))
                <li class="{!! active_class('admin.packaging.current') !!}">
                    <a href="{!! route('admin.packaging.current') !!}">
                        <i class="fa fa-list-ol"></i>
                        <span>@lang('labels.list_of_packaging')</span>
                    </a>
                </li>
                <li class="{!! active_class('admin.packaging*', 'active', 'admin.packaging.current*') !!}">
                    <a href="{!! route('admin.packaging.index') !!}">
                        <i class="fa fa-history"></i>
                        <span>@lang('labels.history_of_packaging')</span>
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.content')</li>
            @if ($user->hasAccess('page.read'))
                <li class="{!! active_class('admin.page*') !!}">
                    <a href="{!! route('admin.page.index') !!}">
                        <i class="fa fa-file-text"></i>
                        <span>@lang('labels.pages')</span>

                        @if ($user->hasAccess('page.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_page')"
                                   data-href="{!! route('admin.page.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('tagcategory.read'))
                <li class="{!! active_class('admin.tag_category*') !!}">
                    <a href="{!! route('admin.tag_category.index') !!}">
                        <i class="fa fa-sitemap"></i>
                        <span>@lang('labels.tag_categories')</span>

                        @if ($user->hasAccess('tagcategory.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_tag_category')"
                                   data-href="{!! route('admin.tag_category.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('tag.read'))
                <li class="{!! active_class('admin.tag*', 'active', 'admin.tag_category*') !!}">
                    <a href="{!! route('admin.tag.index') !!}">
                        <i class="fa fa-tags"></i>
                        <span>@lang('labels.tags')</span>

                        @if ($user->hasAccess('tag.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add')"
                                   data-href="{!! route('admin.tag.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('news.read'))
                <li class="{!! active_class('admin.news*') !!}">
                    <a href="{!! route('admin.news.index') !!}">
                        <i class="fa fa-rss"></i>
                        <span>@lang('labels.blog')</span>

                        @if ($user->hasAccess('news.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_news')"
                                   data-href="{!! route('admin.news.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('article.read'))
                <li class="{!! active_class('admin.article*') !!}">
                    <a href="{!! route('admin.article.index') !!}">
                        <i class="fa fa-newspaper-o"></i>
                        <span>@lang('labels.articles')</span>

                        @if ($user->hasAccess('article.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_article')"
                                   data-href="{!! route('admin.article.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('comment.read'))
                <li class="{!! active_class('admin.comment*') !!}">
                    <a href="{!! route('admin.comment.index') !!}">
                        <i class="fa fa-comments-o"></i>
                        <span>@lang('labels.comments')</span>

                        @if ($user->hasAccess('comment.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_comment')"
                                   data-href="{!! route('admin.comment.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('city.read'))
                <li class="{!! active_class('admin.city*') !!}">
                    <a href="{!! route('admin.city.index') !!}">
                        <i class="fa fa-map-marker"></i>
                        <span>@lang('labels.cities')</span>

                        @if ($user->hasAccess('city.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_city')"
                                   data-href="{!! route('admin.city.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('banner.read'))
                <li class="{!! active_class('admin.banner*') !!}">
                    <a href="{!! route('admin.banner.index') !!}">
                        <i class="fa fa-picture-o"></i>
                        <span>@lang('labels.banners')</span>

                        @if ($user->hasAccess('banner.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_banner')"
                                   data-href="{!! route('admin.banner.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('menu.read'))
                <li class="{!! active_class('admin.menu*') !!}">
                    <a href="{!! route('admin.menu.index') !!}">
                        <i class="fa fa-bars"></i>
                        <span>@lang('labels.menus')</span>

                        @if ($user->hasAccess('menu.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add')"
                                   data-href="{!! route('admin.menu.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('variablevalue.read'))
                <li class="{!! active_class('admin.variable*') !!}">
                    <a href="{!! route('admin.variable.value.index') !!}">
                        <i class="fa fa-cog"></i>
                        <span>@lang('labels.variables')</span>
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('translation.read'))
                <li class="treeview {!! active_class('admin.translation.index*') !!}">
                    <a href="#">
                        <i class="fa fa-language"></i>
                        <span>@lang('labels.translations')</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @foreach($translation_groups as $group)
                            <li class="{!! front_active_class(route('admin.translation.index', $group)) !!}">
                                <a href="{!! route('admin.translation.index', $group) !!}">
                                    <span>@lang('labels.translation_group_' . $group)</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif

            @if ($user->hasAccess('group') || $user->hasAccess('user.read'))
                <li class="header">@lang('labels.users')</li>
            @endif
            @if ($user->hasAccess('user.read'))
                <li class="{!! active_class('admin.user.index*') !!}">
                    <a href="{!! route('admin.user.index') !!}">
                        <i class="fa fa-user"></i>
                        <span>@lang('labels.users')</span>

                        @if ($user->hasAccess('user.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_user')"
                                   data-href="{!! route('admin.user.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('group'))
                <li class="{!! active_class('admin.group.index*') !!}">
                    <a href="{!! route('admin.group.index') !!}">
                        <i class="fa fa-users"></i>
                        <span>@lang('labels.groups')</span>

                        @if ($user->hasAccess('group.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_group')"
                                   data-href="{!! route('admin.group.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
        </ul>
    </section>
</aside>