/**
 * Created by Jurii on 16.08.14.
 */

// ��������� �����
$(function(){
	var topPos = $(".floating-admin").offset().top; //topPos - ��� �������� �� ����� ����� �� ���� ��������
	$(window).scroll(function() {
		var top = $(document).scrollTop();
		if ((top+15) > topPos) $(".floating-admin").addClass("fixed-admin");
		else $(".floating-admin").removeClass("fixed-admin");
	});


	// ������������� tooltip ���������
	/*$(this).on('hover', 'a, input, ul li div.text', $(this).tooltip({
			      track: true,
				    delay: 100,
				    showBody: "::",
		//disabled: true,
				    opacity: 0.85
		  }));*/

	//var disabled = $( "a, input, ul li div.text" ).tooltip( "option", "disabled" );

// Setter
//	$( "a, input, ul li div.text" ).tooltip( "option", "disabled", true );



//defaults
$.fn.editable.defaults.url = '/inc/jeditable/save.php';


	//------------- �������������� �������� (������)


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

// ��������� ���������� ������� �� ��������� ���� "li a"
/*$(function() {

 $( "ul.nav" ).on( "click", "li a",  function(e) {
	 e.preventDefault();
 log( "����" );
 return false;
 });
 });*/


// �������� ����������
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



// ������������� ��������� ��������
	$('.edit-txt').editable({
		url: '/inc/jeditable/save.php',
		showbuttons: 'bottom',
		send: 'always',
		type:'textarea',
		title: '�������� �������',
		placeholder: '������� �����',
		id   : 'elementid',
		name : 'newvalue',
		pk: {id: 'elementid', lang: 'en'},

		rows: 15
	});

	//$.fn.editable.defaults.mode = 'inline'; // popup ��� inline

	// ������������� ������ ���������
	$("#enable-edit").click(function(){
		$(this).editOnOff('newsEditOn');
	}).editOnOff();

});

// ������ on/off �������� � �������
(function ($) {

	var methods = {
		// ������������� ���������� ��������� ���������
		init: function () {

			$('.editable').editable('disable');
			return this.removeClass('button_img_on').addClass('button_img_off');
		},
		newsEditOn: function () {

			if ($(this).hasClass("button_img_on")) {
				$('.editable').editable('disable');
				return this.removeClass('button_img_on').addClass('button_img_off');

			} else {
				$('.editable').editable('enable');
				return this.removeClass('button_img_off').addClass('button_img_on');
			}
			}};

		$.fn.editOnOff = function (method) {

		if (methods[method]) {
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('����� "' + method + '" �� ������ � ������� jQuery.editOnOff');
			return false;
		}
	};
})(jQuery);