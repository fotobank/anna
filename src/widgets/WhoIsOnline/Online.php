<?php
/**
 *
 * @created   by PhpStorm
 * @package   Online.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     28.08.2015
 * @time:     1:29
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use proxy\Cookie;
use proxy\Db;


include('functions.php');

// We don't want web bots scewing our stats:
if(is_bot())
{
    die();
}


return

    function ()
    {


        $stringIp = ip();
        $intIp    = ip2long($stringIp);

// Checking wheter the visitor is already marked as being online:
        Db::where('ip', $intIp);
        $inDB = Db::getOne('tz_who_is_online', '1');


        if(!count($inDB))
        {
            // This user is not in the database, so we must fetch
            // the geoip data and insert it into the online table:
            if(Cookie::has('geoData'))
            {
                // A "geoData" cookie has been previously set by the script, so we will use it
                // Always escape any user input, including cookies:
                list($city, $countryName, $countryAbbrev) = explode('|', strip_tags(Cookie::get('geoData')));
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


                include('geo/geoipcity.php');
                $gi     = geoip_open('geo/GeoLiteCity.dat', GEOIP_STANDARD);
                $record = geoip_record_by_addr($gi, $stringIp);
                if(is_null($record))
                {
                    $city          = '(Unknown City?)';
                    $countryName   = 'UNKNOWN';
                    $countryAbbrev = 'XX';
                }
                else
                {
                    $city          = $record->city;
                    $countryName   = $record->country_name;
                    $countryAbbrev = $record->country_code;
                }
                geoip_close($gi);

                // Setting a cookie with the data, which is set to expire in a month:
                Cookie::set('geoData', $city . '|' . $countryName . '|' . $countryAbbrev, time() + 60 * 60 * 24 * 30,
                            '/');
            }

            $countryName = str_replace('(Unknown Country?)', 'UNKNOWN', $countryName);

            // In case the Hostip API fails:
            /*if (!$countryName)
            {
                $countryName='UNKNOWN';
                $countryAbbrev='XX';
                $city='(Unknown City?)';
            }*/

            $values = [
                'ip'          => 'INET_ATON(' . $intIp . ')',
                'country'     => $countryName,
                'countrycode' => $countryAbbrev,
                'city'        => $city,
            ];
            Db::insert('tz_who_is_online', $values);
        }
        else
        {
            // If the visitor is already online, just update the dt value of the row:
            Db::rawQuery('UPDATE `tz_who_is_online` SET dt=NOW() WHERE ip=' . $intIp);

        }

// Removing entries not updated in the last 10 minutes:  Now()
        Db::where('Unix_timestamp(dt)', (time() - 600), '<');
        Db::delete('tz_who_is_online');

// Counting all the online visitors:
        $number = Db::getOne('tz_who_is_online', 'count(*) as online');

        echo('<span id="online">' . $number['online'] . '</span>');
    };