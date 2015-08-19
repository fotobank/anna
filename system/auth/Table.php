<?php
/**
 * Класс Table
 *
 * @created   by PhpStorm
 * @package   Table.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     19.08.2015
 * @time:     21:04
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace auth;

use proxy\Auth;
use proxy\Db;
use proxy\Session;




/**
 * Class Table
 *
 * В контроллере авторизации:
 *
 * login/password
 * auth\Table::getInstance()->authenticateEquals($login, $password);
 * Если вам необходимо проверить авторизирован ли пользователь без привязки к его привилегиям:
 *
 * use proxy\Auth;
 * if ($identity = Auth::getIdentity()) {
 * all ok
 * }
 *
 * @package application
 *
 */
class Table extends AbstractTable implements EntityInterface
{

    /**
     * Pending email verification
     */
    const STATUS_PENDING = 'pending';
    /**
     * Active user
     */
    const STATUS_ACTIVE = 'active';
    /**
     * Disabled by administrator
     */
    const STATUS_DISABLED = 'disabled';
    /**
     * Removed account
     */
    const STATUS_DELETED = 'deleted';
    /**
     * system user with ID=0
     */
    const SYSTEM_USER = 0;
    /**
     * Time that the token remains valid
     */
    const TOKEN_EXPIRATION_TIME = 1800;

    /**
     * Small cache of user privileges
     * @var array
     */
    protected $privileges;
    // статус юзера
    protected $status;

    /**
     * @param $username
     * @param $password
     *
     * @throws \auth\AuthException
     */
    public function authenticateEquals($username, $password)
    {
        $userId = $this->checkEquals($username, $password)['userId'];
        Db::where('auth', $userId);
        $this->status = Db::getOne('db_users', 'status')['status'];
        // try to login
        $this->login();
    }

    /**
     * authenticate user by token
     * @param $token
     *
     * @throws \auth\AuthException
     */
    public function authenticateToken($token)
    {
        $userId = $this->checkToken($token)['userId'];
        Db::where('auth', $userId);
        $this->status = Db::getOne('db_users', 'status')['status'];
        // try to login
        $this->login();
    }

    /**
     * @param $token
     *
     * @return mixed
     * @throws \auth\AuthException
     */
    public function checkToken($token)
    {
        Db::where('token',  $token);
        Db::where('provider', self::PROVIDER_TOKEN);
        $authRow = Db::getOne('auth');
        if (!$authRow ) {
            throw new AuthException('Invalid token');
        }

        return $authRow;
    }

    /**
     * Check user by login/pass
     *
     * @param string $username
     * @param string $password
     *
     * @return array
     * @throws \auth\AuthException
     */
    public function checkEquals($username, $password)
    {
        $authRow = $this->getAuthRow(self::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException('User not found');
        }

        // verify password
        if (!$this->callVerifyFunction($password, $authRow['token'])) {
            throw new AuthException('Wrong password');
        }

        // get auth row
        return $authRow;
    }

    /**
     * Call Verify Function
     *
     * @param string $password
     * @param string $hash
     *
     * @return string
     * @throws \auth\AuthException
     */
    protected function callVerifyFunction($password, $hash)
    {

        $options = Auth::getOption(self::PROVIDER_EQUALS);

        if (!isset($options['verify']) or !is_callable($options['verify'])
        ) {
            throw new AuthException('Verify function for "equals" adapter is not callable');
        }

        // verify password with hash
        return call_user_func($options['verify'], $password, $hash);
    }

    /**
     * Call Hash Function
     *
     * @param string $password
     *
     * @return string
     * @throws \auth\AuthException
     */
    protected function callHashFunction($password)
    {
        $options = Auth::getOption(self::PROVIDER_EQUALS);

        if (!isset($options['hash']) or !is_callable($options['hash'])
        ) {
            throw new AuthException('Hash function for \'equals\' adapter is not callable');
        }

        // encrypt password with secret
        return call_user_func($options['hash'], $password);
    }


    /**
     * Can entity login
     *
     * @return bool|null
     * @throws \auth\AuthException
     * @internal param $authRow
     *
     */
    public function login()
    {
        switch ($this->status) {
            case (Table::STATUS_PENDING):
                throw new AuthException('Your account is pending activation', 403);
            case (Table::STATUS_DISABLED):
                throw new AuthException('Your account is disabled by administrator', 403);
            case (Table::STATUS_ACTIVE):
                // all ok
                // regenerate session
                if (PHP_SAPI !== 'cli') {
                    Session::regenerateId();
                }
                // save user to new session
                Auth::setIdentity($this);
                break;
            default:
                throw new AuthException('User status is undefined in system');
        }
    }

    /**
     * @param $equalAuth
     *
     * @return \auth\Row
     * @throws \auth\AuthException
     */
    public function generateToken($equalAuth)
    {
        // clear previous generated Auth record
        // works with change password
        $this->delete(
            [
                'userId' => $equalAuth->userId,
                'foreignKey' => $equalAuth->foreignKey,
                'provider' => self::PROVIDER_TOKEN,
                'tokenType' => self::TYPE_ACCESS,
            ]
        );
        // new auth row
        $row = new Row();
        $row->userId = $equalAuth->userId;
        $row->foreignKey = $equalAuth->foreignKey;
        $row->provider = self::PROVIDER_TOKEN;
        $row->tokenType = self::TYPE_ACCESS;
        $row->expired = gmdate('Y-m-d H:i:s', time() + self::TOKEN_EXPIRATION_TIME);

        // generate secret
        $row->tokenSecret = $this->generateSecret($equalAuth->userId);

        // encrypt password and save as token
        $row->token = $this->callHashFunction($equalAuth->token);

        $row->save();

        return $row;
    }

    /**
     * Get user privileges
     */
    public function getPrivileges()
    {
        if (!$this->privileges) {
        //    $this->privileges = Privileges\Table::getInstance()->getUserPrivileges($this->id);
        }
        return $this->privileges;
    }

    /**
     * Has role a privilege
     *
     * @param string $module
     * @param string $privilege
     * @return boolean
     */
    public function hasPrivilege($module, $privilege)
    {
        return true;
    }

}