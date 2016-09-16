<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 14:28
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Purchase\PurchasePriceUpdateRequest;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Carbon;
use Datatables;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Meta;

/**
 * Class PurchaseController
 * @package App\Http\Controllers\Backend
 */
class PurchaseController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "purchase";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'              => 'purchase.read',
        'show'               => 'purchase.read',
        'edit'               => 'purchase.read',
        'download'           => 'purchase.read',
        'ajaxFieldChange'    => 'purchase.write',
        'setIngredientPrice' => 'purchase.write',
    ];
    
    /**
     * @var \App\Services\PurchaseService
     */
    private $purchaseService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\PurchaseService                 $purchaseService
     */
    public function __construct(ResponseFactory $response, PurchaseService $purchaseService)
    {
        parent::__construct($response);
        
        $this->purchaseService = $purchaseService;
        
        Meta::title(trans('labels.purchase'));
        
        $this->breadcrumbs(trans('labels.purchase'), route('admin.'.$this->module.'.index'));
        
        $this->middleware('admin.before_finalisation_date', ['only' => ['generate', 'downloadPreReport']]);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Purchase::select('week', 'year')
                ->groupBy('week')
                ->groupBy('year');
            
            return $dataTables = Datatables::of($list)
                ->filterColumn('week', 'where', 'purchase.week', '=', '$1')
                ->filterColumn('year', 'where', 'purchase.year', '=', '$1')
                ->editColumn(
                    'week',
                    function ($model) {
                        return trans('labels.w_label').$model->week.
                        ($model->isCurrentWeek() ? view('partials.datatables.current_week_label')->render() : '');
                    }
                )
                ->addColumn(
                    'dates',
                    function ($model) {
                        return $model->getWeekDates();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            $this->module.'.datatables.control_buttons',
                            ['model' => $model]
                        )->render();
                    }
                )
                ->make();
        }
        
        $this->data('page_title', trans('labels.history_of_purchasing'));
        
        $this->breadcrumbs(trans('labels.history_of_purchasing'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($year, $week)
    {
        $dt = active_week();
        
        if ($year > $dt->year || ($year == $dt->year && $week > $dt->weekOfYear)) {
            return redirect()->route('admin.'.$this->module.'.edit', [$year, $week]);
        }
        
        $list = $this->purchaseService->getForWeek($year, $week);
        
        $this->data('list', $list);
        
        $this->data('page_title', trans('labels.list_of_purchase').': '.trans('labels.w_label').$week.', '.$year);
        
        $this->breadcrumbs(trans('labels.list_of_purchase').': '.trans('labels.w_label').$week.', '.$year);
        
        return $this->render('views.'.$this->module.'.show');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($year, $week)
    {
        $now = Carbon::now()->startOfWeek();
        
        if ($year < $now->year || ($year == $now->year && $week <= $now->weekOfYear)) {
            return redirect()->route('admin.'.$this->module.'.show', [$year, $week]);
        }
        
        $list = $this->purchaseService->getForWeek($year, $week);
        
        $this->data('list', $list);
        
        if ($now->year == $year && $now->weekOfYear == $week) {
            $this->data('page_title', trans('labels.for_current_week'));
        } else {
            $this->data(
                'page_title',
                trans('labels.purchase_for_week').': '.trans('labels.w_label').$week.', '.$year
            );
        }
        
        $this->breadcrumbs(trans('labels.purchase_for_week'));
        
        return $this->render('views.'.$this->module.'.edit');
    }
    
    /**
     * @param int $year
     * @param int $week
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function generate($year, $week)
    {
        $now = Carbon::now()->startOfWeek();
        
        $list = $this->purchaseService->preGenerate($year, $week);
        
        $this->data('list', $list);
        
        if ($now->year == $year && $now->weekOfYear == $week) {
            $page_title = trans('labels.for_current_week');
        } else {
            $page_title = trans('labels.purchase_for_week').': '.trans('labels.w_label').$week.', '.$year;
        }
        
        $this->data(
            'page_title',
            $page_title.'<span class="label label-danger">'.trans('labels.this_is_not_final_version').'</span>'
        );
        
        $this->breadcrumbs(trans('labels.for_current_week'));
        
        return $this->render('views.'.$this->module.'.generate');
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier_id
     */
    public function download($year, $week, $supplier_id = false)
    {
        return $this->purchaseService->download($year, $week, $supplier_id);
    }
    
    /**
     * @param int      $year
     * @param int      $week
     * @param int|bool $supplier_id
     */
    public function downloadPreReport($year, $week, $supplier_id = false)
    {
        return $this->purchaseService->download($year, $week, $supplier_id, true);
    }
    
    /**
     * @param int $purchase_id
     *
     * @return array
     */
    public function ajaxFieldChange($purchase_id)
    {
        try {
            $model = Purchase::forFuture()->whereId($purchase_id)->first();
            
            if ($model) {
                $field = request('field', null);
                $value = request('value', null);
                
                if (!empty($field) && in_array($field, Purchase::getChangeableFields())) {
                    $model->{$field} = $value;
                    
                    if ($model->save()) {
                        return [
                            "error"   => 0,
                            'message' => trans('messages.field_value_successfully_saved'),
                            'type'    => 'success',
                        ];
                    }
                }
            }
            
            return ["error" => 1, 'message' => trans('messages.error_in_field_value_saving'), 'type' => 'error'];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int                                                            $purchase_id
     * @param \App\Http\Requests\Backend\Purchase\PurchasePriceUpdateRequest $request
     *
     * @return array
     */
    public function setIngredientPrice($purchase_id, PurchasePriceUpdateRequest $request)
    {
        try {
            $purchase = Purchase::with('ingredient')->whereId($purchase_id)->firstOrFail();
            
            $purchase->price = $request->get('value');
            $purchase->save();
            
            $purchase->ingredient->price = $request->get('value');
            $purchase->ingredient->save();
            
            return [
                "error"   => 0,
                'message' => trans('messages.field_value_successfully_saved'),
                'type'    => 'success',
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}