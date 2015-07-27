<?php
/**
 * Класс Proxy Db
 * @created   by PhpStorm
 * @package   Db.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     27.07.2015
 * @time:     10:21
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace classes\pattern\Proxy;

use core\Db\Db as Instance;


/**
 * Proxy to Db
 *
 * Example of usage
 *     use classes\pattern\Proxy\Db;
 *
 *     Db::init()
 *
 * @package  Alex\Proxy
 *
 * @method   static Db|bool init()
 * @see      classes\pattern\Proxy\Db::init()
 *
 * @method   static Db setPrefix($prefix = '')
 * @see      classes\pattern\Proxy\Db::setPrefix()
 *
 * @method   static array rawQuery ($query, $bindParams = null, $sanitize = true)
 * @see      classes\pattern\Proxy\Db::rawQuery ()
 *
 * @method   static array query($query, $numRows = null)
 * @see      classes\pattern\Proxy\Db::query()
 *
 * @method   static Db setQueryOption ($options)
 * @see      classes\pattern\Proxy\Db::setQueryOption()
 *
 * @method   static Db withTotalCount()
 * @see      classes\pattern\Proxy\Db::withTotalCount()
 *
 * @method   static array getOne($tableName, $columns = '*')
 * @see      classes\pattern\Proxy\Db::getOne()
 *
 * @method   static array getValue($tableName, $column)
 * @see      classes\pattern\Proxy\Db::getValue()
 *
 * @method   static boolean insert_($tableName, $insertData)
 * @see      classes\pattern\Proxy\Db::insert_()
 *
 * @method   static boolean insert($tableName, $insertData, $insertPrefix = NULL)
 * @see      classes\pattern\Proxy\Db::insert()
 *
 * @method   static array has($tableName)
 * @see      classes\pattern\Proxy\Db::has()
 *
 * @method   static boolean update($tableName, $tableData)
 * @see      classes\pattern\Proxy\Db::update()
 *
 * @method   static boolean delete($tableName, $numRows = null)
 * @see      classes\pattern\Proxy\Db::delete()
 *
 * @method   static Db where($whereProp, $whereValue = null, $operator = null)
 * @see      classes\pattern\Proxy\Db::where()
 *
 * @method   static Db orWhere($whereProp, $whereValue = null, $operator = null)
 * @see      classes\pattern\Proxy\Db::orWhere()
 *
 * @method   static Db orderBy($orderByField, $orderbyDirection = 'DESC', $customFields = null)
 * @see      classes\pattern\Proxy\Db::orderBy()
 *
 * @method   static Db groupBy($groupByField)
 * @see      classes\pattern\Proxy\Db::groupBy()
 *
 * @method   static integer getInsertId()
 * @see      classes\pattern\Proxy\Db::getInsertId()
 *
 * @method   static bool escape($str)
 * @see      classes\pattern\Proxy\Db::escape()
 *
 * @method   static bool ping()
 * @see      classes\pattern\Proxy\Db::ping()
 *
 * @method   static __destruct()
 * @see      classes\pattern\Proxy\Db::__destruct()
 *
 * @method   static string getLastQuery()
 * @see      classes\pattern\Proxy\Db::getLastQuery()
 *
 * @method   static string getLastError()
 * @see      classes\pattern\Proxy\Db::getLastError()
 *
 * @method   static array getSubQuery()
 * @see      classes\pattern\Proxy\Db::getSubQuery()
 *
 * @method   static string interval($diff, $func = 'NOW()')
 * @see      classes\pattern\Proxy\Db::interval ()
 *
 * @method   static array now($diff = null, $func = 'NOW()')
 * @see      classes\pattern\Proxy\Db::now ()
 *
 * @method   static array inc($num = 1)
 * @see      classes\pattern\Proxy\Db::inc()
 *
 * @method   static array dec($num = 1)
 * @see      classes\pattern\Proxy\dec()
 *
 * @method   static array not($col = null)
 * @see      classes\pattern\Proxy\Db::not()
 *
 * @method   static array func($expr, $bindParams = null)
 * @see      classes\pattern\Proxy\Db::func()
 *
 * @method   static Db subQuery($subQueryAlias = '')
 * @see      classes\pattern\Proxy\Db::subQuery()
 *
 * @method   static mixed copy()
 * @see      classes\pattern\Proxy\Db::copy()
 *
 * @method   static startTransaction()
 * @see      classes\pattern\Proxy\Db::startTransaction()
 *
 * @method   static commit()
 * @see      classes\pattern\Proxy\Db::commit()
 *
 * @method   static rollback()
 * @see      classes\pattern\Proxy\Db::rollback()
 *
 * @method   static _transaction_status_check()
 * @see      classes\pattern\Proxy\Db::_transaction_status_check()
 *
 * @method   static Db setTrace ($enabled, $stripPrefix = null)
 * @see      classes\pattern\Proxy\Db::setTrace()
 *
 * @method   static string _traceGetCaller()
 * @see      classes\pattern\Proxy\Db::_traceGetCaller()
 *
 * @method   static array|bool getParam($file = '')
 * @see      classes\pattern\Proxy\Db::getParam()
 *
 * @method   static connect()
 * @see      classes\pattern\Proxy\Db::connect()
 *
 * @method   static array get($tableName, $numRows = null, $columns = '*')
 * @see      classes\pattern\Proxy\Db::get()
 *
 * @method   static string getCount()
 * @see      classes\pattern\Proxy\Db::getCount()
 *
 *
 * @author   Alex Jurii
 * @package  classes\pattern\Proxy
 */
class Db extends AbstractProxy
{
    /**
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        return $instance;
    }
}