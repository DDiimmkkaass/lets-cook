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
     */
    public function processRecipes(Basket $model, $recipes = [])
    {
        $data = isset($recipes['remove']) ? $recipes['remove'] : [];
        $this->_removeRecipes($model, $data);
        
        $data = isset($recipes['old']) ? $recipes['old'] : [];
        $this->_updateOld($data);
        
        $data = isset($recipes['new']) ? $recipes['new'] : [];
        $this->_saveNew($model, $data);
    }
    
    /**
     * @param \App\Models\Basket $model
     * @param array              $data
     */
    private function _removeRecipes(Basket $model, $data = [])
    {
        foreach ($data as $id) {
            try {
                $recipe = $model->recipes()->findOrFail($id);
                $recipe->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure"." ".$id));
            }
        }
    }
    
    /**
     * @param array $data
     */
    private function _updateOld($data = [])
    {
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
    }
    
    /**
     * @param \App\Models\Basket $model
     * @param array              $data
     */
    private function _saveNew(Basket $model, $data = [])
    {
        foreach ($data as $recipe) {
            try {
                $recipe = new BasketRecipe(array_merge($recipe));
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