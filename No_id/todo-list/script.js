var root_relative_dir="/todo/";

function _getCategories(onlyActive){
	$.get(root_relative_dir+"ajax.php",{'action':'getCategories','only_active':''+onlyActive+'','foo':''+Math.random()+''},function(msg) {
		$('#cat-todoList').html(msg);			
	});
}
function _getTodos(){
	$.get(root_relative_dir+"ajax.php",{'action':'getHTMLTodos','id':''+msg+'','id_cat':'0'},function(msg){
		$(msg).hide().appendTo('.todoList').fadeIn();
	});
}
function setDeleteCategory(idCat){
	$.get(root_relative_dir+'ajax.php',{'action':'setDeleteCat','id':idCat,'foo':''+Math.random()+''},function(msg){
		_getCategories(0);
	})
}
function __setCallEditCat(id,newName,getOnlyActive){
	$.get(root_relative_dir+'ajax.php',{action:'setEditCat','id':''+id+'','nome':''+newName+'','foo':''+Math.random()+''}, function(data) {
		_getCategories(getOnlyActive);
	});
}
function _setEditCat(getOnlyActive){
	$('.categories-list').find('.a-ok').live('click',function(e){
		var txt=$(this).next();
		__setCallEditCat($(this).parent().attr('id').replace('cat-',''),$(txt).val(),getOnlyActive);
	});
	$('#txtEditCat').live('keypress',function(e){
		if (e.keyCode==13){
			__setCallEditCat($(this).parent().attr('id').replace('cat-',''),$(this).val(),getOnlyActive);			
		}
	});	
}
$(document).ready(function(){
	/* 
	filter todos by category
	 */
	$('#cat-todoList a.edit').live('click',function(e){
		var valId=$(this).parent().attr('id').replace('cat-','');
		$.get(root_relative_dir+'ajax.php',{action:'getHTMLTodos','id_cat':valId,'id':'0'}, function(data) {
			$('.todoList').html(data);
			//$('.green-button').css('display','none').nextAll().css('display','none');
		});
	});
	
	$(".todoList").sortable({
		axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',			// Constrained by the window
		update		: function(){		// The function is called after the todos are rearranged
		
			// The toArray method returns an array with the ids of the todos
			var arr = $(".todoList").sortable('toArray');
			
			
			// Striping the todo- prefix of the ids:
			
			arr = $.map(arr,function(val,key){
				return val.replace('todo-','');
			});
			
			// Saving with AJAX
			$.get(root_relative_dir+'ajax.php',{action:'setRearrangeTodos',positions:arr}, function(data) {
			});
		},
		
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});

/*
	set actions for categories list
*/
	$('.categories-list').find('.a-return').live('click',function(e){
		$(this).prevAll().css('display','');
		$(this).css('display','none');
		$(this).next().css('display','none');
		$(this).next().next().css('display','none');
	});
	$('.categories-list').find('.a-edit').live('click',function(e){
		$(this).prevAll().css('display','none');
		$(this).nextAll().css('display','');
		$(this).css('display','none');
	});
	
	
	// A global variable, holding a jQuery object 
	// containing the current todo item:
	
	var currentTODO;
	
	// Configuring the delete confirmation dialog
	$("#dialog-confirm").dialog({
		resizable: false,
		height:150,
		modal: true,
		autoOpen:false,
		title:'Delete Todo',
		buttons: {
			'Confirm': function() {
				
				$.get(root_relative_dir+"ajax.php",{"action":"setDeleteTodo","id":currentTODO.data('id')},function(msg){
					currentTODO.fadeOut('fast');
					_getCategories(1);
				})
				
				$(this).dialog('close');
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}
	});

	// When a double click occurs, just simulate a click on the edit button:
	$('.todo').live('dblclick',function(){
		$(this).find('a.edit').click();
	});
	
	// If any link in the todo is clicked, assign
	// the todo item to the currentTODO variable for later use.

	$('.todo a').live('click',function(e){
									   
		currentTODO = $(this).closest('.todo');
		currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
		e.preventDefault();
	});

	// Listening for a click on a delete button:

	$('.todo a.delete').live('click',function(){
		$("#dialog-confirm").dialog('open');
	});
	
	// Listening for a click on a edit button
	
	$('.todo a.edit').live('click',function(){

		var container = currentTODO.find('#testo');
		var priorita= currentTODO.find('#priorita');
		var lblCat = currentTODO.find('#lblCat');
		var ddlCat = currentTODO.find('#ddlCat');
		
		if(!currentTODO.data('origText'))
		{
			// Saving the current value of the ToDo so we can
			// restore it later if the user discards the changes:
			currentTODO.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		if(!priorita.data('origPriorita'))
		{
			// Saving the current value of the ToDo so we can
			// restore it later if the user discards the changes:
			priorita.data('origPriorita',priorita.text());
		}
		
		if(!lblCat.data('origCat'))
		{
			lblCat.data('origCat',lblCat.text());
		}

		
		
//		$('<input type="text" name="priorita" id="priorita" />').val(priorita.text()).appendTo(priorita.empty());
		//currentTODO.remove('#cat');
		//$('<select><option>1</option></select>').appendTo(container);
		lblCat.css('display','none');
		ddlCat.css('display','block');
		//$('<select id="sel_cat" name="cat"><option>1</option></select>').insertAfter(currentTODO.find('#data_todo'));	
	    $('<textarea rows="3" cols="60" name="testo" id="testo">').val(container.text()).appendTo(container.empty());
		// Appending the save and cancel links:
		container.append(
			'<div class="editTodo">'+
				'<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
			'</div>'
		);
		
		container=container.replace('<br />','\r\n').replace('<br>','\r\n');
		
	});
	
	// The cancel edit link:
	
	$('.todo a.discardChanges').live('click',function(){
		currentTODO.find('.text')
					.text(currentTODO.data('origText'))
					.end()
					.removeData('origText');
		
		currentTODO.find('#ddlCat').css('display','none');
		currentTODO.find('#lblCat').css('display','block');
	});
	
	// The save changes link:
	
	$('.todo a.saveChanges').live('click',function(){
		var text = currentTODO.find("textarea#testo").val();
//		var priorita = currentTODO.find("input[type=text][name=priorita]").val();
		var idCat = currentTODO.find("#ddlCat").val();
		var cat = currentTODO.find("#ddlCat").find('option:selected').text();
		
		$.get("ajax.php",{'action':'setEditTodo','id':currentTODO.data('id'),'id_cat':idCat,'text':text});
		
		currentTODO.removeData('origText')
					.find(".text")
					.text(text);
		
		currentTODO.find('#ddlCat').css('display','none');
		currentTODO.find('#lblCat').css('display','block').text(cat);
		
		_getCategories(1);

		/*
		currentTODO.removeData('origPriorita')
					.find(".priorita_text")
					.text(priorita);
		*/			
		/*
		$.get("ajax.php",{'action':'getAll'},function(msg){
			$('#todoList').html(msg);
		});
		*/			
	});
	
	
	// The Add New ToDo button:
	
	var timestamp=0;
	$('#addButton').click(function(e){

		// Only one todo per 5 seconds is allowed:
		if((new Date()).getTime() - timestamp<5000) return false;
		$.get(root_relative_dir+"ajax.php",{'action':'setNewTodo','text':'New Todo Item. Doubleclick to Edit.','rand':Math.random()},function(msg){
			// Appending the new todo and fading it into view:
			//$(msg).hide().appendTo('.todoList').fadeIn();
			$.get(root_relative_dir+"ajax.php",{'action':'getHTMLTodos','id':''+msg+'','id_cat':'0'},function(msg){
				$(msg).hide().appendTo('.todoList').fadeIn();
				//alert(msg);
			});
		});

		// Updating the timestamp:
		timestamp = (new Date()).getTime();
		
		e.preventDefault();
	});
	
	

}); // Closing $(document).ready()