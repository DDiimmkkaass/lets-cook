<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="@lang('labels.close')">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">@lang('labels.quick_ingredient_create_title')</h4>
        </div>
        <div class="modal-body">
            {!! Form::open(['id' => 'ingredient_quick_form', 'role' => 'form', 'class' => 'ingredient-quick-form', 'route' => ['admin.ingredient.quick_store']]) !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a aria-expanded="false" href="#ingredient_general" data-toggle="tab">@lang('labels.tab_general')</a>
                    </li>

                    <li>
                        <a aria-expanded="false" href="#ingredient_additional" data-toggle="tab">@lang('labels.tab_additional')</a>
                    </li>

                    <li>
                        <a aria-expanded="false" href="#ingredient_parameters" data-toggle="tab">@lang('labels.tab_parameters')</a>
                    </li>

                    <li>
                        <a aria-expanded="false" href="#ingredient_nutritional_values" data-toggle="tab">
                            @lang('labels.tab_nutritional_values')
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="ingredient_general">
                        @include('ingredient.tabs.quick_form.general')
                    </div>

                    <div class="tab-pane" id="ingredient_additional">
                        @include('ingredient.tabs.quick_form.additional')
                    </div>

                    <div class="tab-pane" id="ingredient_parameters">
                        @include('ingredient.tabs.quick_form.parameters')
                    </div>

                    <div class="tab-pane" id="ingredient_nutritional_values">
                        @include('ingredient.tabs.quick_form.nutritional_values')
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
        <div class="modal-footer">
            <button type="button" title="@lang('labels.close_window')" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">@lang('labels.cancel')</button>
            <button type="button" title="@lang('labels.save')" class="btn btn-success btn-flat btn-sm ingredient-quick-store">@lang('labels.save')</button>
        </div>
    </div>
</div>