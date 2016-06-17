<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Traits\Models;

use App\Models\Field;

/**
 * Class FieldableTrait
 * @package App\Models\Traits
 */
trait FieldableTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fields()
    {
        return $this->morphMany(Field::class, 'fieldable');
    }
}
