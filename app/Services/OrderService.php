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
            'delivery_date',
            'delivery_time',
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
                'delivery_date',
                function ($model) {
                    $html = view(
                        'partials.datatables.humanized_date',
                        [
                            'date'      => $model->delivery_date,
                            'in_format' => 'd-m-Y',
                        ]
                    )->render();
                    
                    return $model->delivery_time.' '.$html;
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
            ->removeColumn('delivery_time')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function prepareInputData(Request $request)
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
     * @param \App\Models\Order $order
     *
     * @return \App\Models\Order
     */
    public function createTmpl(Order $order)
    {
        $tmpl = $order->replicate();

        $tmpl->parent_id = $order->id;
        $tmpl->status = Order::getStatusIdByName('tmpl');
        $tmpl->delivery_date = $this->_getDeliveryDateForTmplOrder($order);

        $tmpl->save();

        $tmpl->baskets()->sync($order->baskets()->get(['id'])->pluck('id')->toArray());

        $order->load('recipes', 'ingredients');
        $relations = $order->getRelations();
        foreach ($relations as $relation_name => $relation) {
            foreach ($relation as $record) {
                $tmpl_record = $record->replicate();
                $tmpl_record->order_id = $tmpl->id;
                
                $tmpl_record->push();
            }
        }

        return $tmpl;
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
    
    /**
     * @param \App\Models\Order $parent_order
     *
     * @return string
     */
    private function _getDeliveryDateForTmplOrder(Order $parent_order)
    {
        $latest_tmpl_order = Order::whereId($parent_order->id)->orWhere('parent_id', $parent_order->id)
            ->orderBy('id', 'DESC')
            ->first();
        
        $delivery_date = $latest_tmpl_order->getDeliveryDate();
        
        return $delivery_date->addWeeks($parent_order->subscribe_period)->format('d-m-Y');
    }
}