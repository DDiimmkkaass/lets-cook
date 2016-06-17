<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 21.03.16
 * Time: 22:56
 */

namespace App\Contracts;

/**
 * Interface Likable
 * @package App\Contracts
 */
interface Likable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes();
}