<?php
/**
 * Alex Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use auth\Auth as Instance;
use auth\EntityInterface;

/**
 * Proxy to Auth
 *
 * Example of usage
 *     use proxy\Auth;
 *
 *     $user = Auth::getIdentity();
 *
 * @package  Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static void setIdentity(EntityInterface $identity)
 * @see      auth\Auth::setIdentity()
 *
 * @method   static EntityInterface getIdentity()
 * @see      auth\Auth::getIdentity()
 *
 * @method   static void clearIdentity()
 * @see      auth\Auth::clearIdentity()
 *
 * @method   static mixed getOption($key, $section = null)
 * @see      auth\Auth::getOption()
 */
class Auth extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        $instance->setOptions(Config::getData('auth'));
        return $instance;
    }
}
