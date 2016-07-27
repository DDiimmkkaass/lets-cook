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
        'type',
        'count',
        'position',
        'main',
    ];
    
    /**
     * @var array
     */
    public static $types = [
        0 => 'normal',
        1 => 'home'
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
    
    /**
     * @param        $query
     * @param string $type
     *
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        $id = self::getTypeIdByName($type);
        
        return $query->whereType($id);
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeNormal($query)
    {
        return $query->ofType('normal');
    }
    
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeHome($query)
    {
        return $query->ofType('home');
    }
    
    /**
     * @param string $type
     *
     * @return int|string
     */
    public static function getTypeIdByName($type)
    {
        foreach (self::$types as $id => $_type) {
            if ($_type == $type) {
                return $id;
            }
        }
    }
}