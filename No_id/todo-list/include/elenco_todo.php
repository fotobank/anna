<span id="feedback">rearrenging, please wait</span>
<ul class="todoList" id="todoList">
    <?php
	$idCat=0;
	if (isset($_REQUEST['idcat'])) $idCat=$_REQUEST['idcat'];
    echo($viewMng->getHTMLTodos(0,$idCat));            
    
    ?>
</ul>
<a id="addButton" class="green-button" href="#">Add a Todo</a>
<div id="dialog-confirm" title="Delete Todo item?">Are you sure you want to delete this Todo?</div>
<p class="note">You can add only one in 5 seconds.</p>