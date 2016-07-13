@include('weekly_menu.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-body">

                @include('weekly_menu.tabs.menu_options')

                <div class="get-basket-select-popup pull-right margin-bottom-15 btn btn-primary btn-flat btn-sm">
                    @lang('labels.add_basket')
                </div>

                <div class="clearfix"></div>

                @include('weekly_menu.partials.tabs')

            </div>
        </div>

    </div>
</div>

@include('weekly_menu.partials._buttons')