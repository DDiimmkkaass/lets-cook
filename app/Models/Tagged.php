<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tagged
 * @package App\Models
 */
class Tagged extends Model
{
    /**
     * @var string
     */
    protected $table = 'tagged';

    /**
     * @var array
     */
    protected $fillable = ['tag_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }


    /**
     * return tagged model via tagged_id & tagged_type
     *
     * @return Model
     */
    public function getParent()
    {
        if (!$this->parent) {
            $parentModel = $this->taggable_type;

            if (method_exists($parentModel, 'translations')) {
                $this->parent = $parentModel::with('translations')->whereId($this->taggable_id)->first();
            } else {
                $this->parent = $parentModel::whereId($this->taggable_id)->first();
            }
        }

        return $this->parent;
    }
}