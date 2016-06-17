<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

namespace App\Http\Controllers;

use View;
use Theme;
use FlashMessages;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as IlluminateBaseController;

/**
 * Class BaseController
 * Base controller class
 *
 * @package App\Http\Controllers
 */
class BaseController extends IlluminateBaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array
     */
    public $viewData = [];

    /**
     * @var string
     */
    public $_theme = "default";

    /**
     * @var string
     */
    public $_view = "home";

    /**
     * @var array
     */
    public $breadcrumbs = [];

    /**
     * @var null
     */
    public $messageBag = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_theme = empty($this->_theme) ? config('app.theme') : $this->_theme;

        $this->_init();
    }

    /**
     * Render views
     *
     * @param string $view
     * @param array  $data
     *
     * @return $this
     */
    public function render($view = '', array $data = [])
    {

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $this->data($k, $v);
            }
        }

        if (empty($view) || !View::exists($view)) {
            $view = $this->_view;
        }

        if (View::exists($view)) {
            return view($view, $this->viewData)->with('messages', FlashMessages::retrieve());
        }

        return View::make('noexist', ['message' => 'View <strong>'.$view.'</strong> doesn\'t exist']);
    }

    /**
     * Push data to templates
     *
     * @return bool
     */
    public function data( /*array or pair of values*/)
    {

        $data = func_get_args();

        if (!empty($data)) {
            if (count($data) > 1) {
                $this->viewData[$data[0]] = $data[1];
            } elseif (is_array($data[0])) {
                $this->viewData = array_merge($this->viewData, $data[0]);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param      $name
     * @param bool $url
     */
    public function breadcrumbs($name, $url = false)
    {

        $this->breadcrumbs[] = ['name' => $name, 'url' => $url];
    }

    /**
     *  init theme
     */
    private function _init()
    {

        Theme::init($this->_theme);
    }
}