<?php

require (__DIR__ .'/../../system/config/config.php');
require "functions.php";

// We don't want web bots accessing this page:
if(is_bot()) die();

// Selecting the top 15 countries with the most visitors:

$result = $db->rawQuery("SELECT countryCode,country, COUNT(*) AS total
						FROM tz_who_is_online
						GROUP BY countryCode
						ORDER BY total DESC
						LIMIT ?",  array(15));

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
?>