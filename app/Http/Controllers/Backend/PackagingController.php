<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.08.16
 * Time: 14:28
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Booklet\BookletUpdateRequest;
use App\Models\Purchase;
use App\Services\PackagingService;
use App\Services\PurchaseService;
use Datatables;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Meta;
use Zipper;

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
        'index'         => 'packaging.read',
        'show'          => 'packaging.read',
        'current'       => 'packaging.read',
        'download'      => 'packaging.read',
        'updateBooklet' => 'packaging.booklet.write',
    ];
    
    /**
     * @var array
     */
    protected $tabs = ['repackaging', 'recipes', 'booklet', 'users', 'deliveries'];
    
    /**
     * @var array
     */
    protected $downloads = ['repackaging', 'recipes', 'booklet', 'stickers', 'users', 'deliveries'];
    
    /**
     * @var \App\Services\PackagingService
     */
    private $packagingService;
    
    /**
     * @var \App\Services\PurchaseService
     */
    private $purchaseService;
    
    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\PackagingService                $packagingService
     * @param \App\Services\PurchaseService                 $purchaseService
     */
    public function __construct(
        ResponseFactory $response,
        PackagingService $packagingService,
        PurchaseService $purchaseService
    ) {
        parent::__construct($response);
        
        $this->packagingService = $packagingService;
        $this->purchaseService = $purchaseService;
        
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
        
        $this->data('page_title', trans('labels.history_of_packaging'));
        
        $this->breadcrumbs(trans('labels.history_of_packaging'));
        
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
        if (
            $year == active_week()->year
            &&
            $week == active_week()->weekOfYear
        ) {
            return redirect()->route('admin.'.$this->module.'.current');
        }
        
        $this->data('year', $year);
        $this->data('week', $week);
        
        $page_title = $this->_getPageTitle($year, $week);
        
        $this->data('page_title', $page_title);
        $this->breadcrumbs($page_title);
        
        return $this->render('views.'.$this->module.'.show');
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function current()
    {
        $year = active_week()->year;
        $week = active_week()->weekOfYear;
        
        $this->data('year', $year);
        $this->data('week', $week);
        
        $page_title = $this->_getPageTitle($year, $week, true);
        
        $this->data('page_title', $page_title);
        $this->breadcrumbs($page_title);
        
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
            
            if ($tab == 'booklet') {
                $booklet = $this->packagingService->getBooklet($year, $week);
            }
            
            $html = view('views.'.$this->module.'.tabs.'.$tab)
                ->with('list', $list)
                ->with('year', $year)
                ->with('week', $week)
                ->with('booklet', isset($booklet) ? $booklet : null)
                ->render();
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            admin_notify(
                'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile(),
                [$tab, $year, $week]
            );
            
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
            admin_notify(
                'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile(),
                [$tab, $year, $week]
            );
            
            FlashMessages::add('error', trans('messages.an error has occurred, please reload the page and try again'));
            
            return redirect()->route('admin.'.$this->module.'.show', [$year, $week]);
        }
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function all()
    {
        $year = active_week()->year;
        $week = active_week()->weekOfYear;
        
        try {
            $archive_name = config('archive.path').'/'.
                'reports_w'.$week.'_'.$year.'_'.carbon()->now()->format('Y_m_d_H_i_s').'.zip';
            $archive = Zipper::make($archive_name);
            
            foreach ($this->downloads as $tab) {
                $file = $this->packagingService->{'download'.studly_case($tab)}($year, $week, false);
                
                if (!$file) {
                    continue;
                }
                
                $archive->add($file);
            }
            
            $file = $this->purchaseService->download($year, $week, false, before_finalisation($year, $week), false);
            if ($file) {
                $archive->add($file);
            }
            
            $archive->close();
            
            return response()->download($archive_name);
        } catch (Exception $e) {
            admin_notify(
                'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile(),
                ['all reports', $year, $week]
            );
            
            FlashMessages::add('error', trans('messages.an error has occurred, please reload the page and try again'));
            
            return redirect()->route('admin.'.$this->module.'.show', [$year, $week]);
        }
    }
    
    /**
     * @param \App\Http\Requests\Backend\Booklet\BookletUpdateRequest $request
     *
     * @return array
     */
    public function updateBooklet(BookletUpdateRequest $request)
    {
        try {
            $booklet = $this->packagingService->getBooklet($request->get('year'), $request->get('week'));
            
            $booklet->link = $request->get('link');
            $booklet->save();
            
            return [
                'status'  => 'success',
                'message' => trans('messages.changes successfully saved'),
            ];
        } catch (Exception $e) {
            admin_notify('message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile());
            
            return [
                'status'  => 'error',
                'message' => trans('messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
    
    /**
     * @param int  $year
     * @param int  $week
     * @param bool $current
     *
     * @return string
     */
    private function _getPageTitle($year, $week, $current = false)
    {
        if ($current) {
            $title = trans('labels.for_current_week').': '.trans('labels.w_label').$week.', '.$year;
        } else {
            $title = trans('labels.list_of_packaging').': '.trans('labels.w_label').$week.', '.$year;
        }
        
        if (before_finalisation($year, $week)) {
            $title .= '<span class="label label-danger warning-labels">'.
                trans('labels.this_is_not_final_version').
                '</span>';
        }
        
        return $title;
    }
}