@include('order.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#delivery" data-toggle="tab">@lang('labels.tab_delivery')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#recipes" data-toggle="tab">@lang('labels.tab_recipes')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#additional_baskets" data-toggle="tab">@lang('labels.tab_additional_baskets')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#ingredients" data-toggle="tab">@lang('labels.tab_ingredients')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#coupons" data-toggle="tab">@lang('labels.tab_discount_coupons')</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('order.tabs.general')
                </div>

                <div class="tab-pane" id="delivery">
                    @include('order.tabs.delivery')
                </div>

                <div class="tab-pane" id="recipes">
                    @include('order.tabs.recipes')
                </div>

                <div class="tab-pane" id="additional_baskets">
                    @include('order.tabs.additional_baskets')
                </div>

                <div class="tab-pane" id="ingredients">
                    @include('order.tabs.ingredients')
                </div>

                <div class="tab-pane" id="coupons">
                    @include('order.tabs.coupons')
                </div>
            </div>
        </div>

        @include('order.tabs.comments')

    </div>
</div>

@include('order.partials._buttons')