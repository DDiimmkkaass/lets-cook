@include('group.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-xs-5">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('labels.tab_general')</h3>
            </div>

            <div class="box-body">
                @include('views.group.tabs.locale')
            </div>
        </div>
    </div>

    <div class="col-xs-7">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('labels.tab_permissions')</h3>
            </div>

            <div class="box-body tree">
                @include('views.group.tabs.permissions', ['path' => ''])
            </div>
        </div>
    </div>
</div>

@include('group.partials._buttons')