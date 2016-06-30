<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 30.06.16
 * Time: 10:58
 */

namespace App\Models;

use App\Traits\Models\PositionSortedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecipeIngredient
 * @package App\Models
 */
class RecipeIngredient extends Model
{

    use PositionSortedTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'count',
        'position',
        'main',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class)->with('unit');
    }
}