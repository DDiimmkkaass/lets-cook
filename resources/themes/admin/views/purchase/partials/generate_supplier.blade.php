<div id="supplier_{!! $supplier_id !!}" data-supplier_id="{!! $supplier_id !!}"
     class="box box-info supplier-block"
     @if (!count($supplier['categories'])) style="display: none;" @endif
    >
    <div class="box-header with-border">
        <h3 class="box-title">{!! $supplier['name'] !!}</h3>

        <div class="box-tools pull-right">
            <span class="label label-success margin-right-10 pointer download-purchase-list">
                <a target="_blank" href="{!! route('admin.purchase.download_pre_report', [$list['year'], $list['week'], $supplier_id]) !!}">@lang('labels.download_xlsx_file')</a>
            </span>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body categories-block">
        @foreach($supplier['categories'] as $category_id => $category)
            <div id="category_{!! $category_id !!}" data-category_id="{!! $category_id !!}" class="table-responsive category-block">
                <h4>{!! $category['name'] !!}</h4>
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>@lang('labels.ingredient')</th>
                        <th class="text-center">@lang('labels.unit')</th>
                        <th class="text-center">@lang('labels.price')</th>
                        <th class="text-center">@lang('labels.count')</th>
                        <th class="text-center">@lang('labels.in_stock')</th>
                        <th class="text-center">@lang('labels.purchase_manager')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($category['ingredients'] as $ingredient)
                        <tr id="ingredient_{!! $ingredient['ingredient_id'] !!}" class="ingredient-block">
                            <td>{!! link_to_route('admin.ingredient.edit', $ingredient['name'], [$ingredient['ingredient_id']], ['target' => '_blank']) !!}</td>
                            <td class="text-center">{!! $ingredient['unit'] !!}</td>
                            <td class="text-center">{!! $ingredient['price'] !!}</td>
                            <td class="text-center">{!! $ingredient['count'] !!}</td>
                            <td class="text-center">
                                <label class="checkbox-label">
                                    <input readonly="readonly" type="radio" class="square" @if ($ingredient['in_stock']) checked="checked" @else disabled="disabled" @endif />
                                </label>
                            </td>
                            <td class="text-center">
                                <label class="checkbox-label">
                                    <input readonly="readonly" type="radio" class="square" @if ($ingredient['purchase_manager']) checked="checked" @else disabled="disabled" @endif />
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>