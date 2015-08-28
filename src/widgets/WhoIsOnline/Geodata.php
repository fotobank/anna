<?php
/**
 *
 * @created   by PhpStorm
 * @package   Geodata.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     28.08.2015
 * @time:     1:30
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use proxy\Db;


include('functions.php');

// We don't want web bots accessing this page:
if(is_bot())
{
    die();
}


return function ()
{

// Selecting the top 15 countries with the most visitors:

    $result = Db::rawQuery('SELECT countryCode,country, COUNT(*) AS total
						FROM tz_who_is_online
						GROUP BY countryCode
						ORDER BY total DESC
						LIMIT ?', [15]);

    echo '<div id="geodata">';
    foreach($result as $row)
    {
        echo '
	<div class="geoRow">
		<div class="flag"><img src="/src/widgets/WhoIsOnline/img/famfamfam-countryflags/' .
            strtolower($row['countryCode']) . '.gif" width="16" height="11" /></div>
		<div class="country" title="' . htmlspecialchars($row['country']) . '">' . $row['country'] . '</div>
		<div class="people">' . $row['total'] . '</div>
	</div>';
    }
    echo '</div>';
};