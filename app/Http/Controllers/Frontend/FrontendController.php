<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use Agent;
use App\Http\Controllers\BaseController;
use App\Models\Page;
use App\Models\User;
use Config;
use JavaScript;
use Lang;
use Meta;
use Sentry;
use View;

/**
 * Class FrontendController
 * @package App\Http\Controllers\Frontend
 */
class FrontendController extends BaseController
{
    /**
     * @var string
     */
    public $module = "";
    
    /**
     * @var array
     */
    public $breadcrumbs = [];
    
    /**
     * @var bool|null
     */
    public $user = null;
    
    /**
     * @var int
     */
    public $per_page = 20;
    
    /**
     * @var null|Page
     */
    public $home_page = null;
    
    /**
     * constructor
     */
    function __construct()
    {
        $this->_theme = config('app.theme');
        
        parent::__construct();
        
        Meta::title(Config::get('app.name', ''));
        
        if (Sentry::getUser()) {
            $this->user = User::with('info')->whereId(Sentry::getUser()->getId())->first();
        }
        
        if ($this->user) {
            $this->user->updateActivity();
        }
        
        $this->breadcrumbs(Config::get('app.name', ''), route('home'));
        
        if (!request()->ajax()) {
            $this->home_page = Page::with('translations')->whereSlug('home')->first();
        }
        
        $this->fillThemeData();
    }
    
    /**
     * @param $model
     * @param $type
     */
    public function fillMeta($model, $type)
    {
        Meta::title($model->getMetaTitle());
        Meta::description($model->getMetaDescription());
        Meta::keywords($model->getMetaKeywords());
        Meta::image($model->getMetaImage());
        Meta::canonical($model->getUrl());
    }
    
    /**
     * fill additional template data
     */
    public function fillThemeData()
    {
        $max_upload_file_size = (int) ini_get("upload_max_filesize") * 1024 * 1024;
        View::share('max_upload_file_size', $max_upload_file_size);
        
        View::share('max_upload_image_width', config('image.max_upload_width'));
        View::share('max_upload_image_height', config('image.max_upload_height'));
        
        View::share('currency', currency());
        
        View::share('no_image_user', config('user.no_image'));
        
        // set javascript vars
        JavaScript::put(
            [
                'app_url'                             => Config::get('app.url', ''),
                'lang'                                => Lang::getLocale(),
                'currency'                            => currency(),
                'max_upload_file_size'                => $max_upload_file_size,
                'max_upload_image_width'              => config('image.max_upload_width'),
                'max_upload_image_height'             => config('image.max_upload_height'),
                'lang_error'                          => trans('front_labels.error'),
                'lang_success'                        => trans('front_labels.success'),
                'lang_notice'                         => trans('front_labels.notice'),
                'lang_deleteCardConfirmMessage'       => trans(
                    'messages.your really want to delete this card'
                ),
                'lang_errorRequestError'              => trans(
                    'messages.an error has occurred, please reload the page and try again'
                ),
                'lang_errorValidation'                => trans('messages.validation_failed'),
                'lang_errorFormSubmit'                => trans('messages.error form submit'),
                'lang_authError'                      => trans('messages.auth middleware error message'),
                'lang_errorSelectedFileIsTooLarge'    => trans('messages.trying to load is too large file'),
                'lang_errorIncorrectFileType'         => trans('messages.trying to load unsupported file type'),
                'lang_errorSelectedImageWidthError'   => trans(
                    'messages.max allowed image width: :width px',
                    ['width' => config('image.max_upload_width')]
                ),
                'lang_errorSelectedImageHeightError'  => trans(
                    'messages.max allowed image height: :height px',
                    ['height' => config('image.max_upload_height')]
                ),
                'lang_youReallyWantToCancelThisOrder' => trans('messages.you really want to cancel this order'),
                'lang_errorCantShowAjaxPopup'         => trans('messages.an error has occurred, try_later'),
                'no_image'                            => 'http://www.placehold.it/250x250/EFEFEF/AAAAAA&text=no+image',
                'no_image_user'                       => config('user.no_image'),
                'is_mobile'                           => Agent::isMobile(),
            ]
        );
        
        View::share('site_name', Config::get('app.name', ''));
        
        View::share("lang", Lang::getLocale());
        
        View::share("logo_title", Config::get('app.name', ''));
        
        View::share("user", $this->user);
        
        View::share("is_mobile", Agent::isMobile());
        
        View::share("google_analytics_id", Config::get('google.analytics.id', null));
        
        View::share("home_page", $this->home_page);
    }
    
    /**
     * @param string $view
     * @param array  $data
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render($view = '', array $data = [])
    {
        $this->data('breadcrumbs', $this->breadcrumbs);
        
        return parent::render($view, $data);
    }
}