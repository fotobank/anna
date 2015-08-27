<?php


require(__DIR__ . '/../../src/config/config.php');;
header( 'Content-type: text/html; charset=windows-1251' );



if(isset($_GET['id'])) {
	$id = (int)preg_replace('/\D/','', $_GET['id']);
} else {
	$id = false;
}

try{

	switch($_GET['action'])
	{
		case 'delete':
			EditTitle::delete($id);
			break;
			
		case 'rearrange':
			EditTitle::rearrange($_GET['positions']);
			break;
			
		case 'edit':
			EditTitle::edit($id,$_GET['text']);
			break;
			
		case 'new':
			EditTitle::createNew($_GET['text']);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

   echo "1";