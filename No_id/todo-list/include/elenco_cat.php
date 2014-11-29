<div class="titolo_cat">
	<h2>Categories</h2>
    <? if ($getOnlyActive==1) {?>
    <a href="javascript:location.reload()" title="refresh"><img src="<?=ROOT_RELATIVE_DIR?>/img/refresh.png" alt="refresh" /></a>
    <? } ?>
</div>
<ul id="cat-todoList">
<?php
    echo($viewMng->getHTMLCategories(0,($getOnlyActive == 1 ? true : false)));
?>
</ul>
