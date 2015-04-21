<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 22:44
 */


function online_widget() {

	$ret = '<div class="onlineWidget">';
	$ret .= '<div class="panel">';
	$ret .= '<img class="preloader" src="/inc/who_is_online/img/preloader.gif" alt="Loading.." width="22" height="22" /></div>';
	$ret .= '<div class="count"></div><div class="label"><span>online</span></div><div class="arrow"></div></div>';

return $ret;

}