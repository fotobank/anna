/**
 * Created by Jurii on 16.08.14.
 */

$(function(){
	var topPos = $(".floating-admin").offset().top; //topPos - это значение от верха блока до окна браузера
	$(window).scroll(function() {
		var top = $(document).scrollTop();
		if ((top+15) > topPos) $(".floating-admin").addClass("fixed-admin");
		else $(".floating-admin").removeClass("fixed-admin");
	});





	$(this).on('hover', 'a, input, ul li div.text', $(this).tooltip({
			    track: true,
				    delay: 100,
				    showBody: "::",
				    opacity: 0.85
		  }));



//defaults
$.fn.editable.defaults.url = '/inc/jeditable/save.php';

//enable / disable
$('#enable').click(function() {
	$(this).addClass('selected');
	$('.editable').editable('toggleDisabled');
});


$(function(){
	$('.edit-txt').editable({
		url: '/inc/jeditable/save.php',
		type:'textarea',
		title: 'Enter comments',
		placeholder: 'Введите текст',
		rows: 15
	});
});


/*$(function() {

 $( "ul.nav" ).on( "click", "li a",  function(e) {
 e.stopPropagation();
 log( "Тест" );
 return false;
 });

 });*/


//editables
$('#username').editable({
	url: '/post',
	type: 'text',
	pk: 1,
	name: 'username',
	title: 'Enter username'
});


$('#firstname').editable({
	validate: function(value) {
		if($.trim(value) == '') return 'This field is required';
	}
});



});
