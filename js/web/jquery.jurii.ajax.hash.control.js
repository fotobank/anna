/**
 *  jQuery quick Each
 *
 *  Example:
 *  a.quickEach(function() {
 *      this; // jQuery object
 *  });
 */
jQuery.fn.quickEach = (function() {
	var jq = jQuery([1]);
	return function(c) {
		var i = -1, el, len = this.length;
		try {
			while (++i < len && (el = jq[0] = this[i]) && c.call(jq, i, el) !== false);
		} catch (e) {
			delete jq[0];
			throw e;
		}
		delete jq[0];
		return this;
	};
}());


$(document).ready(function(){
// создаем массив id вкладок дл€ удобства проверок
	var tabs = [],
			tabList = jQuery(".tabs"),
			ltie9 = jQuery.browser.msie && (jQuery.browser.version <= 9);
	/*
	 ltie9 будет true если используетс€ IE8 или ниже
	 IE9 сюда попал из-за того что при нажатии на Back предыдуща€ вкладка не скрывалась
	 */

	$("ul.list-title a[href^=#tab-]").quickEach(function(){
		tabs.push(this.attr("href"));
	});


// log(tabs);


	// смена таба
	function changeTab(tabId){
		if (!tabId) {
			// если не указана вкладка показываем первую
			window.location.hash = tabId = tabs[0];
		}
		tabList.each(function(){

			var	a_tabId = $("a[href ="+tabId+"]");
			$(this).find(".selected").removeClass("selected");
			a_tabId.parent().addClass("selected"); // активной вкладке добавл€ем класс чтобы придать нужный вид
			$(this).find(".tab-content").hide(); // скрыть вкладки
			$(a_tabId.attr('href')).fadeIn(300); // запустить активную вкладку

		});


     // если информаци€ загружена она просто выводитс€ иначе подгружаетс€

		if(!$(tabId).length) {




			$('ul.list-title').ajax_load('load', {
				'url'    : '/classes/ajaxSite/ajaxLoad.php', // адрес скрипта
				'id_load': '#pageContent', // id дл€ загрузки ответа сервера (контента)
				'type'   : 'GET', // тип вызова
				'header' : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
				'data'   : {'location': window.location.pathname, id: window.location.hash}  // массив посылаемого к серверу запроса
			});

		}

	}

	// проверка хеша
	function checkHash(){

		var	tabId = window.location.hash,
				result = false;

		// дл€ »≈6-8 эмул€ци€ :target
		function setTarget(obj){
			$("#tabs").find(".tab-content").removeClass("target");
			$(tabId).addClass("target");
		}

		// при загрузке страницы провер€ем какую вкладку следует открыть
		$.each(tabs, function(){
			if (tabId == this)
			{
				changeTab(tabId);
				result = true;
				ltie9 ? setTarget(tabId) : false;
				return false;
			}
		});

		if (false == result) changeTab();
	}

	// проверка хеша при загрузке
//	checkHash();

	// отслеживаем изменение хеша
	$(window).bind("hashchange", function() {
//		checkHash();
	});



// ---------------------------------------------------------------------------------------------------------------------
	/*
	 * jQuery hashchange event - v1.3 - 7/21/2010
	 * http://benalman.com/projects/jquery-hashchange-plugin/
	 *
	 * Copyright (c) 2010 "Cowboy" Ben Alman
	 * Dual licensed under the MIT and GPL licenses.
	 * http://benalman.com/about/license/
	 */
	(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);


// Bind an event handler.
	$(window).hashchange( function(e) {

		var tabId = window.location.hash,
		    result = false;

		// при загрузке страницы провер€ем какую вкладку следует открыть
		$.each(tabs, function(){
			if (tabId == this)
			{
				changeTab(tabId);
				result = true;
			}
		});

		if (false == result) changeTab();

	});

// Manually trigger the event handler.
	$(window).trigger( 'hashchange' );






	//-----------------------------------------------------------------------------------



	var currentState = '';

	function clickNavLink() {
		// ”же там?
		var href = $(this).attr('href');
		// »гнорируем переход на уже загруженную страницу
		if (href == currentState) {
			return false;
		}
		if (document.location.hash != href) {
			document.location.hash = href;
		}
		// «агружаем страницу
		var link = this;
		// ѕоказываем индикатор загрузки
		$(this).parent().find('.busy').show();
		$(this).hide();
		var targetURL = "/classes/ajaxSite/ajaxLoad.php"; // адрес скрипта";
		currentState = href;  // сразу помен€ем состо€ние, чтобы избежать повторных кликов

		/*$.ajax({
			context:$('#pageContent'),
			url:targetURL,
			dataType:'html',
			method:'GET',
			complete: function() {
				// ќтмечаем активную ссылку.
				$(link).show();
				updateNavLinks();
			},
			success: function(data) {
				// ќбновл€ем "динамическую" часть страницы.
				$('#pageContent').html(data);
			}
		});*/
		return true;
	}

// ќбновл€ем состо€ние ссылок, чтобы отметить активные/неактивные
	function updateNavLinks() {
		$('a.navlink').each(function(i) {
			var href = $(this).attr('href');
			$(this).parent().find('.busy').hide();
			if (href == currentState) {
				$(this).addClass('disabled');
			} else {
				$(this).removeClass('disabled');
			}
		});
	}

// ‘инал. ¬ешаем событи€ на навигационные ссылки.
	jQuery(document).ready(function() {
//		$('a.navlink').each(function() { $(this).click(clickNavLink); });
	});


});
