/* Mongolian (UTF-8) initialisation for the jQuery UI date picker plugin. */
/* Written by Gandavaa Dugarsuren (ganaa7@gmail.com). */
jQuery(function($){
	$.datepicker.regional['mn'] = {
		closeText: 'Хаах',
		prevText: '&#x3C;Өмнөх',
		nextText: 'Дараах&#x3E;',
		currentText: 'Өнөөдөр',
		monthNames: ['I сар','II сар','III сар','IV сар','V сар','VI сар',
		'VII сар','VIII сар','IX сар','Х сар','XI сар','XII сар'],
		monthNamesShort: ['1 сар','2 сар','3 сар','4 сар','5 сар','6 сар',
		'7 сар','8 сар','9 сар','10 сар','11 сар','12 сар'],
		dayNames: ['ням','даваа','мягмар','лхагва','пүрэв','баасан','бямба'],
		dayNamesShort: ['ням','дав','мяг','лха','пүр','баа','бям'],
		dayNamesMin: ['Ня','Да','Мя','Лх','Пү','Ба','Бя'],
		weekHeader: '7х',
		dateFormat: 'yy.mm.dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['mn']);
});

$.timepicker.regional['mn'] = {
	timeOnlyTitle: 'Цагыг сонго',
	timeText: 'Хугацаа:',
	hourText: 'Цаг:',
	minuteText: 'Минут:',
	secondText: 'Секунд:',
	millisecText: 'Миллсекунд:',
	timezoneText: 'Цагийн бүс',
	currentText: 'Одоо',
	closeText: 'Болсон',
	timeFormat: 'HH:mm',
	amNames: ['AM', 'A'],
	pmNames: ['PM', 'P'],
	isRTL: false
};
$.timepicker.setDefaults($.timepicker.regional['mn']);
