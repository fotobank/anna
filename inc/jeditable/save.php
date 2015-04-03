<?php

require_once (__DIR__ .'/../../inc/config.php');
header( 'Content-type: text/html; charset=windows-1251' );
header("Cache-Control: no-cache");

$db->where("teg_id", $_POST['id']);
//$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', strip_tags( nl2br($_POST['value']), '<br>')));
$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($_POST['value']))));
$text = preg_replace('/[tm:]*/i', '', $text);
$value = array(
	'text' => $text,
);
$db->update("spec_category", $value);

//$db->rawQuery("UPDATE spec_category SET text = ".stripslashes($_POST['value'])." WHERE id=". $_POST['id']);

/* sleep for a while so we can see the indicator in demo */
usleep(500);

print $value['text'];