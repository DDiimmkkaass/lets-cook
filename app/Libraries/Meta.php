<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 17.06.15
 * Time: 0:40
 */

/**
 * Class Meta
 * @package App\Libraries
 */
class Meta
{

    /**
     * @var array
     */
    static $tags = [
        "title"       => "<title>%s</title>\n",
        "description" => "<meta content=\"%s\" name=\"description\" />\n",
        "keywords"    => "<meta content=\"%s\" name=\"keywords\" />\n",
        "base"        => "<base href=\"%s\"/>\n",
        "canonical"   => "<link rel=\"canonical\" href=\"%s\"/>\n",
        "custom"      => "%s\n",
        "social"      => "<meta property=\"%s\" content=\"%s\" />\n",
    ];

    /**
     * @var array
     */
    static $data = [
        "title"       => [],
        "site_name"   => "",
        "description" => "",
        "keywords"    => "",
        "base"        => "",
        "canonical"   => "",
        "url"         => "",
        "locale"      => "",
        "image"       => "",
        "custom"      => [],
        "social"      => [
            "og:title"       => "",
            "og:site_name"   => "",
            "og:description" => "",
            "og:url"         => "",
            "og:image"       => "",
            "og:type"        => "website",
            "og:locale"      => "",
            "vk:image"       => "",
            "vk:title"       => "",
            "vk:description" => "",
        ]
    ];

    /**
     * @var string
     */
    static $titleDelimiter = " - ";

    /**
     * @param string $property
     *
     * @return mixed
     * @throws \Exception
     */
    public static function _get($property)
    {

        if (array_key_exists($property, self::$data))
        {
            return self::$data[$property];
        }

        if (strtolower($property = substr($property, 0, 6) == 'social'))
        {
            $_property = strtolower(substr($property, 6, 2)) . ':' . strtolower(substr($property, 8));

            if (array_key_exists($_property, self::$data['social']))
            {
                return self::$data['social'][$_property];
            }
        }

        $_property = strtolower(substr($property, 0, 2)) . ':' . strtolower(substr($property, 2));

        if (array_key_exists($_property, self::$data['social']))
        {
            return self::$data['social'][$_property];
        }

        return "";
    }

    /**
     * @param string $property
     * @param mixed  $arguments
     */
    public static function _set($property, $arguments)
    {

        if (array_key_exists($property, self::$data))
        {
            self::$data[$property] = $arguments;
        }

        if (strtolower($property = substr($property, 0, 6) == 'social'))
        {
            $_property = strtolower(substr($property, 6, 2)) . ':' . strtolower(substr($property, 8));

            if (array_key_exists($_property, self::$data['social']))
            {
                self::$data['social'][$property] = $arguments;
            }
        }

        $_property = strtolower(substr($property, 0, 2)) . ':' . strtolower(substr($property, 2));

        if (array_key_exists($_property, self::$data['social']))
        {
            self::$data['social'][$_property] = $arguments;
        }

        self::$data[$property] = $arguments;
    }

    /**
     * @param string $name
     * @param mixed  $arguments
     *
     * @return bool|mixed
     */
    public static function __callStatic($name, $arguments)
    {

        $action = substr($name, 0, 3);

        switch ($action)
        {
            case 'get':
                if (is_callable([self, 'get' . (string) $name]))
                {
                    return call_user_func([self, 'get' . (string) $name]);
                }

                $property = strtolower(substr($name, 3));

                return self::_get($property);

                break;
            case 'set':
                if (is_callable([self, 'set' . (string) $name]))
                {
                    return call_user_func([self, 'set' . (string) $name], $arguments);
                }

                $property = strtolower(substr($name, 3));

                self::_set($property, $arguments[0]);

                break;
            default :
                $property = $name;

                if (!empty($arguments))
                {
                    self::_set($property, $arguments[0]);
                }
                else
                {
                    return self::_get($property);
                }
        }
    }

    /**
     * @param $str
     */
    public static function title($str)
    {

        if (!empty($str))
        {
            array_unshift(self::$data['title'], $str);
        }
    }

    /**
     * clear title
     */
    public static function clearTitle()
    {

        self::$data['title'] = [];
    }

    /**
     * @return string
     */
    public static function render()
    {

        $output = sprintf(self::$tags['title'], implode(self::$titleDelimiter, self::$data['title']));

        if (!empty(self::$data['description']))
        {
            $output .= sprintf(self::$tags['description'], self::$data['description']);
        }

        if (!empty(self::$data['keywords']))
        {
            $output .= sprintf(self::$tags['keywords'], self::$data['keywords']);
        }

        if (!empty(self::$data['canonical']))
        {
            $output .= sprintf(self::$tags['canonical'], self::$data['canonical']);
        }

        foreach (self::$data['custom'] as $custom)
        {
            $output .= sprintf(self::$tags['custom'], $custom);
        }

        foreach (self::$data['social'] as $property => $value)
        {
            if (!empty($value))
            {
                $output .= sprintf(self::$tags['social'], $property, $value);
            }
            else
            {
                $value = self::_getSocialMeta($property);

                if (!empty($value))
                {
                    $output .= sprintf(self::$tags['social'], $property, $value);
                }
            }

        }

        return $output;
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private static function _getSocialMeta($property)
    {

        $property  = explode(":", $property);
        $property  = isset($property[1]) ? $property[1] : "";

        $content = isset(self::$data[$property]) ? self::$data[$property] : "";

        if (is_array($content))
        {
            $content = implode(self::$titleDelimiter, $content);
        }

        return $content;
    }
}