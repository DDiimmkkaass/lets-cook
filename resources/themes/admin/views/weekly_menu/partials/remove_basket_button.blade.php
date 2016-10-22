<div class="weekly-menu-basket-remove pull-left btn btn-default btn-sm btn-flat"
     data-id="{!! isset($basket->id) ? $basket->id : 'new' !!}">
    @lang('labels.remove_basket')

    <input type="hidden" name="weekly_menu_basket_id" value="{!! isset($basket->id) ? $basket->id : 'new' !!}">
</div>

<div class="clearfix"></div>