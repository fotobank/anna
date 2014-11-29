<?php 
include_once "GlobalVars.php";
include_once "DbConnManager.php";

class BaseManager
{
	/* The constructor */
	public function __construct(){
		
	}
	
	protected function getEscaped($str){
		//if(ini_get('magic_quotes_gpc')) $str = stripslashes($str);
		
		//$str=mysql_real_escape_string(strip_tags($str));
		$str=str_ireplace("\r\n","<br />",$str);
		$str=str_ireplace("\n","<br />",$str);
		$str=str_ireplace("\r","<br />",$str);
//		echo("-->".$str);
		return $str;
	}
	
	protected function getSafeValue($str){
		if (isset($str)){
			$str=str_replace("'","''",$str);
		}
		return $str;
	}
	
	public function getSafeHtml($testo){
		$testo=str_replace('ì','&igrave;',$testo);
		$testo=str_replace('à','&agrave;',$testo);
		$testo=str_replace('è','&egrave;',$testo);
		$testo=str_replace('ù','&ugrave;',$testo);
		$testo=str_replace('ò','&ograve;',$testo);
				
		return $testo;
	}
	
	public function getFormattedDate($strData){
		
		$mesi = array(1=>'gennaio', 'febbraio', 'marzo', 'aprile',
                'maggio', 'giugno', 'luglio', 'agosto',
                'settembre', 'ottobre', 'novembre','dicembre');

		$giorni = array('domenica','lunedì','martedì','mercoledì',
                'giovedì','venerdì','sabato');

		list($sett,$giorno,$mese,$anno) = explode('-',date('w-d-n-Y',strtotime($strData)));
		return $this->getSafeHtml($giorni[$sett].' '.$giorno.' '.$mesi[$mese].' '.$anno);
	}
	
	protected function setBaseDelete($id,$tableName){
		$result=mysql_query("DELETE FROM ".$tableName." WHERE id=".$id);
		return $result;
	}
	
	protected function executeScalar($sql,$def="") {
	    $rs = mysql_query($sql) or die(mysql_error().$sql);
    	if (mysql_num_rows($rs)) {
		    $r = mysql_fetch_row($rs);
		    mysql_free_result($rs);
		    return $r[0];
	    }
    	return $def;
    }
}
?>	