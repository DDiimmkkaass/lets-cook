<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 08.07.16
 * Time: 1:15
 */

namespace App\Services;

use App\Models\BasketRecipe;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuBasket;
use Datatables;
use Exception;
use FlashMessages;

/**
 * Class WeeklyMenuService
 * @package App\Services
 */
class WeeklyMenuService
{
    
    public function table()
    {
        $list = WeeklyMenu::select('id', 'started_at', 'ended_at');
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'weekly_menu.id', '=', '$1')
            ->filterColumn('started_at', 'where', 'weekly_menu.started_at', 'LIKE', '%$1%')
            ->filterColumn('ended_at', 'where', 'weekly_menu.ended_at', 'LIKE', '%$1%')
            ->editColumn(
                'started_at',
                function ($model) {
                    return $model->getStartedAt().
                    ($model->isCurrentWeekMenu() ? view(
                        'views.weekly_menu.partials.current_week_menu_label'
                    )->render() : '');
                }
            )
            ->editColumn(
                'ended_at',
                function ($model) {
                    return $model->getEndedAt();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        ['model' => $model, 'type' => 'weekly_menu', 'without_delete' => true]
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->make();
    }
    
    /**
     * @param \App\Models\WeeklyMenu $model
     * @param array                  $data
     *
     * @return WeeklyMenuBasket
     */
    public function saveBasket(WeeklyMenu $model, $data = [])
    {
        $basket = $model->baskets()->firstOrNew($data);
    
        $basket->save();
            
        return $basket;
    }
    
    /**
     * @param \App\Models\WeeklyMenu $model
     * @param array                  $exists_baskets
     */
    public function removeDeletedBaskets(WeeklyMenu $model, $exists_baskets = [])
    {
        $model->baskets()->whereNotIn('id', $exists_baskets)->delete();
    }
    
    /**
     * @param \App\Models\WeeklyMenuBasket $model
     * @param array                        $recipes
     */
    public function processRecipes(WeeklyMenuBasket $model, $recipes = [])
    {
        $data = isset($recipes['remove']) ? $recipes['remove'] : [];
        $this->_removeRecipes($model, $data);
        
        $data = isset($recipes['old']) ? $recipes['old'] : [];
        $this->_updateOld($data);
        
        $data = isset($recipes['new']) ? $recipes['new'] : [];
        $this->_saveNew($model, $data);
    }
    
    /**
     * @param \App\Models\WeeklyMenuBasket $model
     * @param array                        $data
     */
    private function _removeRecipes(WeeklyMenuBasket $model, $data = [])
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
        foreach ($data as $id => $recipe) {
            try {
                $_recipe = BasketRecipe::findOrFail($id);
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
     * @param \App\Models\WeeklyMenuBasket $model
     * @param array                        $data
     */
    private function _saveNew(WeeklyMenuBasket $model, $data = [])
    {
        foreach ($data as $recipe) {
            try {
                $recipe = new BasketRecipe($recipe);
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