<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach($baskets as $key => $basket)
            <li @if ($key == 0) class="active" @endif>
                <a aria-expanded="false" href="#basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}" data-toggle="tab">
                    {!! $basket->basket->name !!} <span class="text-lowercase">(@lang('labels.portions'): {!! $basket->portions !!})</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($baskets as $key => $basket)
            <div class="tab-pane @if ($key == 0) active @endif" id="basket_{!! $basket->basket_id !!}_{!! $basket->portions !!}">
                @include('weekly_menu.tabs.basket_content')
            </div>
        @endforeach
    </div>
</div>