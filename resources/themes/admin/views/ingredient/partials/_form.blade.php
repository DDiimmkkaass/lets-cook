@include('ingredient.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#additional" data-toggle="tab">@lang('labels.tab_additional')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#parameters" data-toggle="tab">@lang('labels.tab_parameters')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#nutritional_values" data-toggle="tab">
                        @lang('labels.tab_nutritional_values')
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('ingredient.tabs.general')
                </div>

                <div class="tab-pane" id="additional">
                    @include('ingredient.tabs.additional')
                </div>

                <div class="tab-pane" id="parameters">
                    @include('ingredient.tabs.parameters')
                </div>

                <div class="tab-pane" id="nutritional_values">
                    @include('ingredient.tabs.nutritional_values')
                </div>
            </div>
        </div>

    </div>
</div>

@include('ingredient.partials._buttons')