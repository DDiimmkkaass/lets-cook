<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 01.11.16
 * Time: 9:58
 */

namespace App\Classes;

use Barryvdh\TranslationManager\Models\Translation;
use Exception;
use Illuminate\Translation\LoaderInterface;

/**
 * Class DBLoader
 * @package App\Classes
 */
class DBLoader implements LoaderInterface
{
    
    /**
     * Load the messages for the given locale.
     *
     * @param  string $locale
     * @param  string $group
     * @param  string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        try {
            return Translation::whereLocale($locale)->whereGroup($group)
                ->get(['key', 'value'])
                ->keyBy('key')
                ->map(
                    function ($item) {
                        return $item['value'];
                    }
                )
                ->toArray();
        } catch (Exception $e) {
            // just insure themselves in case of problems with the database
            admin_notify(
                'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile(),
                ['locale' => $locale, 'group' => $group]
            );
            
            return [];
        }
    }
    
    /**
     * Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     *
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        // TODO: Implement addNamespace() method.
    }
}