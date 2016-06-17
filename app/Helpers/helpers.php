<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

/**
 * Translate the given message.
 * Wrapper for default trans (Lang::get) method
 *
 * @param  string $id
 * @param  array  $parameters
 * @param  string $domain
 * @param  string $locale
 *
 * @return string
 */
if (!function_exists('shortenString')) {
    /**
     * @param        $id
     * @param array  $parameters
     * @param string $domain
     * @param null   $locale
     *
     * @return string
     */
    function l($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        return trans($id, $parameters, $domain, $locale);
    }
}

/**
 * print_r and die
 *
 * @param string $elem
 */
function prd($elem = '')
{
    pr($elem);
    die;
}

/**
 * print_r variable
 *
 * @param string $elem
 */
function pr($elem = '')
{
    echo '<hr><pre>';
    print_r($elem);
    echo '</pre><hr>';
}

/**
 * Show last query to database
 */
if (!function_exists('get_last_query')) {
    /**
     * @return mixed
     */
    function get_last_query()
    {
        $queries = \DB::getQueryLog();
        $sql = end($queries);

        if (!empty($sql['bindings'])) {
            $pdo = \DB::getPdo();
            foreach ($sql['bindings'] as $binding) {
                $sql['query'] =
                    preg_replace(
                        '/\?/',
                        $pdo->quote($binding),
                        $sql['query'],
                        1
                    );
            }
        }

        return $sql['query'];
    }
}

if (!function_exists('active_class')) {
    /**
     * @param        $pattern
     * @param string $class
     *
     * @return string
     */
    function active_class($pattern, $class = 'active')
    {
        $pattern = str_replace('.', '\.', $pattern);
        if (strpos($pattern, '*')) {
            $pattern = str_replace('*', '', $pattern);

            return route_is("^$pattern") ? $class : '';
        } else {
            return route_is("^$pattern$") ? $class : '';
        }
    }
}

if (!function_exists('front_active_class')) {
    /**
     * @param        $pattern
     * @param string $class
     *
     * @return string
     */
    function front_active_class($pattern, $class = 'active')
    {
        $current = trim(Request::root().Request::getPathInfo(), '/');

        $pattern = str_replace(['/', '.'], ['\/', '\.'], $pattern);

        return preg_match("/$pattern/", $current) ? $class : '';
    }
}

if (!function_exists('get_model_by_controller')) {
    /**
     * @param $class
     *
     * @return string
     */
    function get_model_by_controller($class)
    {
        $class = explode('\\', str_replace('Controller', '', $class));

        return array_pop($class);
    }
}

if (!function_exists('domain_get_url')) {
    /**
     * @param $model
     *
     * @return string
     */
    function domain_get_url($model)
    {
        return $model->schema.'://'.$model->uri;
    }
}

if (!function_exists('get_templates')) {
    /**
     * @param string $parent_folder
     *
     * @return string
     */
    function get_templates($parent_folder = '')
    {
        $templates = [];

        if ($parent_folder !== '' && File::exists($parent_folder)) {
            $folders = File::directories($parent_folder);

            if (count($folders)) {
                foreach ($folders as $folder) {
                    $folder = explode('/', $folder);
                    $folder = array_pop($folder);

                    $templates[$folder] = $folder;
                }
            }
        }

        return $templates;
    }
}

if (!function_exists('format_number')) {
    /**
     * @param float  $price
     * @param int    $after_point_count
     * @param string $text
     * @param string $dec_point
     * @param string $thousands_sep
     *
     * @return string
     */
    function format_number($price, $after_point_count = 2, $text = '', $dec_point = '.', $thousands_sep = '')
    {
        return number_format($price, $after_point_count, $dec_point, $thousands_sep).(!empty($text) ? ' '.$text : '');
    }
}

if (!function_exists('delete_file')) {
    /**
     * @param string $path
     */
    function delete_file($path)
    {
        if (!File::exists($path)) {
            $path = base_path($path);
        }

        if (File::exists($path)) {
            unlink($path);
        }
    }
}

if (!function_exists('route_is')) {
    /**
     * @param string $pattern
     *
     * @return boolean
     */
    function route_is($pattern)
    {
        return (preg_match("/$pattern/", \Route::currentRouteName())) ? true : false;
    }
}

if (!function_exists('check_local')) {
    /**
     * @param null $url
     *
     * @return bool
     */
    function check_local($url = null)
    {
        $url = $url ? : Request::root();

        return get_url_host($url) == get_url_host(route('home'));
    }
}

if (!function_exists('get_url_host')) {
    /**
     * @param string $url
     *
     * @return string
     */
    function get_url_host($url)
    {
        preg_match('/^((http[s]?|ftp):\/\/)?(www\.)?([\w\-\.]+)(\/)?(.*)$/i', $url, $host);

        if (!empty($host)) {
            return !empty($host[4]) ? $host[4] : $url;
        }

        return $url;
    }
}

if (!function_exists('get_localized_date')) {
    /**
     * @param string $date
     * @param string $format
     * @param bool   $time_format
     * @param string $time_position
     *
     * @return string
     */
    function get_localized_date($date, $format = 'Y-m-d H:i:s', $time_format = false, $time_position = 'after')
    {
        $date = LocalizedCarbon::createFromFormat($format, $date);

        return
            trim(
                ($time_position == 'before' ? (($time_format) ? $date->format($time_format) : '') : '').' '.
                $date->format('d').' '.
                $date->formatLocalized('%f').' '.
                $date->format('Y').' '.
                ($time_position == 'after' ? (($time_format) ? $date->format($time_format) : '') : '')
            );
    }
}

if (!function_exists('get_hashed_url')) {
    /**
     * @param        $model
     * @param string $type
     * @param string $key_field
     *
     * @return string
     */
    function get_hashed_url($model, $type = 'page', $key_field = 'slug')
    {
        return md5($type.'_'.$model->id.'_'.$model->{$key_field});
    }
}

if (!function_exists('get_part_with_search_text')) {
    /**
     * @param string $text
     * @param string $search_text
     * @param int    $limit
     *
     * @return string
     */
    function get_part_with_search_text($text, $search_text, $limit = null)
    {
        $limit = $limit ? : config('search.default_short_content_length');

        $text = strip_tags($text);
        $len = (int) strlen($search_text);

        $search_text_pos = (int) strpos(mb_strtolower($text), mb_strtolower($search_text)) + $len;

        if ($search_text_pos < $limit) {
            return str_limit($text, $limit);
        }

        return mb_strcut($text, $search_text_pos - $limit, $search_text_pos + $limit, 'UTF-8').'...';
    }
}

if (!function_exists('mark_search_text')) {
    /**
     * @param string $text
     * @param string $search_text
     *
     * @return mixed
     */
    function mark_search_text($text, $search_text)
    {
        foreach (explode(' ', $search_text) as $text_part) {
            if (strlen($text_part) > 1) {
                preg_match_all('/'.$text_part.'/iu', $text, $matches);

                if (count($matches)) {
                    $count = 0;

                    foreach ($matches[0] as $match) {
                        $text = str_replace($match, '<b>'.$match.'</b>', $text, $count);
                    }
                }
            }
        }

        return $text;
    }
}

if (!function_exists('theme_asset')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function theme_asset($path = '')
    {
        return $path ? Theme::asset($path) : '';
    }
}

if (!function_exists('thumb')) {
    /**
     * @param string   $path
     * @param int      $width
     * @param int|null $height
     *
     * @return string
     *
     */
    function thumb($path = '', $width, $height = null)
    {
        $height = $height ? : $width;
        $path = File::exists(public_path($path)) ? $path : false;

        return $path ?
            url(Thumb::thumb($path, $width, $height)->link()) :
            'http://www.placehold.it/'.$width.'x'.$height.'/EFEFEF/AAAAAA&text=no+image';
    }
}

if (!function_exists('add_get_parameters')) {
    /**
     * @param array $parameters
     * @param null  $url
     *
     * @return string
     */
    function add_get_parameters($parameters, $url = null)
    {
        $newParametersArray = [];
        $parameters = array_merge($_GET, $parameters);

        foreach ($parameters as $name => $parameter) {
            $newParametersArray[] = "$name=$parameter";
        }

        sort($newParametersArray);

        $url = $url ? : Request::url();

        return $url.'?'.implode('&', $newParametersArray);
    }
}

if (!function_exists('update_get_parameters')) {
    /**
     * @param array $parameters
     * @param null  $url
     *
     * @return string
     */
    function update_get_parameters($parameters, $url = null)
    {
        $newParametersArray = [];
        $_keys = [];
        $_parameters = $_GET;

        foreach ($_parameters as $_parameter => $_value) {
            if (isset($parameters[$_parameter])) {
                $_value = explode(',', $_value);
                $_value = array_merge($_value, (array) $parameters[$_parameter]);
                $_value = implode(',', $_value);
            }

            $newParametersArray[] = $_parameter.'='.$_value;
            $_keys = $_parameter;
        }

        $parameters = array_except($parameters, $_keys);
        foreach ($parameters as $parameter => $value) {
            $newParametersArray[] = $parameter.'='.implode(',', (array) $value);
        }

        sort($newParametersArray);

        $url = $url ? : Request::url();

        return $url.'?'.implode('&', $newParametersArray);
    }
}

if (!function_exists('remove_get_parameters')) {
    /**
     * @param array $parameters
     * @param null  $url
     *
     * @return string
     */
    function remove_get_parameters($parameters, $url = null)
    {
        $newParametersArray = [];
        $_parameters = $_GET;

        foreach ($_parameters as $_parameter => $_value) {
            $_value = explode(',', $_value);

            if (isset($parameters[$_parameter])) {
                $_value = array_filter(
                    $_value,
                    function ($v) use ($parameters, $_parameter) {
                        return $v != $parameters[$_parameter];
                    }
                );
            }

            if (!empty($_value)) {
                $newParametersArray[] = $_parameter.'='.implode(',', $_value);
            }
        }

        sort($newParametersArray);

        $url = $url ? : Request::url();

        return count($newParametersArray) ? $url.'?'.implode('&', $newParametersArray) : $url;
    }
}

if (!function_exists('remove_get_parameter')) {
    /**
     * @param string $parameter
     * @param null   $url
     *
     * @return string
     */
    function remove_get_parameter($parameter, $url = null)
    {
        $newParametersArray = [];
        $_parameters = $_GET;

        foreach ($_parameters as $_parameter => $_value) {
            if ($_parameter != $parameter) {
                $newParametersArray[] = $_parameter.'='.$_value;
            }
        }

        sort($newParametersArray);

        $url = $url ? : Request::url();

        return count($newParametersArray) ? $url.'?'.implode('&', $newParametersArray) : $url;
    }
}

if (!function_exists('in_get')) {
    /**
     * @param string $parameter
     * @param string $value
     *
     * @return bool
     */
    function in_get($parameter = '', $value = '')
    {
        $values = isset($_GET[$parameter]) ? $_GET[$parameter] : null;

        if (!$values) {
            return false;
        }

        $values = explode(',', $values);

        if (!in_array($value, $values)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('get_class_name_from_namespace')) {
    /**
     * @param string|Object $object
     *
     * @return string
     */
    function get_class_name_from_namespace($object)
    {
        if (is_object($object)) {
            $object = class_basename($object);
        }

        $object = explode('\\', $object);

        return array_pop($object);
    }
}

if (!function_exists('studly_camel_case')) {
    /**
     * @param string
     *
     * @return string
     */
    function studly_camel_case($string)
    {
        return studly_case(camel_case($string));
    }
}

if (!function_exists('make_locales_fakers')) {
    /**
     * @return array
     */
    function make_locales_fakers()
    {
        $fakers = [];

        foreach (Config::get('app.locales') as $locale) {
            $fakers[$locale] = Faker\Factory::create(
                config('laravellocalization.supportedLocales.'.$locale.'.regional')
            );
        }

        return $fakers;
    }
}

if (!function_exists('valid_youtube_link')) {
    /**
     * @param string $link
     *
     * @return bool
     */
    function valid_youtube_link($link)
    {
        return preg_match('/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v\=|youtu\.be\/)([^&\/\?]+)/', $link);
    }
}

if (!function_exists('localize_url')) {
    /**
     * @param null|string $url
     * @param null|string $locale
     *
     * @return string
     */
    function localize_url($url = null, $locale = null)
    {
        $locale = $locale ? : app()->getLocale();
        $url = $url ? ($url == '/' ? route('home') : $url) : URL::full();

        return check_local($url) ? LaravelLocalization::getLocalizedURL($locale, $url) : $url;
    }
}

if (!function_exists('localize_route')) {
    /**
     * @param string                     $name
     * @param  array|null                $parameters
     * @param string|null                $locale
     * @param  bool                      $absolute
     * @param  \Illuminate\Routing\Route $route
     *
     * @return mixed
     */
    function localize_route($name = 'home', $parameters = null, $locale = null, $absolute = true, $route = null)
    {
        $locale = $locale ? : app()->getLocale();
        $url = route($name, $parameters, $absolute, $route);
        
        return LaravelLocalization::getLocalizedURL($locale, $url);
    }
}

if (! function_exists('variable')) {
    /**
     * Get / set the specified variable value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function variable($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('variable');
        }

        return app('variable')->get($key, $default);
    }
}