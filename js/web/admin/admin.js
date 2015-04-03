/**
 * Created by Jurii on 16.08.14.
 */

$(function(){
	var topPos = $(".floating-admin").offset().top; //topPos - ��� �������� �� ����� ����� �� ���� ��������
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

	$('#edit-content').click(function() {
		if ($(this).prop("checked")) {
			$(this).removeClass('selected');
			$('.editable').editable('toggleDisabled');
		} else {
			$(this).addClass('selected');
			$('.editable').editable('toggleDisabled');
		}
	});



	$('.edit-txt').editable({
		url: '/inc/jeditable/save.php',
		type:'textarea',
		style  : "inherit",
		title: '�������� �������',
		placeholder: '������� �����',
		id   : 'elementid',
		name : 'newvalue',
		rows: 15
	});



	//---------------------------------------



	/*$(".edit-txt").editable("/inc/jeditable/save.php", {
		indicator : "<img src='/images/indicator.gif'>",
		type   : 'textarea',
		submitdata: { _method: "put" },
		select : true,
		submit : 'OK',
		cancel : 'cancel',
		cssclass : "editable"
	});*/

















	//---------------------------------------------------------







/*$(function() {

 $( "ul.nav" ).on( "click", "li a",  function(e) {
 e.stopPropagation();
 log( "����" );
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
