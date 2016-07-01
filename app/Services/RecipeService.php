<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.06.16
 * Time: 10:40
 */

namespace App\Services;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class RecipeService
 * @package App\Services
 */
class RecipeService
{
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function table(Request $request)
    {
        $list = Recipe::with('baskets', 'ingredients')
            ->joinBaskets()
            ->joinMainIngredient()
            ->select(
                'recipes.id',
                'recipes.name',
                'recipes.image',
                DB::raw('1 as baskets_list'),
                'recipes.portions',
                DB::raw('ingredients.name as main_ingredient'),
                'recipes.price',
                'recipes.status'
            )
            ->groupBy('recipes.id');

        $this->_implodeFilters($list, $request);

        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'recipes.id', '=', '$1')
            ->filterColumn('name', 'where', 'recipes.name', 'LIKE', '%$1%')
            ->editColumn(
                'name',
                function ($model) {
                    $html = $model->name;

                    if ($model->image) {
                        $html = view(
                                'partials.image',
                                [
                                    'src'        => $model->image,
                                    'attributes' => ['width' => 50, 'class' => 'margin-right-10'],
                                ]
                            )->render().$html;
                    }

                    return $html;
                }
            )
            ->editColumn(
                'baskets_list',
                function ($model) {
                    return $model->baskets->implode('name', '<br>');
                }
            )
            ->editColumn(
                'price',
                function ($model) {
                    return $model->price.' '.currency();
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view(
                        'partials.datatables.toggler',
                        ['model' => $model, 'type' => 'recipe', 'field' => 'status']
                    )->render();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        ['model' => $model, 'type' => 'recipe']
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('ingredients')
            ->removeColumn('baskets')
            ->removeColumn('image')
            ->make();
    }

    /**
     * @param \App\Models\Recipe $model
     * @param array              $ingredients
     * @param int                $main_ingredient
     */
    public function processIngredients(Recipe $model, $ingredients = [], $main_ingredient = 0)
    {
        $data = isset($ingredients['remove']) ? $ingredients['remove'] : [];
        foreach ($data as $id) {
            try {
                $ingredient = $model->ingredients()->findOrFail($id);
                $ingredient->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.ingredient destroy failure"." ".$id));
            }
        }

        $data = isset($ingredients['old']) ? $ingredients['old'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $_ingredient = RecipeIngredient::findOrFail($id);

                $_ingredient['main'] = $main_ingredient == $_ingredient->ingredient_id ? true : false;
                $_ingredient->fill($ingredient);

                $_ingredient->save();
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.ingredient update failure"." ".$_ingredient->ingredient_id)
                );
            }
        }

        $data = isset($ingredients['new']) ? $ingredients['new'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $ingredient['main'] = $main_ingredient == $id ? true : false;
                $ingredient = new RecipeIngredient($ingredient);

                $model->ingredients()->save($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.ingredient save failure"." ".$ingredient['name'])
                );
            }
        }
    }
    
    /**
     * @param \App\Models\Recipe $model
     * @param array              $steps
     */
    public function processSteps($model, $steps = [])
    {
        $data = isset($steps['remove']) ? $steps['remove'] : [];
        foreach ($data as $id) {
            try {
                $step = $model->steps()->findOrFail($id);
                $step->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.step destroy failure"." ".$id));
            }
        }

        $data = isset($steps['old']) ? $steps['old'] : [];
        foreach ($data as $key => $step) {
            try {
                $_step = RecipeStep::findOrFail($key);
                $_step->update($step);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.step update failure"." ".$step['name'])
                );
            }
        }

        $data = isset($steps['new']) ? $steps['new'] : [];
        foreach ($data as $step) {
            try {
                $step = new RecipeStep($step);
                $model->steps()->save($step);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.step save failure"." ".$step['name'])
                );
            }
        }
    }

    /**
     * @param Builder $list
     * @param Request $request
     */
    private function _implodeFilters(&$list, $request)
    {
        $filters = $request->get('recipe_filters');

        if (count($filters)) {
            foreach ($filters as $filter => $value) {
                if ($value !== '') {
                    switch ($filter) {
                        case 'name':
                            $list->where('recipes.name', 'LIKE', '%'.$value.'%');
                            break;
                        case 'basket':
                            $list->where('basket_recipe.basket_id', $value);
                            break;
                        case 'portions':
                            $list->where('recipes.portions', $value);
                            break;
                        case 'main_ingredient':
                            $list->where('ingredients.name', 'LIKE', '%'.$value.'%');
                            break;
                        case 'price':
                            $list->where('recipes.price', $value * 100);
                            break;
                        case 'status':
                            $list->where('recipes.status', $value);
                            break;
                    }
                }
            }
        }
    }
}