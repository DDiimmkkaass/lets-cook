<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 26.02.16
 * Time: 13:48
 */

namespace App\Models;

use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class Variable
 * @package App\Models
 */
class Variable extends Eloquent
{

    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = ['text'];

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'key',
        'name',
        'description',
        'multilingual',
        'value',
        'text',
        'status',
    ];

    /**
     * @var int
     */
    public static $defaultType = 1;

    /**
     * @var array
     */
    public static $types = [
        1 => 'text',
        2 => 'textarea',
        3 => 'image',
    ];

    /**
     * @param string $value
     */
    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = preg_replace('/\W+\./', '_', $value);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->multilingual ? $this->text : $this->value;
    }

    /**
     * @return string|null
     */
    public function getStringType()
    {
        foreach (self::$types as $key => $type) {
            if ($key == $this->type) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @param string $type_name
     *
     * @return int|null|string
     */
    public static function getTypeKeyByName($type_name)
    {
        foreach (self::$types as $key => $type) {
            if ($type == $type_name) {
                return $key;
            }
        }

        return null;
    }
}