<?php
/**
 *
 * @created   by PhpStorm
 * @package   error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.08.2015
 * @time:     13:13
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

return
[
        // страница - заглушка
        'friendlyExceptionPage' => 'stop',
        // уровень детализации лога
        'logType'               => 'detail',
        // false / simple / detail
        'logDir'                => '',
        // переменные показывамые автоматичесски
        'variables'             => ['_GET', '_POST', '_SESSION', '_COOKIE'],
        // типы игнорируемых ошибок
        'ignoreERROR'           => [],
        // временно включить лог для отладки этого скрипта ( т.к. на '127.0.0.1' лог отключен )
        'log_on'                => true
];