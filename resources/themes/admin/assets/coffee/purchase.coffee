$(document).on "ready", () ->
  $('.purchase-form .purchase_manager-checkbox-input').on 'switchChange.bootstrapSwitch', () ->
    $purchase_manager_block = $(this).closest('#purchase_manager')

    $ingredient = $(this).closest('.ingredient-block')

    supplier_id = $ingredient.data('supplier_id');
    category_id = $ingredient.data('category_id');

    unless $purchase_manager_block.length
      $purchase_manager_block = $('#purchase_manager')
      $category = $purchase_manager_block.find('#category_' + category_id)

      $purchase_manager_block.fadeIn()

      unless $category.length
        $category = $ingredient.closest('.category-block').clone()

        $category.find('tbody').html('')

      $ingredient.appendTo($category.find('tbody'))

      $purchase_manager_block.find('.categories-block').append($category)

      $supplier = $('#supplier_' + supplier_id)
      $category = $supplier.find('#category_' + category_id)

      unless $category.find('.ingredient-block').length
        $category.remove()

      unless $supplier.find('.category-block').length
        $supplier.fadeOut()

    else
      $supplier = $('#supplier_' + supplier_id)
      $category = $supplier.find('#category_' + category_id)

      $supplier.fadeIn()

      unless $category.length
        $category = $ingredient.closest('.category-block').clone()

        $category.find('tbody').html('')

      $ingredient.appendTo($category.find('tbody'))

      $supplier.find('.categories-block').append($category)

      $purchase_manager_block = $('#purchase_manager')
      $category = $purchase_manager_block.find('#category_' + category_id)

      unless $category.find('.ingredient-block').length
        $category.remove()

      unless $purchase_manager_block.find('.category-block').length
        $purchase_manager_block.fadeOut()