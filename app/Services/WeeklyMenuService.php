<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 08.07.16
 * Time: 1:15
 */

namespace App\Services;

use App\Models\Basket;
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
        $list = WeeklyMenu::select('id', 'week', 'year');
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'weekly_menu.id', '=', '$1')
            ->filterColumn('week', 'where', 'weekly_menu.week', 'LIKE', '%$1%')
            ->filterColumn('year', 'where', 'weekly_menu.year', 'LIKE', '%$1%')
            ->editColumn(
                'week',
                function ($model) {
                    return trans('labels.w_label').$model->week.
                    ($model->isCurrentWeekMenu() ?
                        view('views.weekly_menu.partials.current_week_menu_label')->render() :
                        '');
                }
            )
            ->addColumn(
                'dates',
                function ($model) {
                    return $model->getWeekDates();
                }
            )
            ->addColumn(
                'actions',
                function ($model) {
                    return view('weekly_menu.datatables.control_buttons', ['model' => $model])->render();
                }
            )
            ->setIndexColumn('id')
            ->make();
    }
    
    /**
     * @param \App\Models\WeeklyMenu $model
     * @param array                  $data
     *
     * @return \App\Models\WeeklyMenuBasket
     */
    public function saveBasket(WeeklyMenu $model, $data = [])
    {
        $weekly_menu_basket = $model->baskets()->firstOrNew($data);
        
        $weekly_menu_basket->prices = Basket::whereId($weekly_menu_basket->basket_id)
            ->first()
            ->getPrice($weekly_menu_basket->portions);
        
        $weekly_menu_basket->save();
        
        return $weekly_menu_basket;
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