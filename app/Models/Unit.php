<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.16
 * Time: 17:06
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Unit
 * @package App\Models
 */
class Unit extends Model
{

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