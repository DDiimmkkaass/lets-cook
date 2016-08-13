<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @php($baskets = old('baskets', []))

        @if (count($baskets))
            @php($i = 0)
            @foreach($baskets as $basket)
                <li @if ($i == 0) class="active" @endif>
                    <a aria-expanded="false" href="#basket_{!! $basket['id'] !!}_{!! $basket['portions'] !!}"
                       data-toggle="tab"
                       class="pull-left">
                        {!! $basket['name'] !!} <span class="text-lowercase">(@lang('labels.portions'): {!! $basket['portions'] !!})</span>

                        <span class="pull-right margin-left-3 pointer copy-basket"
                           data-href="{!! route('admin.weekly_menu.get_basket_copy_form', [$basket['id'], $basket['portions']]) !!}"
                           title="{!! trans('labels.copy') !!}"
                           data-basket_id="{!! $basket['id'] !!}"
                           data-portions="{!! $basket['portions'] !!}">
                            <i class="fa fa-files-o" aria-hidden="true"></i>
                        </span>
                    </a>
                </li>
                @php($i = 1)
            @endforeach
        @else
            @foreach($model->baskets as $key => $basket)
                <li @if ($key == 0) class="active" @endif>
                    <a aria-expanded="false" href="#basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}"
                       data-toggle="tab"
                       class="pull-left">
                        {!! $basket->basket->name !!} <span class="text-lowercase">(@lang('labels.portions'): {!! $basket->portions !!})</span>

                        <span class="pull-right margin-left-15 pointer copy-basket"
                              data-href="{!! route('admin.weekly_menu.get_basket_copy_form', [$basket->basket_id, $basket->portions]) !!}"
                              title="{!! trans('labels.copy') !!}"
                              data-basket_id="{!! $basket->basket_id !!}"
                              data-portions="{!! $basket->portions !!}">
                            <i class="fa fa-files-o" aria-hidden="true"></i>
                        </span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>

    <div class="tab-content">
        @if (count($baskets))
            @php($i = 0)
            @foreach($baskets as $key => $basket)
                <div class="tab-pane @if ($i == 0) active @endif"
                     id="basket_{!! $basket['id'] !!}_{!! $basket['portions'] !!}">
                    @include('weekly_menu.tabs.new_basket_content')
                </div>
                @php($i = 1)
            @endforeach
        @else
            @foreach($model->baskets as $key => $basket)
                <div class="tab-pane @if ($key == 0) active @endif"
                     id="basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}">
                    @include('weekly_menu.tabs.basket_content')
                </div>
            @endforeach
        @endif
    </div>
</div>