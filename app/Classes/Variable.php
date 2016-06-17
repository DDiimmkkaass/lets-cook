<?php
/**
 * Created by PhpStorm.
 * User: ddiimmkkaass
 * Date: 07.04.16
 * Time: 21:47
 */

namespace App\Classes;

use App\Models\Variable as VariableModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Exception;
use Schema;

/**
 * Class Variable
 * @package App\Classes
 */
class Variable
{

    /**
     * @var Collection
     */
    protected $variables = [];

    /**
     * Variable constructor.
     */
    public function __construct()
    {
        if (Schema::hasTable('variables')) {
            $this->variables = VariableModel::withTranslations()->visible()->get()->keyBy('key');
        }
    }

    /**
     * merge variables with laravel config, config value will be replaced
     */
    public function mergeWithConfig()
    {
        foreach ($this->variables as $variable) {
            if (config()->has($variable->key)) {
                config()->set($variable->key, $variable->getValue());
            }
        }
    }

    /**
     * Get the specified variable value.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $variable = Arr::get($this->variables, $key, $default);

        return $variable instanceof Model ? $variable->getValue() : $default;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->variables;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $variables = [];

        foreach ($this->variables as $variable) {
            $variables[$variable->key] = $variable->getValue();
        }
        
        return $variables;
    }
}