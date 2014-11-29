<?php


require_once (__DIR__ .'/../../inc/config.php');
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
			ajaxSite_EditBody::delete($id);
			break;
			
		case 'rearrange':
			ajaxSite_EditBody::rearrange($_GET['positions']);
			break;
			
		case 'edit':
			ajaxSite_EditBody::edit($id,$_GET['text']);
			break;
			
		case 'new':
			ajaxSite_EditBody::createNew($_GET['text']);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

   echo "1";