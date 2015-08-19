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



/**
 * Class Table
 *
 * @package application
 */
class Table extends AbstractTable
{
    public function authenticateEquals($username, $password)
    {
        $authRow = $this->checkEquals($username, $password);

        // get user profile
        $user = Users\Table::findRow($authRow->userId);

        // try to login
        $user->login();
    }

    public function checkEquals($username, $password)
    {
        $authRow = $this->getAuthRow(self::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException('User not found');
        }

        // encrypt password
        $encrypt = $this->callEncryptFunction($password, $authRow->tokenSecret);

        if ($encrypt != $authRow->token) {
            throw new AuthException('Wrong password');
        }

        // get auth row
        return $authRow;
    }
}