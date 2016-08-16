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
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <h4 class="margin-top-10 margin-bottom-0">@lang('labels.comments')</h4>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <div class="col-xs-12">
                        {!! Form::textarea('admin_comment', null, ['rows' => '4', 'placeholder' => trans('labels.comments'), 'class' => 'form-control input-sm height-auto']) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('order.partials._buttons')