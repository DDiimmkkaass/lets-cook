<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:36
 */

namespace App\Http\Controllers\Backend;

use App\Events\Backend\ShareDelete;
use App\Http\Requests\Backend\Share\ShareCreateRequest;
use App\Http\Requests\Backend\Share\ShareRequest;
use App\Http\Requests\Backend\Share\ShareUpdateRequest;
use App\Models\Share;
use App\Services\ShareService;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\ProcessTagsTrait;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;

/**
 * Class ShareController
 * @package App\Http\Controllers\Backend
 */
class ShareController extends BackendController
{
    
    use AjaxFieldsChangerTrait;
    use ProcessTagsTrait;
    
    /**
     * @var string
     */
    public $module = "share";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'share.read',
        'create'          => 'share.create',
        'store'           => 'share.create',
        'show'            => 'share.read',
        'edit'            => 'share.read',
        'update'          => 'share.write',
        'destroy'         => 'share.delete',
        'ajaxFieldChange' => 'share.write',
    ];
    
    /**
     * @var ShareService
     */
    private $shareService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\ShareService                    $shareService
     */
    public function __construct(ResponseFactory $response, ShareService $shareService)
    {
        parent::__construct($response);
        
        $this->shareService = $shareService;
        
        Meta::title(trans('labels.shares'));
        
        $this->breadcrumbs(trans('labels.shares'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /share
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Bllim\Datatables\json|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            return $this->shareService->table();
        }
        
        $this->data('page_title', trans('labels.shares'));
        $this->breadcrumbs(trans('labels.shares_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    
    /**
     * Show the form for creating a new resource.
     * GET /share/create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->data('model', new Share);
        
        $this->data('page_title', trans('labels.share_creating'));
        
        $this->breadcrumbs(trans('labels.share_creating'));
        
        return $this->render('views.'.$this->module.'.create');
    }
    
    /**
     * Store a newly created resource in storage.
     * POST /share
     *
     * @param ShareRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ShareRequest $request)
    {
        try {
            $model = new Share($request->all());
            
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
     * GET /share/{id}
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
     * GET /share/{id}/edit
     *
     * @param  int $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        try {
            $model = Share::whereId($id)->firstOrFail();
            
            $this->data('page_title', trans('labels.share_editing'));
            
            $this->breadcrumbs(trans('labels.share_editing'));
            
            return $this->render('views.'.$this->module.'.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        }
    }
    
    /**
     * Update the specified resource in storage.
     * PUT /share/{id}
     *
     * @param  int         $id
     * @param ShareRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, ShareRequest $request)
    {
        try {
            $model = Share::findOrFail($id);
            
            $model->fill($request->all());
            $model->save();
            
            FlashMessages::add('success', trans('messages.save_ok'));
            
            return redirect()->route('admin.'.$this->module.'.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.update_error'));
        }
        
        return redirect()->back()->withInput();
    }
    
    /**
     * Remove the specified resource from storage.
     * DELETE /share/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $model = Share::findOrFail($id);
            
            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error'));
        }
        
        return redirect()->route('admin.'.$this->module.'.index');
    }
}