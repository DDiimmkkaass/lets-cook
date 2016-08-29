<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 11:42
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Coupon\CouponRequest;
use App\Models\Coupon;
use App\Services\CouponService;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;

/**
 * Class CouponController
 * @package App\Http\Controllers\Backend
 */
class CouponController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "coupon";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'   => 'coupon.read',
        'create'  => 'coupon.create',
        'store'   => 'coupon.create',
        'show'    => 'coupon.read',
        'edit'    => 'coupon.read',
        'update'  => 'coupon.write',
        'destroy' => 'coupon.delete',
    ];
    
    /**
     * @var \App\Services\CouponService
     */
    private $couponService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\CouponService                   $couponService
     */
    public function __construct(ResponseFactory $response, CouponService $couponService)
    {
        parent::__construct($response);
        
        $this->couponService = $couponService;
        
        Meta::title(trans('labels.all_coupons'));
        
        $this->breadcrumbs(trans('labels.all_coupons'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /coupon
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->couponService->table();
        }
        
        $this->data('page_title', trans('labels.all_coupons'));
        $this->breadcrumbs(trans('labels.coupons_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * Show the form for creating a new resource.
     * GET /coupon/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new Coupon);
        
        $this->data('page_title', trans('labels.coupon_create'));
        
        $this->breadcrumbs(trans('labels.coupon_create'));
        
        $this->_fillAdditionalTemplateData();
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /coupon
     *
     * @param CouponRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CouponRequest $request)
    {
        try {
            $model = new Coupon($request->all());
            $model->code = $this->couponService->newCode();
            
            $model->save();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.save_failed'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * GET /coupon/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return $this->edit($id);
    }
    
    /**
     * Show the form for editing the specified resource.
     * GET /coupon/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $model = Coupon::findOrFail($id);
            
            $this->data('page_title', trans('labels.coupon_editing').' ('.$model->code.')');
            
            $this->breadcrumbs(trans('labels.coupon_editing'));
    
            $this->_fillAdditionalTemplateData();
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /coupon/{id}
     *
     * @param  int          $id
     * @param CouponRequest $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update($id, CouponRequest $request)
    {
        try {
            $model = Coupon::findOrFail($id);
            
            $model->fill($request->all());
            
            $model->save();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error'));
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /coupon/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $model = Coupon::findOrFail($id);
            
            $model->delete();
            
            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }
        
        return redirect()->route('admin.'.$this->module.'.index');
    }
    
    private function _fillAdditionalTemplateData()
    {
        $types = [];
        foreach (Coupon::getTypes() as $id => $type) {
            $types[$id] = trans('labels.discount_type_'.$type);
        }
        $this->data('types', $types);
    
        $discount_types = [];
        foreach (Coupon::getDiscountTypes() as $id => $discount_type) {
            $discount_types[$id] = trans('labels.discount_discount_type_'.$discount_type);
        }
        $this->data('discount_types', $discount_types);
    }
}