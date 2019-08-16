;(function($){
/**
 * jqGrid English Translation
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.jgrid = $.jgrid || {};
$.extend($.jgrid,{
	defaults : {
		recordtext: "Харагдац {0} - {1} Нийт:{2}",
		emptyrecords: "No records to view",
		loadtext: "Уншиж байна...",
		pgtext : "Хуудас: {0} бүгд:{1}"
	},
	search : {
		caption: "Хайлт...",
		Find: "Хайх",
		Reset: "Дахин дуудах",
		odata : ['тэнцүү', 'тэнцүү биш', 'бага', 'бага ба тэнцүү','их','их ба тэнцүү', 'эхэнд байх','эхэнд байхгүй','дотор байх','дотор байхгүй','төгсгөлд байх','төгсгөлд байхгүй','агуулах','агуулахгүй', 'хоосон', 'хоосон биш'],
		groupOps: [	{ op: "AND", text: "all" },	{ op: "OR",  text: "any" }	],
		matchText: " match",
		rulesText: " rules"
	},
	edit : {
		addCaption: "Бичлэг нэмэх",
		editCaption: "Бичлэг засах",
		bSubmit: "Хадгалах",
		bCancel: "Цуцлах",
		bClose: "Хаах",
		saveData: "Утгуудыг засварлалаа! Хадгалах уу?",
		bYes : "Тийм",
		bNo : "Үгүй",
		bExit : "Цуцлах",
		msg: {
			required:"Field is required",
			number:"Тохирсон утгыг оруулна уу",
			minValue:"value must be greater than or equal to ",
			maxValue:"value must be less than or equal to",
			email: "Энэ имэйл хаяг буруу байна, имэйл хаягаа зөв бичнэ үү!",
			integer: "Тохирох бүхэл утгийг оруулна уу",
			date: "Тохирох огноог оруулна уу",
			url: "URL хаяг биш байна, ('http://' or 'https://') байх шаардлагатай",
			nodefined : " утга өгөөгүй байна!",
			novalue : " буцах утга шаардлагатай!",
			customarray : " Фүнкцийн буцах утга массиф байх ёстой!",
			customfcheck : " Фүнкийг шалгахад байх ёстой!" // Custom function should be present in case of custom checking			
		}
	},
	view : {
		caption: "View Record",
		bClose: "Close"
	},
	del : {
		caption: "Устгах",
		msg: "Delete selected record(s)?",
		bSubmit: "Delete",
		bCancel: "Cancel"
	},
	nav : {
		edittext: "",
		edittitle: "Сонгосон бичлэгийг засах",
		addtext:"",
		addtitle: "Шинэ бичлэг нэмэх",
		deltext: "",
		deltitle: "Сонгосон мөрийг устгах",
		searchtext: "",
		searchtitle: "Бичлэгээс хайх",
		refreshtext: "",
		refreshtitle: "Дахин ачаалах",
		alertcap: "Анхаар",
		alerttext: "Нэг бичлэгийг сонгоно уу",
		viewtext: "",
		viewtitle: "Сонгосон бичлэгийг харах"
	},
	col : {
		caption: "Select columns",
		bSubmit: "Ok",
		bCancel: "Cancel"
	},
	errors : {
		errcap : "Алдаа",
		nourl : "Url-г тохируулаагүй байна",
		norecords: "Бичлгэ байхгүй байна", //No records to process
		model : " colNames <> colModel урт тохирохгүй байна!"
	},
	formatter : {
		integer : {thousandsSeparator: ",", defaultValue: '0'},
		number : {decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, defaultValue: '0.00'},
		currency : {decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "", suffix:"", defaultValue: '0.00'},
		date : {
			dayNames:   [
				"Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat",
				"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
			],
			monthNames: [
				"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
				"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
			],
			AmPm : ["am","pm","AM","PM"],
			S: function (j) {return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th';},
			srcformat: 'Y-m-d',
			newformat: 'n/j/Y',
			masks : {
				// see http://php.net/manual/en/function.date.php for PHP format used in jqGrid
				// and see http://docs.jquery.com/UI/Datepicker/formatDate
				// and https://github.com/jquery/globalize#dates for alternative formats used frequently
				// one can find on https://github.com/jquery/globalize/tree/master/lib/cultures many
				// information about date, time, numbers and currency formats used in different countries
				// one should just convert the information in PHP format
				ISO8601Long:"Y-m-d H:i:s",
				ISO8601Short:"Y-m-d",
				// short date:
				//    n - Numeric representation of a month, without leading zeros
				//    j - Day of the month without leading zeros
				//    Y - A full numeric representation of a year, 4 digits
				// example: 3/1/2012 which means 1 March 2012
				ShortDate: "n/j/Y", // in jQuery UI Datepicker: "M/d/yyyy"
				// long date:
				//    l - A full textual representation of the day of the week
				//    F - A full textual representation of a month
				//    d - Day of the month, 2 digits with leading zeros
				//    Y - A full numeric representation of a year, 4 digits
				LongDate: "l, F d, Y", // in jQuery UI Datepicker: "dddd, MMMM dd, yyyy"
				// long date with long time:
				//    l - A full textual representation of the day of the week
				//    F - A full textual representation of a month
				//    d - Day of the month, 2 digits with leading zeros
				//    Y - A full numeric representation of a year, 4 digits
				//    g - 12-hour format of an hour without leading zeros
				//    i - Minutes with leading zeros
				//    s - Seconds, with leading zeros
				//    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
				FullDateTime: "l, F d, Y g:i:s A", // in jQuery UI Datepicker: "dddd, MMMM dd, yyyy h:mm:ss tt"
				// month day:
				//    F - A full textual representation of a month
				//    d - Day of the month, 2 digits with leading zeros
				MonthDay: "F d", // in jQuery UI Datepicker: "MMMM dd"
				// short time (without seconds)
				//    g - 12-hour format of an hour without leading zeros
				//    i - Minutes with leading zeros
				//    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
				ShortTime: "g:i A", // in jQuery UI Datepicker: "h:mm tt"
				// long time (with seconds)
				//    g - 12-hour format of an hour without leading zeros
				//    i - Minutes with leading zeros
				//    s - Seconds, with leading zeros
				//    A - Uppercase Ante meridiem and Post meridiem (AM or PM)
				LongTime: "g:i:s A", // in jQuery UI Datepicker: "h:mm:ss tt"
				SortableDateTime: "Y-m-d\\TH:i:s",
				UniversalSortableDateTime: "Y-m-d H:i:sO",
				// month with year
				//    Y - A full numeric representation of a year, 4 digits
				//    F - A full textual representation of a month
				YearMonth: "F, Y" // in jQuery UI Datepicker: "MMMM, yyyy"
			},
			reformatAfterEdit : false
		},
		baseLinkUrl: '',
		showAction: '',
		target: '',
		checkbox : {disabled:true},
		idName : 'id'
	}
});
})(jQuery);
