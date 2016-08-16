<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 15.07.16
 * Time: 10:36
 */

namespace App\Services;

use App\Models\Order;
use App\Models\OrderIngredient;
use App\Models\OrderRecipe;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Http\Request;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService
{
    
    /**
     * @return array|\Bllim\Datatables\json
     */
    public function table()
    {
        $list = Order::select(
            'id',
            'full_name',
            'user_id',
            'type',
            'subscribe_period',
            'payment_method',
            'status',
            'created_at',
            'total'
        );
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'orders.id', '=', '$1')
            ->filterColumn('full_name', 'where', 'orders.full_name', 'LIKE', '%$1%')
            ->editColumn(
                'full_name',
                function ($model) {
                    return '<a href="'.route('admin.user.show', $model->user_id).'" 
                            title="'.trans('labels.go_to_user').' '.$model->getUserFullName().'">
                            '.$model->getUserFullName().'
                            </a>';
                }
            )
            ->editColumn(
                'type',
                function ($model) {
                    $html = trans('labels.order_type_'.$model->getStringType());
                    $html .= $model->isSubscribe() ?
                        ' ('.trans_choice('labels.subscribe_period_label', $model->subscribe_period).')' :
                        '';
                    
                    return $html;
                }
            )
            ->editColumn(
                'payment_method',
                function ($model) {
                    return trans('labels.payment_method_'.$model->getStringPaymentMethod());
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view(
                        'partials.datatables.status_label',
                        [
                            'status' => $model->getStringStatus(),
                            'label'  => trans('labels.order_status_'.$model->getStringStatus()),
                        ]
                    )->render();
                }
            )
            ->editColumn(
                'created_at',
                function ($model) {
                    return view(
                        'partials.datatables.humanized_date',
                        [
                            'date'        => $model->created_at,
                            'time_format' => 'H:i',
                        ]
                    )->render();
                }
            )
            ->editColumn(
                'total',
                function ($model) {
                    return $model->total.' '.currency();
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        [
                            'model'          => $model,
                            'type'           => 'order',
                            'without_delete' => true,
                        ]
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('user_id')
            ->removeColumn('subscribe_period')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function prepareUpdateData(Request $request)
    {
        $data = $request->all();
        
        $data['city_id'] = empty($data['city']) ? $data['city_id'] : null;
        
        return $data;
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    public function saveRelationships(Order $model, $input)
    {
        $this->_saveRecipes($model, isset($input['recipes']) ? $input['recipes'] : []);
        
        $this->_saveAdditionalBaskets($model, isset($input['baskets']) ? $input['baskets'] : []);
    
        $this->_saveIngredients($model, isset($input['ingredients']) ? $input['ingredients'] : []);
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    private function _saveRecipes(Order $model, $input)
    {
        $data = isset($input['remove']) ? $input['remove'] : [];
        foreach ($data as $id) {
            try {
                $recipe = $model->recipes()->findOrFail($id);
                $recipe->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure")." ".$id);
            }
        }
        
        $data = isset($input['old']) ? $input['old'] : [];
        foreach ($data as $id => $recipe) {
            try {
                $_recipe = OrderRecipe::findOrFail($id);
                $_recipe->update($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe update failure")." ".$recipe['name']
                );
            }
        }
        
        $data = isset($input['new']) ? $input['new'] : [];
        foreach ($data as $recipe) {
            try {
                $recipe = new OrderRecipe($recipe);
                $model->recipes()->save($recipe);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe save failure")." ".$recipe['name']
                );
            }
        }
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $baskets
     */
    private function _saveAdditionalBaskets(Order $model, $baskets)
    {
        $model->baskets()->sync($baskets);
    }
    
    /**
     * @param \App\Models\Order $model
     * @param array             $input
     */
    private function _saveIngredients(Order $model, $input)
    {
        $data = isset($input['remove']) ? $input['remove'] : [];
        foreach ($data as $id) {
            try {
                $ingredient = $model->ingredients()->findOrFail($id);
                $ingredient->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.recipe delete failure")." ".$id);
            }
        }
        
        $data = isset($input['old']) ? $input['old'] : [];
        foreach ($data as $id => $ingredient) {
            try {
                $_recipe = OrderIngredient::findOrFail($id);
                $_recipe->update($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe update failure")." ".$ingredient['name']
                );
            }
        }
        
        $data = isset($input['new']) ? $input['new'] : [];
        foreach ($data as $ingredient) {
            try {
                $ingredient = new OrderIngredient($ingredient);
                $model->ingredients()->save($ingredient);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.recipe save failure")." ".$ingredient['name']
                );
            }
        }
    }
}