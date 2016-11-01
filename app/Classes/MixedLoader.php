<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 01.11.16
 * Time: 9:34
 */

namespace App\Classes;

use Illuminate\Translation\LoaderInterface;

class MixedLoader implements LoaderInterface
{
    
    /**
     * db loader
     *
     * @var LoaderInterface
     */
    protected $primaryLoader;
    
    /**
     * file loader
     *
     * @var LoaderInterface
     */
    protected $secondaryLoader;
    
    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $hints = [];
    
    /**
     *  Create a new mixed loader instance.
     *
     * @param LoaderInterface $primaryLoader
     * @param LoaderInterface $secondaryLoader
     */
    public function __construct(LoaderInterface $primaryLoader, LoaderInterface $secondaryLoader)
    {
        $this->primaryLoader = $primaryLoader;
        $this->secondaryLoader = $secondaryLoader;
    }
    
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
        return array_replace_recursive(
            $this->secondaryLoader->load($locale, $group, $namespace),
            $this->primaryLoader->load($locale, $group, $namespace)
        );
    }
    
    /**
     *  Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     *
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
        $this->secondaryLoader->addNamespace($namespace, $hint);
    }
}
