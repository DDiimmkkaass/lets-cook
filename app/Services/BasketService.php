<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 07.07.16
 * Time: 12:14
 */

namespace App\Services;

use App\Models\Basket;
use App\Models\BasketRecipe;
use Exception;
use FlashMessages;

/**
 * Class BasketService
 * @package App\Services
 */
class BasketService
{
    
    /**
     * @param \App\Models\Basket $model
     * @param array              $recipes
     * @param null|int           $weekly_menu_id
     */
    public function processRecipes(Basket $model, $recipes = [], $weekly_menu_id = null)
    {
        $data = isset($recipes['remove']) ? $recipes['remove'] : [];
        foreach ($data as $id) {
            try {
                $recipe = $model->recipes()->findOrFail($id);
                $recipe->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure"." ".$id));
            }
        }

        $data = isset($recipes['old']) ? $recipes['old'] : [];
        foreach ($data as $key => $recipe) {
            try {
                $_recipe = BasketRecipe::findOrFail($key);
                $_recipe->update($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe update failure"." ".$recipe['name'])
                );
            }
        }

        $data = isset($recipes['new']) ? $recipes['new'] : [];
        foreach ($data as $recipe) {
            try {
                $recipe = new BasketRecipe(array_merge($recipe, ['weekly_menu_id' => $weekly_menu_id]));
                $model->recipes()->save($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe save failure"." ".$recipe['name'])
                );
            }
        }
    }
}