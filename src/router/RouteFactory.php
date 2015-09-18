<?php

namespace router;
/**
 * Class RouteFactory
 *
 * @package router\Router
 */
class RouteFactory {

    /**
     * @param       $pattern
     * @param array $dispatch
     *
     * @return \router\Route
     * @throws \Exception
     */
    public function newRoute($pattern, array $dispatch = []) {
        try
        {
            return new Route($pattern, $dispatch);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}
