<?php

namespace App\Console\Commands;

use App\Models\SearchIndex;
use Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UpdateSearchIndex
 * @package App\Console\Commands
 */
class UpdateSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:update-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update search index table from other searchable table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('['.Carbon::now().'] - Start updating search index');

        foreach (SearchIndex::getTypes() as $type) {
            $this->info('  -  ['.Carbon::now().'] - Start updating '.$type);

            $this->_updateIndex($type);
        }

        $this->info('['.Carbon::now().'] - Finish updating search index');
    }

    /**
     * @param string $type
     */
    private function _updateIndex($type)
    {
        $model = '\\'.studly_case($type);

        if (method_exists($model, 'translations')) {
            $this->_processModel($model, $type, true);
        } else {
            $this->_processModel($model, $type);
        }
    }

    /**
     * @param        $model
     * @param string $type
     * @param bool   $with_locale
     */
    private function _processModel($model, $type, $with_locale = false)
    {
        $list = $with_locale ? $model::with('translations')->visible()->get() : $model::visible()->get();;

        SearchIndex::of($type)->whereNotIn('searchable_id', $list->pluck('id')->toArray())->delete();

        foreach ($list as $item) {
            $data = [
                'image'      => $item->getImageForSearchIndex(),
                'updated_at' => $item->updated_at,
            ];

            foreach (config('app.locales') as $locale) {
                $data[$locale] = [
                    'title'            => $item->getTitleForSearchIndex($with_locale ? $locale : null),
                    'description'      => $item->getDescriptionForSearchIndex($with_locale ? $locale : null),
                    'meta_title'       => $item->getMetaTitleForSearchIndex($with_locale ? $locale : null),
                    'meta_description' => $item->getMetaDescriptionForSearchIndex($with_locale ? $locale : null),
                    'meta_keywords'    => $item->getMetaKeywordsForSearchIndex($with_locale ? $locale : null),
                ];
            }

            $this->__saveSearchItem($type, $item, $data);
        }
    }

    /**
     * @param string $type
     * @param Model  $item
     * @param array  $data
     */
    private function __saveSearchItem($type, $item, $data)
    {
        if ($model = SearchIndex::of($type)->whereSearchableId($item->id)->first()) {
            if ($model->updated_at < $item->updated_at) {
                $model->update($data);
            }
        } else {
            $data['searchable_id'] = $item->id;
            $data['searchable_type'] = get_class($item);

            SearchIndex::create($data);
        }
    }
}
