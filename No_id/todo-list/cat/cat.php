<?php include_once(realpath("../logic/managers/GlobalVars.php")) ?>
<?php
$path="../";
require_once(realpath("../logic/managers/ViewManager.php"));
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
		<a href="../index.php"><h1 class="head">Todo list</h1></a>
    </div>
    <div class="nav">
    	<? include_once(realpath("../include/menu.php")) ?>
    </div>
</div>
<div id="main">
	<div class="small-col">
    	<? 
		$getOnlyActive=0;
		include_once(realpath("../include/elenco_cat.php")) 
		?>
    </div>
    <div class="big-col">
    	<h2>Add new category</h2>
        <div class="ins-cat">
        	<span style="color:#0196E3;display:none" id="lblCatAdded">Category added</span>
	        <input type="text" name="txtCat0" id="txtCat0" />
            <a id="btnAddCat" style="margin-top:20px" class="green-button" href="#">Add new</a>
        </div>
	</div>
</div>
<div id="debug"></div>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$path?>script.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	_setEditCat(0);
	
	$('#btnAddCat').click(function(e){
		if ($('#txtCat0').val()=='') {
			$('#txtCat0').css('border','1px solid #f00');
		} else {
			$.get("../ajax.php",{'action':'setNewCat','text':''+$('#txtCat0').val()+''},function(msg){
				$.get("../ajax.php",{'action':'getCategories','only_active':'0','foo':''+Math.random()+''},function(msg) {
					$('#cat-todoList').empty().html(msg);			
				});
			});
		}
		e.preventDefault();
	});
	
	$('.categories-list').find('.a-delete').live('click',function(e){
		$.get(root_relative_dir+'/ajax.php',{action:'setDeleteCat','id':$(this).attr('id').replace('delcat-','')}, function(data) {
			_getCategories(0);
		});
	});
	
	
});	
</script>
<?
	$viewMng=null;
?>
</body>
</html>
