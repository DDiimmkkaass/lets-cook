@include('coupon.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#tags" data-toggle="tab">@lang('labels.tab_tags')</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('coupon.tabs.general')
                </div>

                <div class="tab-pane" id="tags">
                    @include('coupon.tabs.tags')
                </div>
            </div>
        </div>

    </div>
</div>

@include('coupon.partials._buttons')