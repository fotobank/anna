<?php

use proxy\Db as Db;

/** @noinspection PhpIncludeInspection */
include(__DIR__ .  DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR.'system'.
		DIRECTORY_SEPARATOR.'config'. DIRECTORY_SEPARATOR . 'primary_config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');
include("functions.php");

// We don't want web bots accessing this page:
if(is_bot()) die();

// Selecting the top 15 countries with the most visitors:

$result = Db::rawQuery("SELECT countryCode,country, COUNT(*) AS total
						FROM tz_who_is_online
						GROUP BY countryCode
						ORDER BY total DESC
						LIMIT ?",  [15]);

echo '<div id="geodata">';
foreach($result as $row) {
	echo '
	<div class="geoRow">
		<div class="flag"><img src="/inc/who_is_online/img/famfamfam-countryflags/'.strtolower($row['countryCode']).'.gif" width="16" height="11" /></div>
		<div class="country" title="'.htmlspecialchars($row['country']).'">'.$row['country'].'</div>
		<div class="people">'.$row['total'].'</div>
	</div>';
}
echo '</div>';