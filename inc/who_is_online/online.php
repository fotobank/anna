<?php
require( __DIR__ . '/../../inc/config.php' );
require "functions.php";

// We don't want web bots scewing our stats:
if(is_bot()) die();

$stringIp = ip();
$intIp = ip2long($stringIp);

// Checking wheter the visitor is already marked as being online:
$db->where("ip", $intIp);
$inDB = $db->getOne("tz_who_is_online", '1');


if(!count($inDB))
{
	// This user is not in the database, so we must fetch
	// the geoip data and insert it into the online table:
	if(!$_COOKIE['geoData'])
	{
		// A "geoData" cookie has been previously set by the script, so we will use it
		// Always escape any user input, including cookies:
		list($city,$countryName,$countryAbbrev) = explode('|', strip_tags($_COOKIE['geoData']));
	}
	else
	{

		// Making an API call to Hostip:
		/*$xml = file_get_contents('http://api.hostip.info/?ip='.$stringIp);

		$city = get_tag('gml:name',$xml);
		$city = $city[1];
		
		$countryName = get_tag('countryName',$xml);
		$countryName = $countryName[0];
		
		$countryAbbrev = get_tag('countryAbbrev',$xml);
		$countryAbbrev = $countryAbbrev[0];*/


		include("geo/geoipcity.php");
		$gi = geoip_open("geo/GeoLiteCity.dat",GEOIP_STANDARD);
		$record = geoip_record_by_addr($gi, $stringIp);
		if( is_null($record) )
		{
			$city='(Unknown City?)';
			$countryName='UNKNOWN';
			$countryAbbrev='XX';
		} else {
			$city = $record->city;
			$countryName = $record->country_name;
			$countryAbbrev = $record->country_code;
		}
		geoip_close($gi);

		// Setting a cookie with the data, which is set to expire in a month:
		setcookie('geoData', $city.'|'.$countryName.'|'.$countryAbbrev, time()+60*60*24*30,'/');
	}
	
	$countryName = str_replace('(Unknown Country?)','UNKNOWN', $countryName);
	
	// In case the Hostip API fails:
	/*if (!$countryName)
	{
		$countryName='UNKNOWN';
		$countryAbbrev='XX';
		$city='(Unknown City?)';
	}*/

	$values = array(
		'ip' => 'INET_ATON('.$intIp.')',
		'country' => $countryName,
		'countrycode' => $countryAbbrev,
		'city' => $city
	);
	$db->insert("tz_who_is_online", $values);
}
else
{
	// If the visitor is already online, just update the dt value of the row:
	$db->rawQuery("UPDATE tz_who_is_online SET dt=NOW() WHERE ip=".$intIp);

}

// Removing entries not updated in the last 10 minutes:  Now()
$db->where("Unix_timestamp(dt)", (time() - 600), "<");
$db->delete("tz_who_is_online");

// Counting all the online visitors:
$number = $db->getOne ("tz_who_is_online", "count(*) as online");

echo ('<span id="online">'. $number["online"] . '</span>');