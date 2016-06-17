<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Backend;

use App;
use App\Http\Controllers\BaseController;
use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use File;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use JavaScript;
use Lang;
use Meta;
use Redirect;
use Request;
use Response;
use Sentry;
use View;

/**
 * Class BackendController
 * @package App\Http\Controllers\Backend
 */
class BackendController extends BaseController
{

    /**
     * @var string
     */
    public $_theme = "admin";

    /**
     * @var string
     */
    public $module = "";

    /**
     * @var int
     */
    public $paginator_count = 10;

    /**
     * Карта доступа к методам.
     * Пара метод => права доступа
     * Ключ all - права доступа ков сем методам класса
     */

    public $accessMap = [];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    function __construct(ResponseFactory $response)
    {
        parent::__construct();

        App::setLocale('ru');

        $this->response = $response;

        $this->user = Sentry::getUser();

        $this->fillThemeData();
    }

    /**
     * Call controller with the specified parameters.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function callAction($method, $parameters)
    {

        if (!empty($this->user)) {

            $permission = array_get($this->accessMap, $method, false);

            // если нет соотв. прав - проверим, может заданы права к абсолютно всем методам
            if (!$permission) {
                $permission = array_get($this->accessMap, 'all', false);
            }

            if ($permission) {

                $protect = $this->protect($permission);

                if ($protect !== true) {
                    return $protect;
                }
            }
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * @param $permission
     *
     * @return bool
     */
    public function isActionAllowed($permission)
    {

        return $this->user->hasAccess($permission);
    }

    /**
     * @param      $permission
     * @param bool $verbose
     *
     * @return bool|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function protect($permission, $verbose = true)
    {

        if (!$this->isActionAllowed($permission)) {
            $message = trans('messages.access not allowed');

            if (Request::ajax()) {
                return Response::json(['message' => $message, 'status' => 'warning']);
            } else {
                if ($verbose) {
                    FlashMessages::add('warning', $message);

                    return Redirect::route('admin.home');
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function getIndex()
    {
        return $this->render();
    }

    /**
     * @param string $view
     * @param array  $data
     *
     * @return $this
     */
    public function render($view = '', array $data = [])
    {
        $this->data('breadcrumbs', $this->breadcrumbs);

        return parent::render($view, $data);
    }

    /**
     * fill theme data
     */
    private function fillThemeData()
    {
        Meta::title(config('app.name'));

        $this->data("module", $this->module);

        View::share("user", $this->user);
        View::share("user_info", $this->user ? $this->user->info()->first() : null);

        View::share('lang', Lang::getLocale());

        View::share('currency', trans('labels.grn'));

        View::share('elfinder_link_name', 'link');

        $upload_max_filesize = (int) ini_get("upload_max_filesize") * 1024 * 1024;
        View::share('upload_max_filesize', $upload_max_filesize);

        $no_image = '/assets/themes/admin/img/no_image.png';
        View::share('no_image', $no_image);

        JavaScript::put(
            [
                'no_image'                         => $no_image,
                'lang'                             => Lang::getLocale(),
                'lang_yes'                         => trans('labels.yes'),
                'lang_save'                        => trans('labels.save'),
                'lang_cancel'                      => trans('labels.cancel'),
                'birthday_format'                  => 'dd-mm-yyyy',
                'lang_errorSelectedFileIsTooLarge' => trans('messages.trying to load is too large file'),
                'lang_errorIncorrectFileType'      => trans('messages.trying to load unsupported file type'),
                'lang_errorFormSubmit'             => trans('messages.error form submit'),
                'lang_errorValidation'             => trans('messages.validation_failed'),
                'lang_errorEmptyData'              => trans('messages.you have not entered any data'),
                'lang_errorEmptyNameField'         => trans('messages.name field not set'),
                'upload_max_filesize'              => $upload_max_filesize,
                'elfinderConnectorUrl'             => route('admin.elfinder.connector'),
            ]
        );

        view()->share('translation_groups', config('translation.visible_groups'));
    }
}