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

            @if ($user->hasAccess('tag.read'))
                <li class="{!! active_class('admin.tag*') !!}">
                    <a href="{!! route('admin.tag.index') !!}">
                        <i class="fa fa-tags"></i>
                        <span>@lang('labels.tags')</span>

                        @if ($user->hasAccess('tag.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_tag')"
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
                        <i class="fa fa-newspaper-o"></i>
                        <span>@lang('labels.news')</span>

                        @if ($user->hasAccess('news.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_news')"
                                   data-href="{!! route('admin.news.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('question.read'))
                <li class="{!! active_class('admin.question*') !!}">
                    <a href="{!! route('admin.question.index') !!}">
                        <i class="fa fa-question-circle"></i>
                        <span>@lang('labels.questions')</span>

                        @if ($user->hasAccess('question.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_question')"
                                   data-href="{!! route('admin.question.create') !!}">
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
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_menu')"
                                   data-href="{!! route('admin.menu.create') !!}">
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

            @if ($user->hasAccess('textwidget.read'))
                <li class="{!! active_class('admin.text_widget*') !!}">
                    <a href="{!! route('admin.text_widget.index') !!}">
                        <i class="fa fa-font"></i>
                        <span>@lang('labels.text_widgets')</span>

                        @if ($user->hasAccess('textwidget.create'))
                            <small class="label create-label pull-right bg-green"
                                   title="@lang('labels.add_text_widget')"
                                   data-href="{!! route('admin.text_widget.create') !!}">
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

            @if ($user->hasAccess('comments.read'))
                <li class="{!! active_class('admin.comment*') !!}">
                    <a href="{!! route('admin.comment.index') !!}">
                        <i class="fa fa-comment"></i>
                        <span>@lang('labels.comments')</span>
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