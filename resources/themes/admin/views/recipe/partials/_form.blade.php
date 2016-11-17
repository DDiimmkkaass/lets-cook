@include('recipe.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#ingredients" data-toggle="tab">@lang('labels.tab_ingredients')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#ingredients_home" data-toggle="tab">@lang('labels.tab_ingredients_home')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#steps" data-toggle="tab">@lang('labels.tab_cooking_steps')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#tags" data-toggle="tab">@lang('labels.tab_tags')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#meta" data-toggle="tab">@lang('labels.tab_meta')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#files" data-toggle="tab">@lang('labels.tab_files')</a>
                </li>

                @if ($model->exists && !isset($copy))
                    <li>
                        <a aria-expanded="false" href="#statistic_of_orders_uses" data-toggle="tab">
                            @lang('labels.tab_statistic_of_orders_uses')
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('recipe.tabs.general')
                </div>

                <div class="tab-pane" id="ingredients">
                    @include('recipe.tabs.ingredients')
                </div>

                <div class="tab-pane" id="ingredients_home">
                    @include('recipe.tabs.ingredients_home')
                </div>

                <div class="tab-pane" id="steps">
                    @include('recipe.tabs.steps')
                </div>

                <div class="tab-pane" id="tags">
                    @include('recipe.tabs.tags')
                </div>

                <div class="tab-pane" id="meta">
                    @include('recipe.tabs.meta')
                </div>

                <div class="tab-pane" id="files">
                    @include('recipe.tabs.files')
                </div>

                @if ($model->exists && !isset($copy))
                    <div class="tab-pane" id="statistic_of_orders_uses">
                        @include('recipe.tabs.statistic')
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('recipe.partials._buttons')