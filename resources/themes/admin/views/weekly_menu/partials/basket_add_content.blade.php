<div class="tab-pane" id="basket_{!! $basket->id !!}_{!! $portions !!}">
    <div class="weekly-menu-prices-box margin-bottom-40 margin-top-10">
        <div class="form-group">
            <label class="col-sm-2 control-label">@lang('labels.basket_price'):</label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        @foreach($basket->getPrice($portions) as $day => $price)
                            <td class="text-center">
                                {!! $day . ' ' . trans_choice('labels.count_of_days', $day) !!}:
                                {!! $price !!} {!! $currency !!}

                                <input type="hidden"
                                       name="baskets[{!! $basket->id !!}_{!! $portions !!}][prices][{!! $day !!}]"
                                       value="{!! $day . ' ' . trans_choice('labels.count_of_days', $day) !!}: {!! $price !!} {!! $currency !!}">
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">@lang('labels.internal_price'):</label>
            <div class="col-sm-10">
                <div class="col-sm-1 with-after-helper currency-rub">
                    <input type="text" readonly="readonly" class="form-control input-sm basket-internal-price"
                           value="0">
                </div>
            </div>
        </div>
    </div>

    <h4 class="main-recipe-helper-message col-am-12 margin-bottom-15">
        @lang('messages.click on recipe for make him main')
    </h4>

    <div id="basket_recipes_{!! $basket->id !!}_{!! $portions !!}" class="menu-recipes-table margin-bottom-40">

    </div>

    <div class="clearfix"></div>

    <div class="recipes-add-control">
        <div class="form-group">
            <div class="col-sm-12">
                @include('weekly_menu.partials.recipes_select', ['basket_id' => $basket->id, 'portions' => $portions])

                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][name]"
                       value="{!! $basket->name !!}">
                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][id]"
                       value="{!! $basket->id !!}">
                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][portions]"
                       value="{!! $portions !!}">
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    @include('weekly_menu.partials.remove_basket_button')

</div>