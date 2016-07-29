<div class="tab-pane" id="basket_{!! $basket->id !!}_{!! $portions !!}">

    <div class="recipes-add-control">
        <div class="form-group margin-bottom-25">
            <div class="col-xs-12 margin-bottom-5 font-size-16 text-left">
                @lang('labels.recipes')
            </div>
            <div class="col-sm-12 full-width-select">
                <select class="form-control select2 menu-recipe-select input-sm add-recipe" aria-hidden="true" data-portions="{!! $portions !!}" data-basket="{!! $basket->id !!}">
                    <option value="">@lang('labels.please_select_recipe')</option>
                    @foreach($recipes as $recipe)
                        <option value="{!! $recipe->id !!}">{!! $recipe->name !!} (@lang('labels.portions_lowercase'): {!! $recipe->portions !!})</option>
                    @endforeach
                </select>

                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][name]" value="{!! $basket->name !!}">
                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][id]" value="{!! $basket->id !!}">
                <input type="hidden" name="baskets[{!! $basket->id !!}_{!! $portions !!}][portions]" value="{!! $portions !!}">
            </div>
        </div>
    </div>

    <h4 class="main-recipe-helper-message col-am-12 margin-bottom-15">
        @lang('messages.click on recipe for make him main')
    </h4>

    <div id="basket_recipes_{!! $basket->id !!}_{!! $portions !!}" class="menu-recipes-table">

    </div>

    <div class="clearfix"></div>

    @include('weekly_menu.partials.remove_basket_button')

</div>