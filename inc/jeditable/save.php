<?php

require_once (__DIR__ .'/../../inc/config.php');

/*
 Check submitted value
*/
if(!empty($_POST['value'])) {

	$pk = $_POST['pk'];
	$name = $_POST['name'];
	$value = $_POST['value'];

	$db->where("teg_id", $_POST['id']);
//$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', strip_tags( nl2br($_POST['value']), '<br>')));
	$text = cp1251(preg_replace('/[\n\t]{1,}/i', '', nl2br(cleanInput($_POST['value']))));
	$text = preg_replace('/[tm:]*/i', '', $text);
	$value = array(
		'text' => $text,
	);
	$db->update("spec_category", $value);

	//$db->rawQuery("UPDATE spec_category SET text = ".stripslashes($_POST['value'])." WHERE id=". $_POST['id']);

	/*
	  If value is correct you process it (for example, save to db).
	  In case of success your script should not return anything, standard HTTP response '200 OK' is enough.

	  for example:
	  $result = mysql_query('update users set '.mysql_escape_string($name).'="'.mysql_escape_string($value).'" where user_id = "'.mysql_escape_string($pk).'"');
	*/


	/* sleep for a while so we can see the indicator in demo */
	usleep(500);

	header( 'Content-type: text/html; charset=windows-1251' );
	header("Cache-Control: no-cache");
	//here, for debug reason we just return dump of $_POST, you will see result in browser console
	print_r($_POST);
} else {
	/*
	In case of incorrect value or error you should return HTTP status != 200.
	Response body will be shown as error message in editable form.
	*/
	header('HTTP 400 Bad Request', true, 400);
	echo "This field is required!";
}