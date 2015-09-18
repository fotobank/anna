<?php

namespace router;


/**
 * Class CaseInsensitiveRouteFactory
 *
 * @package router
 */
class CaseInsensitiveRouteFactory extends RouteFactory {

    /**
     * @param       $pattern
     * @param array $dispatch
     *
     * @return \router\Route
     * @throws \Exception
     */
    public function newRoute($pattern, array $dispatch = []) {
        return new CaseInsensitiveRoute($pattern, $dispatch);
    }

}
