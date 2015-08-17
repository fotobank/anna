<?php
/**
 * Bluz Framework Component
 *
 */

/**
 * @namespace
 */
namespace Bluz\Auth;

use proxy\Db;

/**
 * Abstract class for Auth\Table
 */
abstract class AbstractTable
{
    /**
     * Types
     */
    const TYPE_ACCESS = 'access';
    const TYPE_REQUEST = 'request';

    /**
     * Providers
     *  - equals - login+password
     *  - token - token with ttl
     *  - cookie - cookie token with ttl
     */
    const PROVIDER_COOKIE = 'cookie';
    const PROVIDER_EQUALS = 'equals';
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_LDAP = 'ldap';
    const PROVIDER_TOKEN = 'token';
    const PROVIDER_TWITTER = 'twitter';

    /**
     * @var string Table
     */
    protected $table = 'auth';

    /**
     * @var array Primary key(s)
     */
    protected $primary = ['provider', 'foreignKey'];

    /**
     * Get AuthRow
     * @param string $provider
     * @param string $foreignKey
     * @return AbstractRow
     */
    public function getAuthRow($provider, $foreignKey)
    {
        Db::where('provider', $provider);
        Db::where('foreignKey', $foreignKey);
        $find_row = Db::get('auth');
        return $find_row;
    }

    /**
     * Generate Secret token
     * @param int $id
     * @return string
     */
    protected function generateSecret($id)
    {
        // generate secret
        $alpha = range('a', 'z');
        shuffle($alpha);
        $secret = array_slice($alpha, 0, mt_rand(5, 15));
        return md5($id . implode('', $secret));
    }
}
