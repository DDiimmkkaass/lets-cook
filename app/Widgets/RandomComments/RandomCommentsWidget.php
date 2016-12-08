<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 31.08.16
 * Time: 14:35
 */

namespace App\Widgets\RandomComments;

use App\Models\Comment;
use Pingpong\Widget\Widget;

/**
 * Class RandomCommentsWidget
 * @package App\Widgets\RandomComments
 */
class RandomCommentsWidget extends Widget
{
    
    /**
     * @param int $count
     *
     * @return string
     */
    public function index($count = 4)
    {
        $list = Comment::visible()->get(['id']);
        
        $comments_count = count($list);
        
        if ($comments_count > $count) {
            $ids = $list->random($count)->pluck('id')->toArray();
            
            $list = Comment::with('user')->whereIn('id', $ids)->get();
        } else {
            $list = Comment::with('user')->visible()->get();
        }
        
        return view('widgets.random_comments.index')->with(compact('list'))->render();
    }
}