<div class="modal-dialog">
    <div class="modal-content">
        <form class="basket-select-form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('labels.close')">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('labels.basket_select_popup_title')</h4>
            </div>
            <div class="modal-body">
                <div class="form-group required">
                    <label for="basket_id" class="control-label">@lang('labels.basket')</label>

                    <select name="basket_id"
                            id="basket_id"
                            required="required"
                            class="form-control select2 basket-select"
                            arial-hidden="true">
                        @foreach($baskets as $basket)
                            <option value="{!! $basket->id !!}">
                                {!! $basket->name !!}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group required">
                    <label for="portions" class="control-label">@lang('labels.portions')</label>

                    <input class="form-control input-sm" type="text" name="portions" id="portions" required="required" value="{!! config('weekly_menu.default_portions_count') !!}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" title="@lang('labels.close_window')" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">
                    @lang('labels.cancel')
                </button>

                <button type="button" title="@lang('labels.add')" class="btn btn-success btn-flat btn-sm weekly-menu-add-basket">
                    @lang('labels.add')
                </button>
            </div>
        </form>
    </div>
</div>