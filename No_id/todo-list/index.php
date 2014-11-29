<?php include_once(realpath("logic/managers/GlobalVars.php")) ?>
<?php
$path="";
require_once(realpath("logic/managers/ViewManager.php"));
$viewMng=new ViewManager();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="<?=$path?>favicon.ico" />
<title>Todo</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="<?=$path?>styles.css" />
</head>

<body>
<div class="header">
	<div class="logo">
		<a href="<?=ROOT_RELATIVE_DIR?>"><h1 class="head">Todo list</h1></a>
    </div>
    <div class="nav">
    	<? include_once(realpath("include/menu.php")) ?>
    </div>
    <div class="container">
        <?
        	echo($viewMng->getBoxSimpleTodo(3));
		?>
    </div>
</div>
<div id="main">
</div>
<div id="debug"></div>
<?
	$viewMng=null;
?>
</body>
</html>
