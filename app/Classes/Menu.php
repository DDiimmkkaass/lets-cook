<?php
/**
 * Created by Newway, info@newway.com.ua.
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 20.11.16
 * Time: 21:35
 */

namespace App\Classes;

use App\Models\Menu as MenuModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Menu
 * @package App\Classes
 */
class Menu
{
    
    /**
     * @var Collection
     */
    protected $menus = [];
    
    /**
     * Menu constructor.
     */
    public function __construct()
    {
        try {
            $this->menus = MenuModel::with('visible_items')->visible()->get();
        } catch (Exception $e) {
            // just insure themselves in case of problems with the database
            
            $this->menus = collect();
        }
    }
    
    /**
     * Get the specified menu.
     *
     * @param  string $layout_position
     *
     * @return mixed
     */
    public function get($layout_position)
    {
        return $this->menus->where('layout_position', $layout_position);
    }
}