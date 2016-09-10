@foreach($list['categories'] as $category_id => $category)
    <tr>
        <th colspan="6"><h4>{!! $category['name'] !!}</h4></th>
    </tr>
    @foreach($category['ingredients'] as $ingredient)
        <tr id="ingredient_{!! $ingredient['ingredient_id'] !!}" class="ingredient-block">
            <td>{!! link_to_route('admin.ingredient.edit', $ingredient['name'], [$ingredient['ingredient_id']], ['target' => '_blank']) !!}</td>
            <td class="text-center">{!! $ingredient['unit'] !!}</td>
            <td class="text-center">{!! $ingredient['price'] !!}</td>
            <td class="text-center">{!! $ingredient['count'] !!}</td>
            <td class="text-center">
                <label class="checkbox-label">
                    <input readonly="readonly"
                           type="radio"
                           class="square"
                           @if ($ingredient['in_stock']) checked="checked" @else disabled="disabled" @endif />
                </label>
            </td>
            <td class="text-center">
                <label class="checkbox-label">
                    <input readonly="readonly"
                           type="radio"
                           class="square"
                           @if ($ingredient['purchase_manager']) checked="checked" @else disabled="disabled" @endif />
                </label>
            </td>
        </tr>
    @endforeach
@endforeach