<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a aria-expanded="false" href="#info" data-toggle="tab">@lang('labels.tab_info')</a>
        </li>

        @if ($model->exists)
            <li>
                <a aria-expanded="false" href="#coupons" data-toggle="tab">@lang('labels.tab_coupons')</a>
            </li>
        @endif

        <li class="@if ($errors->has('groups')) tab-with-errors @endif">
            <a aria-expanded="false" href="#groups" data-toggle="tab">@lang('labels.tab_groups')</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="info">
            @include('user.tabs.info')
        </div>

        @if ($model->exists)
            <div class="tab-pane" id="coupons">
                @include('user.tabs.coupons')
            </div>
        @endif

        <div class="tab-pane" id="groups">
            @include('user.tabs.groups')
        </div>
    </div>
</div>
