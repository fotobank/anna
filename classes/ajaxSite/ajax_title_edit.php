<?php


require (__DIR__ .'/../../system/config/config.php');;
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
			ajaxSite_EditTitle::delete($id);
			break;
			
		case 'rearrange':
			ajaxSite_EditTitle::rearrange($_GET['positions']);
			break;
			
		case 'edit':
			ajaxSite_EditTitle::edit($id,$_GET['text']);
			break;
			
		case 'new':
			ajaxSite_EditTitle::createNew($_GET['text']);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

   echo "1";