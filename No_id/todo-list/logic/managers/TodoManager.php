<?php 
include_once "GlobalVars.php";
include_once "BaseManager.php";


class TodoManager extends BaseManager
{

	
	/* The constructor */
	public function __construct(){
		include_once(ROOT."/logic/classes/Todo.php");
		include_once(ROOT."/logic/classes/Cat0.php");
	}
	
	function getNumTodos($idCat){
		$sql = "SELECT COUNT(1) FROM todo t, cat0 c0 WHERE (t.id_cat=c0.id) AND c0.id=".$idCat;
		return $this->executeScalar($sql);
	}
	
	function getTodosInCat($idCat,$topN){
		$sql = "SELECT t.*,c.id id_cat0,c.nome FROM todo t INNER JOIN cat0 c ON t.id_cat=c.id";
		$sql .= " WHERE t.id_cat=".$idCat;
		$sql .= " ORDER BY t.data ".($topN>0 ? " LIMIT ".$topN : "");

		return $this->_getTodo($sql);
	}
	
    function getTodos ($id, $topN){
		$sql = "SELECT t.*,c.id id_cat0,c.nome FROM todo t LEFT OUTER JOIN cat0 c ON t.id_cat=c.id";
		if (isset($id)) {
			if ($id > 0) $sql .= " WHERE t.id=".$id;
		}
		$sql .= " ORDER BY t.data ".($topN>0 ? " LIMIT ".$topN : "");

		return $this->_getTodo($sql);
    }
	
	public function setEdit($id, $idCat, $text){
		$text = $this->getEscaped($text);
//		echo($text);
		if(!$text) throw new Exception("Wrong update text!");
		$sql="UPDATE todo SET testo='".parent::getSafeValue($text)."',id_cat=".$idCat." WHERE id=".$id;
		mysql_query($sql);
		
if (DEBUG) echo($sql);		
		//if(mysql_affected_rows($GLOBALS['link'])!=1) throw new Exception("Couldn't update item!");
		//ToDo::setViewAll();
	}
	
	function setNewTodo($text){
		if(!isset($text)) throw new Exception("Wrong input data!");
		
		$sql="SELECT MAX(posizione)+1 FROM todo";
		$posResult = mysql_query($sql);
		if (!$posResult) die('Invalid query: ' . mysql_error());
		
		if(mysql_num_rows($posResult)) list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		$sql="INSERT INTO todo SET testo='".parent::getSafeValue($text)."',posizione = ".$position;
		mysql_query($sql);
		//echo($sql);
		$newId=mysql_insert_id();
		
		if($newId <= 0) throw new Exception("Error inserting TODO!");
		
		//mysql_free_result();
		
		return $newId;
	}
	
	function setDelete($id){
		$result=parent::setBaseDelete($id,"todo");
	}
	
	function setRearrangeTodos($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the ToDo positions en masse:
		$sql="UPDATE todo SET posizione = CASE id ".join($strVals)." ELSE posizione END";
if (DEBUG) echo($sql);		
		mysql_query($sql);
		
		if(mysql_error()) throw new Exception("Error updating positions!");
		
		/*	
		$viewMng=new ViewManager();
		return $viewMng->getHTMLTodos(0);
		$viewMng=null;
		*/
	}
	
	private function _getTodo($sql){
		$arrTodo=array();
		$result = mysql_query($sql) or die("-->".mysql_error());
		while ($row = mysql_fetch_array($result)) {
			$todo= new Todo();
			$todo->setId($row['id']);
			$todo->setPosizione($row['posizione']);
			$todo->setTesto($row['testo']);
			$todo->setData($row['data']);
			$todo->setPriorita($row['priorita']);
			$todo->setIdCat($row['id_cat']);	
			
			$cat0= new Cat0();
			$cat0->setId($row['id_cat0']);
			$cat0->setNome($row['nome']);
			
			$todo->setCat($cat0);		
			
			array_push($arrTodo,$todo);
		}
		mysql_free_result($result);
		
		return $arrTodo;
	}
	
	
}

?>
