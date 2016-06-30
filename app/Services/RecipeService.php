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
use Exception;
use FlashMessages;

/**
 * Class RecipeService
 * @package App\Services
 */
class RecipeService
{

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
}