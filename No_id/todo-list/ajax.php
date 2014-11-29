<?php

require "logic/managers/Cat0Manager.php";
require "logic/managers/TodoManager.php";
require "logic/managers/ViewManager.php";

//if (isset($_GET['id'])) {
	$id=0;
	$id = (int)@$_GET['id'];
	$result="1";
	try{
		$todoMng=new TodoManager();
		$viewMng=new ViewManager();	
		$cat0Mng=new Cat0Manager();		
		switch(@$_GET['action'])
		{
			case 'setDeleteTodo':
				$todoMng->setDelete($id);
				break;
			case 'setDeleteCat':
				$cat0Mng->setDelete($id);
				break;
			case 'setRearrangeTodos':
				$result=$todoMng->setRearrangeTodos(@$_GET['positions']);
				break;
			case 'setRearrangeCat':
				$cat0Mng->setRearrangeCat(@$_GET['positions']);
				break;
			case 'setEditTodo':
				$todoMng->setEdit($id,@$_GET['id_cat'],@$_GET['text']);
				break;
			case 'setEditCat':
				$cat0Mng->setEdit($id,@$_GET['nome']);
				break;	
			case 'getHTMLTodos':
				$result=$viewMng->getHTMLTodos(@$_GET['id'],@$_GET['id_cat']);
				break;
			case 'setNewTodo':
				$result=$todoMng->setNewTodo(@$_GET['text']);
				break;
			case 'setNewCat':
				$result=$cat0Mng->setNew(@$_GET['text']);
				break;
			case 'getCategories':
				$result=$viewMng->getHTMLCategories(0,@$_GET['only_active']);
				break;
		}
		
		$todoMng=null;
		$viewMng=null;
		$cat0Mng=null;
	}
	catch(Exception $e){
		echo $e->getMessage();
		die("0");
	}
	
	echo $result;
//}
?>