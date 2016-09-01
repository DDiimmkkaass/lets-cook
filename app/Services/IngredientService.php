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
use DB;
use Illuminate\Database\Query\Builder;
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
                    return $model->inSale() ? $model->sale_price.' '.currency() : trans('labels.not_sales');
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
        $list = Ingredient::joinCategory()->joinSupplier()->joinUnit()->joinSaleUnit('sale_unit')
            ->select(
                'ingredients.id',
                'ingredients.name',
                'ingredients.image',
                DB::raw('categories.name as category'),
                DB::raw('(SELECT units.name FROM units WHERE ingredients.unit_id = units.id) as unit'),
                DB::raw('(SELECT units.name FROM units WHERE ingredients.sale_unit_id = units.id) as sale_unit'),
                DB::raw('suppliers.name as supplier'),
                'category_id',
                'unit_id',
                'sale_unit_id',
                'sale_price',
                'supplier_id'
            );
        
        $this->_implodeIncompleteFilters($list);
        
        return $dataTables = Datatables::of($list)
            ->filterColumn('id', 'where', 'ingredients.id', '=', '$1')
            ->filterColumn('name', 'where', 'ingredients.name', 'LIKE', '%$1%')
            ->filterColumn('category', 'where', 'categories.name', 'LIKE', '%$1%')
            ->filterColumn('unit', 'where', 'units.name', 'LIKE', '%$1%')
            ->filterColumn('sale_unit', 'where', 'units.name', 'LIKE', '%$1%')
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
                'sale_unit',
                function ($model) use ($request) {
                    if (!$model->sale_unit_id || $request->get('sale_unit', false)) {
                        $html = view(
                            'partials.datatables.select',
                            [
                                'list'  => $this->units,
                                'field' => 'sale_unit_id',
                                'model' => $model,
                                'type'  => $this->module,
                            ]
                        )->render();
                    } else {
                        $html = $model->sale_unit;
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
            ->removeColumn('sale_unit_id')
            ->removeColumn('sale_price')
            ->removeColumn('supplier_id')
            ->make();
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function prepareInputData(Request $request)
    {
        $input = $request->all();
        
        $input['repacking'] = isset($input['repacking']) ? 1 : 0;
        
        return $input;
    }
    
    /**
     * @param Builder $list
     */
    private function _implodeFilters(&$list)
    {
        $filters = request()->get('datatable_filters');
        
        if (count($filters)) {
            foreach ($filters as $filter => $value) {
                if ($value !== '' && $value !== null) {
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
            
            if ($filter == 'sale_unit') {
                $list->where('ingredients.sale_price', '>', 0);
            }
        }
        
        $types_values = request()->only($this->filter_types, []);
        foreach ($types_values as $type => $value) {
            if (!is_null($value)) {
                if ($filter == 'sale_unit') {
                    $list->orWhere(
                        function ($query) use ($type, $value) {
                            $query->where('ingredients.sale_price', '>', 0)
                                ->where('ingredients.'.$type.'_id', $value);
                        }
                    );
                } else {
                    $list->where('ingredients.'.$type.'_id', $value);
                }
                
                $filter = true;
            }
        }
        
        if (!$filter) {
            foreach ($this->filter_types as $type) {
                if ($type == 'sale_unit') {
                    $list->orWhere(
                        function ($query) use ($type) {
                            $query->where('ingredients.sale_price', '>', 0)
                                ->whereNull('ingredients.'.$type.'_id');
                        }
                    );
                } else {
                    $list->orWhereNull('ingredients.'.$type.'_id');
                }
            }
        }
    }
}