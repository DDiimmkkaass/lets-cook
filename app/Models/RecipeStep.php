<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.06.16
 * Time: 13:54
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecipeStep
 * @package App\Models
 */
class RecipeStep extends Model
{

    use PositionSortedTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'recipe_id',
        'name',
        'description',
        'image',
        'position',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}