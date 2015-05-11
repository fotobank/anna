/**
 * Created by Jurii on 27.04.2015.
 */


(function($) {
	/*
	 * ������� �� ���������
	 */
	var defaults = {
		iteration : 5,
		step : 1
	};

	/*
	 * ���������� ���������� �����
	 */
	var options;

	/*
	 * ��������� �������
	 */
	var cookieSettings = {
		expires: 7,
		path : '/',
		name : 'zoomtext'
	};

	/*
	 * ������� ������ � ��������
	 */
	var methods = {

		/*
		 * �������������
		 */
		init:function(params) {

			var type, size;
			options = $.extend({}, defaults, params);

			if ($.cookie != undefined &amp;&amp; $.cookie(cookieSettings.name) != null )
			{
				if ($.cookie(cookieSettings.name) == $(this).css('font-size'))
				{
					type = options.type =  'px';
					size = options.defaultSize = $.cookie(cookieSettings.name).split(options.type).join('');
				}
				else
				{
					type = 'px';
					size = $.cookie(cookieSettings.name).split(type).join('');
					options.type =  'px';
					options.defaultSize = $(this).css('font-size').split(options.type).join('');
				}
			}
			else
			{
				type = options.type =  'px';
				size = options.defaultSize = $(this).css('font-size').split(options.type).join('');
			}

			$(this).css('font-size', size+type);

			return $(this).data(cookieSettings.name) ? this : $(this).data(cookieSettings.name, true);
		},

		/*
		 * ���������� ������
		 */
		inc:function() {

			var maxSize = parseInt(options.defaultSize)+options.iteration;
			var currentSize = parseInt($(this).css('font-size').split(options.type).join(''));

			if (currentSize &lt; maxSize)
			{
				var changedSize = (currentSize+options.step).toString()+options.type;
				$.cookie != undefined ? $.cookie(cookieSettings.name, changedSize, cookieSettings) : '';
				$(this).css('font-size', changedSize);
			}
		},

		/*
		 * ���������� ������
		 */
		dec:function() {

			var minSize = parseInt(options.defaultSize)-options.iteration;
			var currentSize = parseInt($(this).css('font-size').split(options.type).join(''));

			if (currentSize &gt; minSize)
			{
				var changedSize = (currentSize-options.step).toString()+options.type;
				$.cookie != undefined ? $.cookie(cookieSettings.name, changedSize, cookieSettings) : '';
				$(this).css('font-size', changedSize);
			}
		},

		/*
		 * ����� ������
		 */
		reset:function() {
			$.cookie != undefined ? $.cookie(cookieSettings.name, null, cookieSettings) : '';
			$(this).removeAttr('style');
		}
	};

	/*
	 *  ���������� ������ ��� ������ �������
	 */
	$.fn.zoomtext = function(method){
		if (methods[method]) {
			// �������� �� ������������� ����� + �������� ���������� ������
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			// �������� ������ ������������� (init) ����������
			return methods.init.apply(this, arguments);
		} else {
			// ������ �������������, ���� ���-�� ����� �� ���
			return methods.init.apply(this);
		}
	};
})(jQuery);



$(function(){
	// ����� ������� � ��������� ����������� ��������
	$('p#content').zoomtext({
		step:1,
		iteration:2
	});

	// ����������� ����� ��� ���������� ������
	$('a#increase').unbind().bind('click',  function(){
		$('p#content').zoomtext('inc');
	});

	// ����������� ����� ��� ������ ������
	$('#reset').unbind().bind('click', function(){
		$('p#content').zoomtext('reset');
	});

	// ����������� ����� ��� ���������� ������
	$('a#decrease').unbind().bind('click', function(){
		$('p#content').zoomtext('dec');
	});
});