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
                        <i class="fa fa-bars"></i>
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

            @if ($user->hasAccess('variablevalue.read'))
                <li class="{!! active_class('admin.variable*') !!}">
                    <a href="{!! route('admin.variable.value.index') !!}">
                        <i class="fa fa-cog"></i>
                        <span>@lang('labels.variables')</span>
                    </a>
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