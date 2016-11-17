<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.11.16
 * Time: 2:12
 */

namespace App\Http\Controllers\Backend;

use App\Models\Subscribe;
use Datatables;
use Excel;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Meta;

/**
 * Class SubscribeController
 * @package App\Http\Controllers\Backend
 */
class SubscribeController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = 'subscribe';
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'  => 'subscribe.read',
        'export' => 'subscribe.export',
    ];
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);
        
        Meta::title(trans('labels.subscribes'));
        
        $this->breadcrumbs(trans('labels.subscribes'), route('admin.'.$this->module.'.index'));
    }
    
    /**
     * Display a listing of the resource.
     * GET /subscribe
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Subscribe::select('id', 'email', 'created_at');
            
            return $dataTables = Datatables::of($list)
                ->editColumn(
                    'created_at',
                    function ($model) {
                        $html = view('partials.datatables.humanized_date', ['date' => $model->created_at])->render();
    
                        return '<div class="text-center">'.$html.'<div>';
                    }
                )
                ->setIndexColumn('id')
                ->make();
        }
        
        $this->data('page_title', trans('labels.subscribes'));
        $this->breadcrumbs(trans('labels.subscribes_list'));
        
        return $this->render('views.'.$this->module.'.index');
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export()
    {
        try {
            $data = Subscribe::orderBy('email')->get(['email'])->pluck('email');
    
            return Excel::create(
                trans('labels.subscribes_export_file_name').'_'.carbon()->now()->format('Y_m_d_H_i_s'),
                function ($excel) use ($data) {
                    $excel->sheet(
                        str_slug(trans('labels.subscribes_export_file_name')),
                        function ($sheet) use ($data) {
                            $sheet->loadView('views.'.$this->module.'.export')->with('data', $data);
                        }
                    );
                }
            )->download('csv');
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, please reload the page and try again'));
            
            return redirect()->back();
        }
    }
}