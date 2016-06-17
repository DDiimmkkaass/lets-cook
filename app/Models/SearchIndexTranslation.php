<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchIndexTranslation
 * @package App\Models
 */
class SearchIndexTranslation extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];
}