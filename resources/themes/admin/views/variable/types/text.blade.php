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
                            {!! Form::text($locale.'[text]', isset($item->translate($locale)->text) ? $item->translate($locale)->text : '', ['id' => $locale.'_text', 'placeholder' => trans('labels.text'), 'required' => true, 'class' => 'form-control input-sm']) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@else

    <div class="row form-group">
        <div class="col-xs-12 col-sm-6 col-md-5">
            {!! Form::text('value', null, ['id' => 'value', 'placeholder' => trans('labels.value'), 'required' => true, 'class' => 'form-control input-sm']) !!}
        </div>
    </div>

@endif