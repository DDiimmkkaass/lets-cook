<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.16
 * Time: 14:52
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Model
{

    use PositionSortedTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}