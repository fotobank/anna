<?php
require_once "GlobalVars.php";

$link = @mysql_connect(DB_HOST,USER,PWD);// or die('Unable to establish a DB connection');

mysql_set_charset('utf8');
mysql_select_db(DATABASE,$link) or die("Impossibile selezionare il db $db_database");

?>