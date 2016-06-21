<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
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

            <li class="header">@lang('labels.settings')</li>
            @if ($user->hasAccess('settings.translations'))
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
        </ul>
    </section>
</aside>