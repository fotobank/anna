<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace view\Helper\Widget;

use application\Application;
use application\Exception\ForbiddenException;
use view\View;

return
    /**
     * Widget call
     *
     * Example of usage:
     *     $this->widget($module, $controller, array $params);
     *
     * @var View $this
     * @param string $module
     * @param string $widget
     * @param array $params
     * @return void
     */
    function ($controller, $widget, $params = []) {
        try {
            $widgetClosure = Application::getInstance()->widget($controller, $widget);
            $widgetClosure($params);
        } catch (ForbiddenException $e) {
            // nothing for Acl exception
        } catch (\Exception $e) {
            echo $this->exception($e);
        }
    };
