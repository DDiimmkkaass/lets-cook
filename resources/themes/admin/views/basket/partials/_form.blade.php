@include('basket.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                @if ($type == 'basic')
                    <li>
                        <a aria-expanded="false" href="#prices" data-toggle="tab">@lang('labels.tab_prices')</a>
                    </li>
                    <li>
                        <a aria-expanded="false" href="#places" data-toggle="tab">@lang('labels.tab_places')</a>
                    </li>
                @endif

                @if ($type == 'additional')
                    <li>
                        <a aria-expanded="false" href="#recipes" data-toggle="tab">@lang('labels.tab_recipes')</a>
                    </li>

                    <li>
                        <a aria-expanded="false" href="#tags" data-toggle="tab">@lang('labels.tab_tags')</a>
                    </li>
                @endif

            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('basket.tabs.general')
                </div>

                @if ($type == 'basic')
                    <div class="tab-pane" id="prices">
                        @include('basket.tabs.prices')
                    </div>
                    <div class="tab-pane" id="places">
                        @include('basket.tabs.places')
                    </div>
                @endif

                @if ($type == 'additional')
                    <div class="tab-pane" id="recipes">
                        @include('basket.tabs.recipes')
                    </div>

                    <div class="tab-pane" id="tags">
                        @include('basket.tabs.tags')
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('basket.partials._buttons')