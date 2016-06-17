<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Question;

/**
 * Class QuestionController
 * @package App\Http\Controllers\Frontend
 */
class QuestionController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'question';

    /**
     * @return $this|\App\Http\Controllers\Frontend\QuestionController
     */
    public function index()
    {
        $this->data('list', Question::withTranslations()->visible()->positionSorted()->get());

        return $this->render($this->module.'.index');
    }
}