<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 14:28
 */

namespace App\Http\Controllers\Backend;

use App\Models\Purchase;
use App\Services\PackagingService;
use Carbon;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Meta;

/**
 * Class PackagingController
 * @package App\Http\Controllers\Backend
 */
class PackagingController extends BackendController
{
    
    /**
     * @var string
     */
    public $module = "packaging";
    
    /**
     * @var array
     */
    public $accessMap = [
        'index'    => 'packaging.read',
        'show'     => 'packaging.read',
        'current'  => 'packaging.read',
        'download' => 'packaging.read',
    ];
    
    /**
     * @var array
     */
    protected $tabs = ['repackaging', 'recipes', 'users', 'deliveries'];
    
    /**
     * @var array
     */
    protected $downloads = ['repackaging', 'recipes', 'stickers', 'users', 'deliveries'];
    
    /**
     * @var \App\Services\PackagingService
     */
    private $packagingService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\PackagingService                $packagingService
     */
    public function __construct(ResponseFactory $response, PackagingService $packagingService)
    {
        parent::__construct($response);
        
        $this->packagingService = $packagingService;
        
        Meta::title(trans('labels.packaging'));
        
        $this->breadcrumbs(trans('labels.packaging'), route('admin.'.$this->module.'.index'));
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
                        return trans('labels.w_label').$model->week;
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
        if ($year == Carbon::now()->year && $week == Carbon::now()->weekOfYear) {
            return redirect()->route('admin.'.$this->module.'.current');
        }
        
        $this->data('year', $year);
        $this->data('week', $week);
        
        $this->data('page_title', trans('labels.list_of_packaging').': '.trans('labels.w_label').$week.', '.$year);
        
        $this->breadcrumbs(trans('labels.list_of_packaging').': '.trans('labels.w_label').$week.', '.$year);
        
        return $this->render('views.'.$this->module.'.show');
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function current()
    {
        $week = Carbon::now()->weekOfYear;
        $year = Carbon::now()->year;
    
        $this->data('year', $year);
        $this->data('week', $week);
        
        $this->data('page_title', trans('labels.for_current_week').': '.trans('labels.w_label').$week.', '.$year);
        
        $this->breadcrumbs(trans('labels.for_current_week').': '.trans('labels.w_label').$week.', '.$year);
        
        return $this->render('views.'.$this->module.'.show');
    }
    
    /**
     * @param string $tab
     * @param int    $year
     * @param int    $week
     *
     * @return array
     */
    public function tab($tab, $year, $week)
    {
        try {
            if (!in_array($tab, $this->tabs)) {
                throw new Exception(trans('messages.wrong tab id name'));
            }
            
            $list = $this->packagingService->{$tab.'ForWeek'}($year, $week);
            
            $html = view('views.'.$this->module.'.tabs.'.$tab)
                ->with('list', $list)->with('year', $year)->with('week', $week)
                ->render();
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param string $tab
     * @param int    $year
     * @param int    $week
     *
     * @return array
     */
    public function download($tab, $year, $week)
    {
        try {
            if (!in_array($tab, $this->downloads)) {
                throw new Exception(trans('messages.wrong download file request'));
            }
            
            return $this->packagingService->{'download'.studly_case($tab)}($year, $week);
        } catch (Exception $e) {
            FlashMessages::add('error', trans('messages.an error has occurred, please reload the page and try again'));
            
            return redirect()->route('admin.'.$this->module.'.show', [$year, $week]);
        }
    }
}