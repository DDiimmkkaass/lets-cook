<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 16.12.16
 * Time: 13:17
 */

namespace App\Http\Controllers\Frontend;

use App\Services\TagService;
use Exception;

/**
 * Class TagController
 * @package App\Http\Controllers\Frontend
 */
class TagController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'tag';
    
    /**
     * @var \App\Services\TagService
     */
    private $tagService;
    
    /**
     * TagController constructor.
     *
     * @param \App\Services\TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        parent::__construct();
        
        $this->tagService = $tagService;
    }
    
    
    /**
     * @param int    $category_id
     * @param string $model
     *
     * @return array
     */
    public function tagsByCategory($category_id, $model)
    {
        try {
            $html = '';
            $model = title_case(str_singular($model));
            
            $list = $this->tagService->tagsByCategory($category_id, $model);
            
            $list->each(
                function ($item) use (&$html) {
                    $html .= '<li class="article-item__tag-item" data-tag="'.
                        $item->id.'">'.
                        $item->name.'</li>';
                }
            );
            
            return [
                'status' => 'success',
                'html'   => $html,
            ];
        } catch (Exception $e) {
            return [
                'status'  => 'error',
                'message' => trans('front_messages.an error has occurred, please reload the page and try again'),
            ];
        }
    }
}