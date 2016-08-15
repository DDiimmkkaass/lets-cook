@include('partials.datatables.control_buttons', ['type' => 'recipe', 'delete_function' => 'delete_recipe('.$model->id.')'])

@if ($user->hasAccess('recipe.write') || $user->hasAccess('recipe.create'))
    <a class="btn btn-default btn-sm btn-flat margin-left-3 copy-recipe" href="{!! route('admin.recipe.get_copy_form', $model->id) !!}"
       title="{!! trans('labels.copy') !!}">
        <i class="fa fa-files-o" aria-hidden="true"></i>
    </a>
@endif