<li>
    <a aria-expanded="false"
       href="#basket_{!! $basket->id !!}_{!! $portions !!}"
       data-toggle="tab"
       class="pull-left">
        {!! $basket->name !!} <span class="text-lowercase">(@lang('labels.portions'): {!! $portions !!})</span>

        <span class="pull-right margin-left-3 pointer copy-basket"
              data-href="{!! route('admin.weekly_menu.get_basket_copy_form', [$basket->id, $portions]) !!}"
              title="{!! trans('labels.copy') !!}"
              data-basket_id="{!! $basket->id !!}"
              data-portions="{!! $portions !!}">
            <i class="fa fa-files-o" aria-hidden="true"></i>
        </span>
    </a>
</li>