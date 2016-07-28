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

                    <select class="form-control select2 basket-select" name="portions" id="portions" required="required">
                        @foreach(config('recipe.available_portions') as $portion)
                            <option value="{!! $portion !!}"
                                @if (config('weekly_menu.default_portions_count') == $portion) selected="selected" @endif
                            >
                                {!! $portion !!} @choice('labels.count_of_portions', $portion)
                            </option>
                        @endforeach
                    </select>

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