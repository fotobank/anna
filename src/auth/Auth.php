<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace auth;

use common\Options;
use lib\Config\InterfaceConfig;
use proxy\Server;
use proxy\Session;

/**
 * Auth class
 *
 * @package Auth
 */
class Auth
{
    use Options;

    /**
     * @var EntityInterface Instance of EntityInterface
     */
    protected $identity;

    /**
     * Setup, check and init options
     *
     * Requirements
     * - options must be a array
     * - options can be null
     *
     * @param array|\lib\Config\Config $options
     *
     * @return \auth\Auth
     * @throws \Exception
     */
    public function setOptions(InterfaceConfig $options)
    {
        try
        {
            $this->options = $options->getData('auth');

            // apply options
            foreach($this->options as $key => $value)
            {
                $this->setOption($key, $value);
            }

            return $this;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Setup identity
     * @api
     * @param EntityInterface $identity
     * @return void
     */
    public function setIdentity(EntityInterface $identity)
    {
        // save identity to Auth
        $this->identity = $identity;
        // save identity to session
        Session::set('auth/identity', $identity);
        // save user agent to session
        Session::set('auth/agent', Server::get('HTTP_USER_AGENT'));
    }

    /**
     * Return identity if user agent is correct
     * @api
     * @return EntityInterface|null
     */
    public function getIdentity()
    {
        if (!$this->identity) {
            // check user agent
            if (Session::get('auth/agent') == Server::get('HTTP_USER_AGENT')) {
                $this->identity = Session::get('auth/identity');
            } else {
                $this->clearIdentity();
            }
        }
        return $this->identity;
    }

    /**
     * Clear identity and user agent information
     * @api
     * @return void
     */
    public function clearIdentity()
    {
        $this->identity = null;
        Session::del('auth/identity');
        Session::del('auth/agent');
    }
}
