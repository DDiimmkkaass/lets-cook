<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use Eloquent;

/**
 * Class Field
 * @package App\Models
 */
class Field extends Eloquent
{

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'value',
        'fieldable_id',
        'fieldable_type',
    ];

    /**
     * @var array
     */
    public static $types = [
        'email'             => 1,
        'mobile_phone'      => 2,
        'static_phone'      => 3,
        'messenger'         => 4,
        'link'              => 5
    ];

    /**
     * @var string
     */
    protected $table = 'fields';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldable()
    {
        return $this->morphTo();
    }

    /**
     * @param $query
     * @param $type
     *
     * @return mixed
     */
    public function scopeOf($query, $type)
    {
        $id = array_get(self::$types, $type, 0);

        if (!empty($id)) {
            return $query->where('type', $id);
        }

        return $query;
    }

    /**
     * @return string
     */
    public function getStringType()
    {
        foreach (self::$types as $type => $key) {
            if ($key == $this->type) {
                return $type;
            }
        }

        return '';
    }
}
