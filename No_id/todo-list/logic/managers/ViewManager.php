<?php 
include_once "GlobalVars.php";
include_once "DbConnManager.php";
		
class ViewManager
{
	
	/* The constructor */
	public function __construct(){
		include_once "Cat0Manager.php";
		include_once "TodoManager.php";
		include_once(ROOT."/logic/classes/Cat0.php");
	}
	
    function getHTMLTodos ($id,$idCat){

		$cat0Mng=new Cat0Manager();
		$arrCategories=$cat0Mng->getCategories(0,false);
		
		$todoMng=new TodoManager();
		$arrTodo=($idCat <=0 ? $todoMng->getTodos($id,0) :$todoMng->getTodosInCat($idCat,0));
		$selected="";
		
		$html="";
		$catScritta=false;
		foreach($arrTodo as $todo) {
			
			if ($idCat===$todo->getCat()->getId() && !$catScritta){
				$html.="<h2>".$todo->getCat()->getNome()."</h2>";
				$catScritta=true;	
			}
			
			$html.="<li id=\"todo-".$todo->getId()."\" class=\"todo\">";
			$html.="<span class=\"small\" style=\"display:block;\" id=\"data_todo\">".$todoMng->getFormattedDate($todo->getData())."</span>";
			$html.="<span class=\"small\" style=\"display:block\" id=\"lblCat\">".$todo->getCat()->getNome()."</span>";
			$html.="<select name=\"cat\" id=\"ddlCat\" class=\"todo-select\" style=\"display:none\">";
			foreach($arrCategories as $cat){
				$html.="<option value=\"".$cat->getId()."\" ".($todo->getCat()->getId()==$cat->getId() ? " selected=\"true\"" : "").">".$cat->getNome()."</option>";
			}
			$html.="</select>";
			$html.="<div class=\"text\" id=\"testo\">".$todoMng->getSafeHtml(nl2br($todo->getTesto()))."</div>";
			$html.="<div class=\"actions\">";
			$html.="<a href=\"#\" class=\"edit\">Edit</a>";
			$html.="<a href=\"#\" class=\"delete\">Delete</a>";
			$html.="</div>";
            $html.="</li>";
		}
		return $html;
    }
	
	function getHTMLCategories($id,$getOnlyActive){
		$cat0Mng=new Cat0Manager();
		$arrCategories=$cat0Mng->getCategories($id,$getOnlyActive);
		
		$html="";
		foreach($arrCategories as $cat) {
			$num=$cat0Mng->getNumPostInCat($cat->getId());
			$html.="<li id=\"cat-".$cat->getId()."\" class=\"categories-list\">";
			if ((bool)$getOnlyActive){
				$html.="<a href=\"#\" class=\"edit\">".$cat->getNome()." (".$cat0Mng->getNumPostInCat($cat->getId()).")</a>";	
			}else{
				$html.="<span>".$cat->getNome()." (".$num.")</span>";
			}
			
			if ($num == 0){
				//le categorie con prodotti non possono essere cancellate
				//onclick=\"this.style.display='none';this.previousSibling.firstChild.style.display='';this.previousSibling.previousSibling.style.display='none';this.nextSibling.style.display='block'\"
				$html.="<a class=\"a-delete\" id=\"delcat-".$cat->getId()."\"><img class=\"delete\" src=\"".ROOT_RELATIVE_DIR."/img/delete.png\" /></a>";
			}
			$html.="<a class=\"a-edit\"><img class=\"edit\" src=\"".ROOT_RELATIVE_DIR."/img/edit.png\" /></a>";
			$html.="<a class=\"a-return\" style=\"display:none\"><img class=\"return\" src=\"".ROOT_RELATIVE_DIR."/img/return.png\" /></a>";
			$html.="<a class=\"a-ok\" style=\"display:none;\"><img class=\"return\" src=\"".ROOT_RELATIVE_DIR."/img/ok.png\" style=\"margin-right:3px\" /></a>";
			$html.="<input type=\"text\" name=\"txtCat\" id=\"txtEditCat\" value=\"".$cat->getNome()."\" style=\"display:none;position:absolute;\" />";
			$html.="</li>";
		}
		
		return $html;
	}
	
	function getBoxSimpleTodo($topN)
	{
		$todoMng=new TodoManager();
		$catMng=new Cat0Manager();
		$finalArray=array();		
		$arrCat = $catMng->getCategories(0,true);
		foreach($arrCat as $cat){
			$arrTodo=$todoMng->getTodosInCat($cat->getId(),$topN);		
			
			$finalArray=array_merge($arrTodo,$finalArray);
		}

		
		$html="";
		$titCat="";
		$showFooter=false;
		$numTodos=0;
		setlocale(LC_TIME, 'ita', 'it_IT');

		for ($i = 0; $i < count($finalArray); $i++) {
    		$todo = $finalArray[$i];
			
			if ($titCat != $todo->getCat()->getNome()){
				$titCat = $todo->getCat()->getNome();
				
				$html .= "<div class=\"box_last_todos\">";
				$html .= "<h2>".$titCat."</h2>";
				$html .= "<ul>";
			}
			
			$html .= "<li>";
			$html .= "<span class=\"data\">".$todoMng->getFormattedDate($todo->getData())."</span>";			
			$html .= "<span class=\"testo\">".$todoMng->getSafeHtml($todo->getTesto())."</span>";
			$html .= "</li>";
			
			$tmpI=$i;				
			/*
			mi sposto avanti per vedere il prossimo indice che categoria è
			se cambia la categoria o sono all'ultimo indice chiudo l'id
			*/
			$tmpI += 1;
			if ($tmpI < count($finalArray)){
				$nextCat = $finalArray[$tmpI]->getCat()->getNome();
			}
			
			if ($nextCat != $todo->getCat()->getNome() || $tmpI==count($finalArray)){
				$html .= "</ul>";
				$html .= "<div class=\"footer\">";
					$numTodos=$todoMng->getNumTodos($todo->getCat()->getId());
					$html .= "<span>".($topN > $numTodos ? $numTodos : $topN)." di ".$numTodos."</span>";
					$html .= "<a href=\"list.php?idcat=".$todo->getCat()->getId()."\">vedi tutti</a>";
				$html .= "</div>";
				$html .= "</div>";
			}

		}

		$todoMng=null;
		$arrTodo=null;
		
		return $html;
		
		
	}
	
}

?>
