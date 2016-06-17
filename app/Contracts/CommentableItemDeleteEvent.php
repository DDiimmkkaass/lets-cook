<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.02.16
 * Time: 18:59
 */

namespace App\Contracts;

/**
 * Interface CommentableItemDeleteEvent
 * @package App\Contracts
 */
interface CommentableItemDeleteEvent
{

    /**
     * set commentable item properties for comments delete listener
     * call from __construct function
     *
     * public int    commentable_id
     * public string commentable_type
     *
     * @return mixed
     */
    public function setCommentableProperties();
}