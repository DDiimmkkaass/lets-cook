@if (!empty($date))
    @php($in_format = isset($in_format) ? $in_format : 'Y-m-d H:i:s')
    @php($time_format = isset($time_format) ? $time_format : false)
    @php($time_position = isset($time_position) ? $time_position : 'after')

    <div class="nowrap {!! isset($class) ? $class : '' !!}">{!! get_localized_date($date, $in_format, $time_format, $time_position) !!}</div>
@endif