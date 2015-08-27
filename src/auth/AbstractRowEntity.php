<?php
/**
 * @namespace
 */
namespace auth;

/**
 * Abstract class for Users\Row
 *
 * @property integer $id
 */
abstract class AbstractRowEntity implements EntityInterface
{
    /**
     * Can entity login
     * @throws AuthException
     * @return bool
     */
    abstract public function login();

    /**
     * Get privileges
     * @return array
     */
    abstract public function getPrivileges();

    /**
     * Has role a privilege
     * @param string $module
     * @param string $privilege
     * @return bool
     */
    public function hasPrivilege($module, $privilege)
    {
        $privileges = $this->getPrivileges();

        return in_array($module.':'.$privilege, $privileges, true);
    }
}
