<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace auth;

/**
 * Interface EntityInterface
 * @package auth
 */
interface EntityInterface
{
    /**
     * Get user privileges
     * @return array
     */
    public function getPrivileges();

    /**
     * Has role a privilege
     * @param string $module
     * @param string $privilege
     * @return bool
     */
    public function hasPrivilege($module, $privilege);
}
