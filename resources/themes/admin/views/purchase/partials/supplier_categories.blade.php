@foreach($categories as $category)
    <tr>
        <td colspan="6"><h4>{!! $category['name'] !!}</h4></td>
    </tr>
    @foreach($category['ingredients'] as $ingredient)
        <tr>
            <td>{!! link_to_route('admin.ingredient.edit', $ingredient->ingredient->name, [$ingredient->ingredient_id], ['target' => '_blank']) !!}</td>
            <td class="text-center">{!! $ingredient->ingredient->unit->name !!}</td>
            <td class="text-center">{!! $ingredient->price !!}</td>
            <td class="text-center">{!! $ingredient->count !!}</td>
            <td class="text-center">
                <label class="checkbox-label">
                    <input readonly="readonly"
                           type="radio"
                           class="square"
                           @if ($ingredient->in_stock) checked="checked" @else disabled="disabled" @endif />
                </label>
            </td>
            <td class="text-center">
                <label class="checkbox-label">
                    <input readonly="readonly"
                           type="radio"
                           class="square"
                           @if ($ingredient->purchase_manager) checked="checked" @else disabled="disabled" @endif />
                </label>
            </td>
        </tr>
    @endforeach
@endforeach
