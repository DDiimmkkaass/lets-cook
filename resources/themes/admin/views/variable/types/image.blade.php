@if ($item->multilingual)

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach (config('app.locales') as $key => $locale)
                <li @if ($key == 0) class="active" @endif>
                    <a aria-expanded="false" href="#tab_{!! $item->id !!}_{!! $locale !!}" data-toggle="tab">
                        <i class="flag flag-{!! $locale !!}"></i>
                        @lang('labels.tab_'.$locale)
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach (config('app.locales') as $key => $locale)
                <div class="tab-pane fade in @if ($key == 0) active @endif" id="tab_{!! $item->id !!}_{!! $locale !!}">
                    <div class="row form-group">
                        <div class="col-xs-12">
                            {!! Form::imageInput($locale.'[text]', isset($item->translate($locale)->text) ? $item->translate($locale)->text : '', ['id' => $item->id . '_' . $locale . '_text', 'data-related-image' => $item->id . '_' . $locale . '_text_image', 'class' => "form-control input-sm " . $item->id . '_' . $locale . '_text', 'data-target-input' => '.' . $item->id . '_' . $locale . '_text', 'elfinder-link-name' => $item->id . '_' . $locale . '_text_image']) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@else

    <div class="row form-group">
        <div class="col-xs-12">
            {!! Form::imageInput('value', $item->value, ['id' => 'value', 'data-related-image' => $item->id . '_value_image', 'class' => "form-control input-sm " . $item->id . '_value', 'data-target-input' => '.' . $item->id . '_value', 'elfinder-link-name' => $item->id . '_value_image']) !!}
        </div>
    </div>

@endif