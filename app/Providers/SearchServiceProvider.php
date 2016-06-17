<?php

namespace App\Providers;

use App\Models\SearchIndex;
use Carbon;
use Illuminate\Support\ServiceProvider;

/**
 * Class SearchServiceProvider
 * @package App\Providers
 */
class SearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (SearchIndex::getTypes() as $translated_model) {
            $translating_model = '\\'.$translated_model.'Translation';

            if (class_exists($translating_model)) {
                $translating_model::updated(
                    function ($_translating_model) use ($translating_model, $translated_model) {
                        $foreign_key = snake_case(class_basename($translated_model)).'_id';

                        $model = $translated_model::whereId($_translating_model->{$foreign_key})->first();
                        if ($model) {
                            $model->updated_at = Carbon::now();
                            $model->save();
                        }
                    }
                );
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
