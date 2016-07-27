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
                    <a aria-expanded="false" href="#tab_recipe" data-toggle="tab">@lang('labels.tab_recipe')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#steps" data-toggle="tab">@lang('labels.tab_cooking_steps')</a>
                </li>
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

                <div class="tab-pane" id="tab_recipe">
                    @include('recipe.tabs.recipe')
                </div>

                <div class="tab-pane" id="steps">
                    @include('recipe.tabs.steps')
                </div>
            </div>
        </div>

    </div>
</div>

@include('recipe.partials._buttons')