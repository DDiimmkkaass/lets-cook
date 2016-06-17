<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Eloquent;

/**
 * Class Media
 * @package App\Models
 */
class Media extends Eloquent
{

    use Translatable;

    /**
     * @var string
     */
    public $table = 'medias';

    /**
     * @var array
     */
    public $translatedAttributes = ['name', 'description'];

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'mediable_id',
        'mediable_type',
        'src',
        'preview',
        'position',
        'name',
        'description',
        'date',
    ];

    /**
     * @var array
     */
    protected static $types = [
        'image' => 1,
        'video' => 2,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        $code = htmlspecialchars_decode($this->src, ENT_QUOTES);

        if (strpos($code, '?') !== false) {
            list($path, $query) = explode('?', $code);

            if ($query) {
                parse_str($query, $params);

                if (isset($params['v'])) {
                    return $params['v'];
                }
            }
        }

        preg_match('/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v\=|youtu\.be\/)([^&\/\?]+)/', $code, $match);

        return isset($match[1]) ? $match[1] : '';

        //http://youtu.be/PP9KmYUIn_Y
        //http://www.youtube.com/watch?v=PP9KmYUIn_Y
        //http://www.youtube.com/embed/vxW119_RZwA/asd
        //http://www.youtube.com/watch?feature=player_embedded&v=A4tdvICr0Dk
    }

    /**
     * @return bool|string
     */
    public function getVideoProvider()
    {
        $code = htmlspecialchars_decode($this->src, ENT_QUOTES);

        list($path, $query) = explode('?', $code);

        if ($query) {
            parse_str($query, $params);

            if (isset($params['v'])) {
                return $params['v'];
            }
        }

        if (preg_match('/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v\=|youtu\.be\/)([^&\/\?]+)/', $code, $match)) {
            return 'youtube';
        }

        return false;
    }

    /**
     * @return string
     */
    public function getYoutubePreview()
    {
        return 'http://img.youtube.com/vi/'.$this->getCode().'/0.jpg';
    }


    /**
     * @return mixed|string
     */
    public function getPreview()
    {
        if (!empty($this->preview)) {
            return $this->preview;
        }

        if ($this->type == 1) {
            return $this->src;
        }

        if ($this->type == 2) {
            $provider = $this->getVideoProvider();

            switch ($provider) {
                case 'youtube':
                    return $this->getYoutubePreview();
                    break;
            }
        }

        return '';
    }

    /**
     * @param        $query
     * @param string $type
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
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy('position', $order);
    }
}