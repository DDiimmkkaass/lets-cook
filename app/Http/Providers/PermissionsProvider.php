<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Providers;

use App\Contracts\Permissions as PermissionsContract;
use Config;

/**
 * Class PermissionsProvider
 * @package App\Http\Providers
 */
class PermissionsProvider implements PermissionsContract
{

    /**
     * Load permissions array from app config
     *
     * @return array
     */
    public function load()
    {
        $perm = Config::get('permissions');
        if (!is_array($perm)) {
            return [];
        }

        $perm = array_fill_keys(array_values($perm), 0);

        return $perm;
    }

    /**
     * Load permissions list in multi-dimensional array
     *
     * @return array
     */
    public function loadArray()
    {
        $permissions = $this->load();

        $result = [];
        foreach ($permissions as $key => $value) {
            array_set($result, $key, 0);
        }

        return $result;
    }

    /**
     * Load actual permissions
     *
     * @param $groupPermissions
     *
     * @return array
     */
    public function loadAndCombine($groupPermissions)
    {
        $permissions = $this->load();

        // оставляем только те ключи, которые есть в актуальном списке прав
        // другими словами, все старые права, которые более не требуются, будут недоступны
        $intersect = array_intersect_key($groupPermissions, $permissions);

        $permissions = array_merge($permissions, $intersect);

        return $this->_MDArray($permissions);
    }

    /**
     * convert multi-dimensional array
     */
    private function _MDArray($permissions)
    {
        $result = [];
        foreach ($permissions as $key => $value) {
            array_set($result, $key, $value);
        }

        return $result;
    }
}