<?php
/**
 * Framework Component
 *
 */

/**
 * @namespace
 */
namespace view\Helper\Url;

use view\View;

return
    /**
     * Generate URL
     *
     * @var View     $this
     *
     * @param string $module
     * @param string $controller
     * @param array  $params
     * @param bool   $checkAccess
     *
     * @return string|null
     */
    function ($controller, $method, $params = [])
    {
        $url       = $controller . '/' . $method;
        $getParams = [];
        foreach($params as $key => $value)
        {
            // sub-array as GET params
            if(is_array($value))
            {
                $getParams[$key] = $value;
                continue;
            }
            $url .= '/' . urlencode($key) . '/' . urlencode($value);
        }
        if(0 !== count($getParams))
        {
            $url .= '?' . http_build_query($getParams);
        }

        return $url;
    };
