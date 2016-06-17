<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 27.02.16
 * Time: 19:33
 */

namespace App\Traits\Controllers;

use App\Models\Tag;
use App\Models\Tagged;

/**
 * Class ProcessTagsTrait
 * @package App\Traits\Controllers
 */
trait ProcessTagsTrait
{

    /**
     * @param        $model
     * @param string $array_name
     */
    public function processTags($model, $array_name = 'tags')
    {
        $tags = request($array_name, []);

        $this->_removeDeleted($model, $tags);

        $this->_saveNew($model, $tags);
    }

    /**
     * @return array
     */
    public function getTagsList()
    {
        $tags = [];
        foreach (Tag::with('translations')->visible()->get() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return $tags;
    }

    /**
     * @param $model
     *
     * @return array
     */
    public function getSelectedTagsList($model)
    {
        $selected_tags = [];
        if ($model) {
            $selected_tags = $model->tags->lists('tag_id')->toArray();
        }

        return $selected_tags;
    }

    /**
     * @param       $model
     * @param array $tags
     */
    private function _removeDeleted($model, $tags = [])
    {
        $model->tags()->whereNotIn('tag_id', $tags)->delete();
    }

    /**
     * @param       $model
     * @param array $tags
     */
    private function _saveNew($model, $tags = [])
    {
        $exists = $model->tags()->whereIn('tag_id', $tags)->lists('tag_id')->toArray();

        foreach ($tags as $tag_id) {
            if (!in_array($tag_id, $exists)) {
                $tagged = new Tagged(['tag_id' => $tag_id]);

                $model->tags()->save($tagged);
            }
        }
    }
}