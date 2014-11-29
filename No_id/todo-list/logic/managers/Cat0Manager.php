<?php 
include_once "GlobalVars.php";
include_once "BaseManager.php";


class Cat0Manager extends BaseManager
{
	/* The constructor */
	public function __construct(){
		include_once(ROOT."/logic/classes/Cat0.php");
	}
	
    function getCategories ($id,$getOnlyActive){
		$sql ="";
		if ((bool)$getOnlyActive){
			$sql = "SELECT COUNT(1),c.id,c.nome,c.posizione FROM cat0 c,todo t WHERE c.id=t.id_cat";
			if (isset($id)) {
				if ($id > 0) $sql .= " AND c.id=".$id;
			}
			$sql.=" GROUP BY c.id,c.nome,c.posizione ORDER BY COUNT(1) DESC";
		}else{
			$sql="SELECT c.* FROM cat0 c ".($id > 0 ? " WHERE c.id=".$id : " ORDER BY c.nome");
		}
		
		$arr=array();
		$result = mysql_query($sql) or die("-->".mysql_error());
		while ($row = mysql_fetch_array($result)) {
			$cat= new Cat0();
			$cat->setId($row['id']);
			$cat->setNome($row['nome']);
			$cat->setPosizione($row['posizione']);
			
			array_push($arr,$cat);
		}
		mysql_free_result($result);
		
		return $arr;
    }
	
	function getNumPostInCat($id){
		$result=mysql_query("SELECT COUNT(1) FROM todo t,cat0 c0 WHERE t.id_cat=c0.id AND c0.id=".$id);
		$row=mysql_fetch_array($result);
		mysql_free_result($result);
		return $row[0];
	}
	
	function setRearrangeCat($key_value){
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the ToDo positions en masse:
		$sql="	UPDATE cat0 SET posizione = CASE id
						".join($strVals)."
						ELSE posizione
						END";
if (DEBUG) echo($sql);						
		mysql_query($sql);
		
		if(mysql_error()) throw new Exception("Error updating positions!");
		
		/*
		$viewMng=new ViewManager();
		return $viewMng->getHTMLCategories(0,true);
		$viewMng=null;
		*/
	}
	
	function setEdit($id, $nome){
		$nome = $this->getEscaped($nome);
//		echo($text);
		if(!$nome) throw new Exception("Wrong update text!");
		$sql="UPDATE cat0 SET nome='".parent::getSafeValue($nome)."' WHERE id=".$id;
if (DEBUG) echo($sql);
		mysql_query($sql);
		//if(mysql_affected_rows($GLOBALS['link'])!=1) throw new Exception("Couldn't update item!");
		//ToDo::setViewAll();
	}
	
	function setDelete($id){
		$result=parent::setBaseDelete($id,"cat0");
	}
	
	function setNew($text){
		if(!isset($text)) throw new Exception("Wrong input data!");
		
		$sql="INSERT INTO cat0 SET nome='".nl2br(parent::getSafeValue($text))."'";
if (DEBUG) echo($sql);		
		mysql_query($sql);
		//echo($sql);
		$newId=mysql_insert_id();
		
		if($newId <= 0) throw new Exception("Error inserting TODO!");
		
		//mysql_free_result();
		
		return $newId;
	}
}

?>
