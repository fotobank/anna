<?php

namespace router;


/**
 * Class CaseInsensitiveRoute
 *
 * @package router
 */
class CaseInsensitiveRoute extends Route {

    /**
     * Builds a complete case-insensitive regex that will match a valid URL.
     *
     * @return string
     */
    protected function buildRegex() {
        return sprintf('/^%s%s$/i', $this->regex, $this->wildcard ? '(.*)' : null);
    }

}