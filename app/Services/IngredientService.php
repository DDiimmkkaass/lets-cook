<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 20.07.16
 * Time: 11:51
 */

namespace App\Services;

use App\Models\Ingredient;
use Datatables;
use Illuminate\Database\Query\Builder;
use DB;
use Illuminate\Http\Request;

/**
 * Class IngredientService
 * @package App\Services
 */
class IngredientService
{
    
    /**
     * @var string
     */
    protected $module = 'ingredient';
    
    /**
     * @var array
     */
    public $filter_types;
    
    /**
     * @var array
     */
    private $units;
    
    /**
     * @var array
     */
    private $categories;
    
    /**
     * @var array
     */
    private $suppliers;
    
    /**
     * @param string $key
     * @param mixed  $data
     */
    public function setData($key, $data)
    {
        $this->{$key} = $data;
    }
    
    /**
     * @return array|\Bllim\Datatables\json
     */
    public function table()
    {
        $list = Ingredient::joinCategory()->joinSupplier()->joinUnit()
            ->select(
                'ingredients.id',
                'ingredients.name',
                'ingredients.image',
                DB::raw('categories.name as category'),
                DB::raw('units.name as unit'),
                DB::raw('suppliers.name as supplier'),
                'price',
                'sale_price'
            );
    
        $this->_implodeFilters($list);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'ingredients.id', '=', '$1')
            ->filterColumn('name', 'where', 'ingredients.name', 'LIKE', '%$1%')
            ->filterColumn('category', 'where', 'categories.name', 'LIKE', '%$1%')
            ->filterColumn('unit', 'where', 'units.name', 'LIKE', '%$1%')
            ->filterColumn('supplier', 'where', 'suppliers.name', 'LIKE', '%$1%')
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
                'price',
                function ($model) {
                    return $model->price.' '.currency();
                }
            )
            ->editColumn(
                'sale_price',
                function ($model) {
                    return $model->isSold() ? $model->sale_price.' '.currency() : trans('labels.not_sales');
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        ['model' => $model, 'type' => $this->module]
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('image')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json
     */
    public function tableIncomplete(Request $request)
    {
        $list = Ingredient::joinCategory()->joinSupplier()->joinUnit()
            ->select(
                'ingredients.id',
                'ingredients.name',
                'ingredients.image',
                DB::raw('categories.name as category'),
                DB::raw('units.name as unit'),
                DB::raw('suppliers.name as supplier'),
                'category_id',
                'unit_id',
                'supplier_id'
            );
        
        $this->_implodeIncompleteFilters($list);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'ingredients.id', '=', '$1')
            ->filterColumn('name', 'where', 'ingredients.name', 'LIKE', '%$1%')
            ->filterColumn('category', 'where', 'categories.name', 'LIKE', '%$1%')
            ->filterColumn('unit', 'where', 'units.name', 'LIKE', '%$1%')
            ->filterColumn('supplier', 'where', 'suppliers.name', 'LIKE', '%$1%')
            ->editColumn(
                'name',
                function ($model) {
                    $html = link_to_route(
                        'admin.'.$this->module.'.show',
                        $model->name,
                        ['ingredient' => $model->id],
                        ['target' => '_blank']
                    )->toHtml();
                    
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
                'category',
                function ($model) use ($request) {
                    if (!$model->category_id || $request->get('category', false)) {
                        $html = view(
                            'partials.datatables.select',
                            [
                                'list'  => $this->categories,
                                'field' => 'category_id',
                                'model' => $model,
                                'type'  => $this->module,
                            ]
                        )->render();
                    } else {
                        $html = $model->category;
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'unit',
                function ($model) use ($request) {
                    if (!$model->unit_id || $request->get('unit', false)) {
                        $html = view(
                            'partials.datatables.select',
                            [
                                'list'  => $this->units,
                                'field' => 'unit_id',
                                'model' => $model,
                                'type'  => $this->module,
                            ]
                        )->render();
                    } else {
                        $html = $model->unit;
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'supplier',
                function ($model) use ($request) {
                    if (!$model->supplier_id || $request->get('supplier', false)) {
                        $html = view(
                            'partials.datatables.select',
                            [
                                'list'  => $this->suppliers,
                                'field' => 'supplier_id',
                                'model' => $model,
                                'type'  => $this->module,
                            ]
                        )->render();
                    } else {
                        $html = $model->supplier;
                    }
                    
                    return $html;
                }
            )
            ->editColumn(
                'actions',
                function ($model) {
                    return view(
                        'partials.datatables.control_buttons',
                        ['model' => $model, 'type' => $this->module]
                    )->render();
                }
            )
            ->setIndexColumn('id')
            ->removeColumn('image')
            ->removeColumn('category_id')
            ->removeColumn('unit_id')
            ->removeColumn('supplier_id')
            ->make();
    }
    
    /**
     * @param Builder $list
     */
    private function _implodeFilters(&$list)
    {
        $filters = request()->get('datatable_filters');
        
        if (count($filters)) {
            foreach ($filters as $filter => $value) {
                if ($value !== '') {
                    switch ($filter) {
                        case 'sale_price':
                            if ($value > 0) {
                                $list->inSales();
                            } elseif ($value < 0) {
                                $list->notSales();
                            }
                            break;
                    }
                }
            }
        }
    }
    
    /**
     * @param $list
     */
    private function _implodeIncompleteFilters(&$list)
    {
        $filter = request()->get('filter', false);
        if ($filter && in_array($filter, $this->filter_types)) {
            $list->whereNull('ingredients.'.$filter.'_id');
        }
        
        $types_values = request()->only($this->filter_types, []);
        foreach ($types_values as $type => $value) {
            if (!is_null($value)) {
                $list->where('ingredients.'.$type.'_id', $value);
                
                $filter = true;
            }
        }
        
        if (!$filter) {
            foreach ($this->filter_types as $type) {
                $list->orWhereNull('ingredients.'.$type.'_id');
            }
        }
    }
}