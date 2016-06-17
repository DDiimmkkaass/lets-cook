<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:31
 */

namespace App\Models;

use App\Contracts\Likable;
use App\Contracts\SearchableContract;
use App\Traits\Models\LikableTrait;
use App\Traits\Models\SearchableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App\Models
 */
class Comment extends Model implements SearchableContract, Likable
{

    use SearchableTrait;
    use LikableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'comment',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_comment()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visible_childs()
    {
        return $this->childs()->visible();
    }
    
    /**
     * @param $value
     */
    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = empty($value) ? null : (int) $value;
    }
    
    /**
     * @return string
     */
    public function getCommentableItemLink()
    {
        return $this->getParent()->getAdminUrl();
    }
    
    /**
     * @return string
     */
    public function getCommentableItemTitle()
    {
        return $this->getParent()->getTitle();
    }

    /**
     * @return string
     */
    public function getCommentableItemImage()
    {
        return $this->getParent()->getImage();
    }

    /**
     * @return Model
     */
    public function getParent()
    {
        if (!$this->parent) {
            $parentModel = $this->commentable_type;

            $this->parent = $parentModel::whereId($this->commentable_id)->first();
        }

        return $this->parent;
    }

    /**
     * @return string
     */
    public function getImageForSearchIndex()
    {
        return $this->getCommentableItemImage();
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getTitleForSearchIndex($locale = null)
    {
        return $this->getCommentableItemTitle();
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getDescriptionForSearchIndex($locale = null)
    {
        return $this->comment;
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaTitleForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaDescriptionForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @param null|string $locale
     *
     * @return string
     */
    public function getMetaKeywordsForSearchIndex($locale = null)
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getParent()->getUrl() . '#comments_item_' . $this->id;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        // TODO: Implement getBreadcrumbs() method.
    }

    /**
     * @return string
     */
    public function getUserImage()
    {
        return empty($this->user_id) ? config('user.no_image') :
            ($this->user->avatar ? $this->user->avatar : config('user.no_image'));
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return empty($this->name) ?
            (
                empty($this->user_id) ? trans('labels.unregistered_user') :
                ($this->user->name ? $this->user->name : trans('labels.unregistered_user'))
            ) :
            $this->name;
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeNotModerated($query)
    {
        return $query->whereStatus(false);
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeParents($query)
    {
        return $query->whereNUll('parent_id');
    }

    /**
     * @param mixed $query
     *
     * @return mixed
     */
    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }
}