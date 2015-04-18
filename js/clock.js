/**
 * Created by Jurii on 16.04.2015.
 */


$(document).ready(function () {

	var monthNames = [ "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря" ]; 
	var dayNames= ["Воскресенье - ","Понедельник - ","Вторник - ","Среда - ","Четверг - ","Пятница - ","Суббота - "];

	var newDate = new Date();

	newDate.setDate(newDate.getDate());

	$('#Date').html(dayNames[newDate.getDay()] + "  " + newDate.getDate() + " " + monthNames[newDate.getMonth()] + " " + newDate.getFullYear());

	setInterval(function () {

		var seconds = new Date().getSeconds();

		$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	}, 1000);

	setInterval(function () {

		var minutes = new Date().getMinutes();

		$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
	}, 1000);

	setInterval(function () {

		var hours = new Date().getHours();

		$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
	}, 1000);

});
