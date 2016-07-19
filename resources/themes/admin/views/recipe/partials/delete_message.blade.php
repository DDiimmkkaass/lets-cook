@if (count($menus))
    @lang('messages.you can not delete this recipe as it is used in the following menu'):
    <br>
    @foreach($menus as $menu)
        <div>
            <a class="popup-delete-button"
               title="@lang('labels.go_to_menu') {!! $menu->getWeekDates() !!}"
               target="_blank"
               href="{!! route('admin.weekly_menu.edit', $menu->id) !!}">
                @lang('labels.menu_of') {!! $menu->getWeekDates() !!}
            </a>
        </div>
    @endforeach
@else
    @lang('messages.your really want to delete this record')
@endif