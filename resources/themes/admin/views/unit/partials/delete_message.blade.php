@lang('messages.your really want to delete this record')
<br>
@lang('messages.after removing some ingredients remain without unit')
<br>
<a class="popup-delete-button"
   title="@lang('labels.go_to_ingredients_list')"
   target="_blank"
   href="{!! route('admin.ingredient.incomplete', ['unit' => $unit_id]) !!}">
    @lang('labels.view_ingredients_list')
</a>