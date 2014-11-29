<?php
session_start();
require_once 'class_cache.php';
$cache = new cache(30,'./cache/class_protect_picture');
if ($cache->inCache(session_id()))
{
	$val = explode('||',$cache->readCache());
	@readfile($val[$_GET['id']]);
}
unset($cache);
?>