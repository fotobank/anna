<?php require_once "logic/managers/GlobalVars.php" ?>
<?php
$path="";
require_once "logic\managers\ViewManager.php";
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
    	<? require_once("include/menu.php") ?>
    </div>
</div>
<div id="main">
	<div class="small-col">
    	<? 
		$getOnlyActive=1;
		require_once("include/elenco_cat.php") 
		?>
    </div>
    <div class="big-col">
    	<? require_once("include/elenco_todo.php") ?>
	</div>
</div>



<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$path?>script.js"></script>
<script>
//original hotifx from jquery forum
// HOTFIX: We can't upgrade to jQuery UI 1.8.6 (yet)
// This hotfix makes older versions of jQuery UI drag-and-drop work in IE9
(function($){var a=$.ui.mouse.prototype._mouseMove;$.ui.mouse.prototype._mouseMove=function(b){if($.browser.msie&&document.documentMode>=9){b.button=1};a.apply(this,[b]);}}(jQuery));</script>
<script type="text/javascript">
$(document).ready(function(){
	_setEditCat(1);
});	
</script>
<?
	$viewMng=null;
	
?>
</body>
</html>
