/*
 * events javascript @Oct 22 2013
 */

$(document).ready(function(){

    $("#doneby_id").chosen({no_results_text: "Oops, nothing found!"}); 

    $('.chosen-container ').css("width","250px");

// filter herev
    $("#location_id", '#event_form').change(function(){

       //filter(form_name, target_id, target);
       filter('#event_form', $(this).val(), 'equipment');

    });


   form= $('#event_form');

   rowdone =$("#trdone");

   role=$('#role').val();

   //createdbyId=$('#createdby_id');
   eventtypeId=$('#eventtype_id');
   equipmentId=$('#equipment_id');
   locationId=$('#location_id');
   event=$('input[name="event"]');

   done=$('#done');

   form.dialog({
      width: 640, // set the width of the dialog to 400px
      autoOpen: false, // don't want it to open automatically
      resizable: false, // set it to not resizable
      modal: true, // use a modal overlay background
      close: function(){	// onClose

         $('p.feedback', form).html('').hide();

         // clear & hide the feedback msg inside the form

         $('input[type="text"], input[type="hidden"], textarea, select', form).val('');

         // $("#doneby_id").empty('destroy');
         

         $('#eventId').text('');
         // clear the input values on form
      }
    });

   $('#startdate').datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });

   $('#enddate').datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });

   var calendar=$('#calendar').fullCalendar({
      columnFormat: {
          month: 'dddd',
          week: 'ddd-d',
          day: ''
      },
      axisFormat: 'H:mm',
      timeFormat: {
          '': 'H:mm',
      agenda: 'H:mm{ - H:mm}'
      },
      /*firstDay:1, */
      buttonText: {
              today: 'Өнөөдөр',
              month: 'Сар',
              day: 'Өдөр',
              week: '7 хоног'
      },
      monthNames: ['I сар','II сар','III сар','IV сар','V сар','VI сар','VII сар','VIII сар','IX сар','X сар','XI сар','XII сар'],
      monthNamesShort: ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'],
      dayNames: ['Ням','Даваа','Мягмар','Лхагва','Пүрэв','Баасан','Бямба'],
      dayNamesShort: ['Ня','Да','Мя','Лх','Пү','Ба','Бя'],
      header: {
         left: 'prev,next today',
         center: 'title',
         right: 'month,agendaWeek,agendaDay'
      },
      events: {
         url:base_url+"/maintenance/get_event",
         cache: true
      },
      eventColor: '#FFD900',
      eventTextColor: '#000000',
      eventborderColor:'red',
      selectable: true ,
      selectHelper: true ,
      editable: true,
      eventDrop: function(event, delta) {    //хаагдсан байвал мөн өөрийн үүсгэсэн event байвал зөөнө
         var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
         var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
         moveEvent(start, end, event.id, 'move');


      },
      eventResize: function(event){
        start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
        end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
        moveEvent(start, end, event.id, 'resize');
      },
      select: function (start, end, allday){
          createEvent();
          $("#createdbyId").text('');
          $('#startdate').val($.fullCalendar.formatDate(start,'yyyy-MM-dd HH:mm'));
          $('#enddate').val($.fullCalendar.formatDate(end, 'yyyy-MM-dd HH:mm'));
          calendar.fullCalendar ( 'unselect' );
          $('#calendar').fullCalendar( 'refetchEvents' );
       },
      eventClick: function(calEvent, jsEvent, view) {

          // post-с утгийг авах id-гаар авч хэрэв active_id утгагүй байвал
          $.post( base_url+'/maintenance/get_event_dtl', {id:calEvent.id}, function(data){
             //createdbyId.val(data.createdbyId)
             $("#eventId").text(calEvent.id);

             $("#event").val(data.title);

             $("#is_interrupt").val(data.is_interrupt).change();

             var itas = data.itas;

             if(data.isdone){

                $.each(itas, function( key, value ) {  

                   $("#doneby_id option[value="+key+"]").attr("selected", "selected");
                
                });
             }

             if(data.createdby!==null) $("#createdbyId").text(data.createdby);
           
             else $("#createdbyId").text('');
            
             eventtypeId.val(data.eventtype_id);

             //equipment-diig haruulna
             gen_option(data.equipments, '#event_form');

             equipmentId.val(data.equipment_id);
            // $("#equipment_id option[value="+data.equipment_id+"]", '#event_form').attr("selected", "selected");
             locationId.val(data.location_id);

             if(data.done!==null&&data.done!==''){

                done.val(data.done);
                
                // $("#doneby_id").val(data.doneby_id);

                $("#doneby_id option[value="+data.doneby_id+"]").attr("selected", "selected");
                
                $('#rowDoneby').show();

                $('#trdone').show();

             }
             else{
                
                // $('#rowDoneby').hide();
                
                $('#trdone').hide();

              } 
             $("#startdate").val(dateFormat(data.start, "yyyy-mm-dd HH:MM")); //sstt
             
             $("#enddate").val(dateFormat(data.end, "yyyy-mm-dd HH:MM"));
            
             $('#doneby_id').trigger('chosen:updated');
             
             //isdone=data.isdone;
             clickEvent(calEvent.id, data.active, data.isdone);
          });

          

         // console.log(calEvent.end);

          $(this).css('border-color', 'red');  //change the border color just for fun
        }
    }); //end calendar

}); // end jquery
 //Хэн хэзээ ч ямарч бүртгэл үүсгэж болно!!
 function createEvent(){

   rowdone.hide();

   $('#rowDoneby').hide();

   form.dialog('option', 'title', 'Шинэ бүртгэл үүсгэх');

    if(role!=='USER'){
       form.dialog({
          buttons: [
          {
             text: "Хадгалах",
             click: function() {
                var data = {};
                var inputs = $('input[type="text"], input[type="hidden"], select, textarea', form);
                inputs.each(function(){
                    var el = $(this);
                    data[el.attr('name')] = el.val();
                });
                $.ajax({
                  type:     'POST',
                  url:      base_url+'/maintenance/add',
                  data:     data,
                  dataType: 'json',
                  success: function (json) {
                     if(json.status =='success'){
                        form.dialog("close");
                        $('#calendar').fullCalendar('refetchEvents');
                        showMessage(json.message, 'success');
                     }else{
                         $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                     }
                  }
                 });

             }// end click
          },
          {   text: "Болих",
              click: function() {

                 $('#doneby_id').trigger('chosen:updated');
                 $( this ).dialog( "close" );
              }
          }
          ]
       });
    }else
      form.dialog({
          buttons: [
          {   text: "Болих",

              click: function() {

                 $('#doneby_id').trigger('chosen:updated');
                 $( this ).dialog( "close" );
              }
          }
          ]
       });
    form.dialog('open');
}

function clickEvent(eventId, active, done){
   switch(role){
      case "ENG":  // энд Engineeriin фүнкц
          if(active==true&&done==false){ //гүйцэтгэлийг дуудна

              rowdone.show();
             
              $('#rowDoneby').show();
             
              allEnabled();

              form.dialog('option', 'title', 'Үйл ажиллагаа гүйцэтгэх');

              form.dialog({

              buttons: [{
                 text: "Гүйцэтгэх",
                 click: function() {
                    data=getData(eventId); //get all data
                    $.ajax({
                       url: base_url+'/maintenance/done',
                       data: data,
                       type: 'POST',
                       dataType: 'json',
                       success: function (json) {
                          if(json.status =='success'){
                             form.dialog("close");
                             showMessage(json.message, 'success');
                             $('#calendar').fullCalendar('refetchEvents');
                         }else{
                            $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                         }
                       }
                     });
                   }
                },
                {
                    text: "Болих",
                    click: function() {

                       $('#doneby_id').trigger('chosen:updated');
                       $( this ).dialog( "close" );
                    }
                }
                ]
             });
          }else{
             //if(done==true) rowdone.show();// not active
             // allDisabled();
             form.dialog('option', 'title', 'Үйл ажиллагаа');
             form.dialog({
                buttons: [
                {
                   text: "Буцах",
                   click: function() {
                      $( this ).dialog( "close" );
                      // allEnabled();
                   }
                }]
             });

          }
          form.dialog( "open");
      break;

      case "ENG_CHIEF":
         

         form.dialog('option', 'title', 'Үйл ажиллагаа');

         if (active == true && done == false) { //гүйцэтгэлийг дуудна

            rowdone.show();

            $('#rowDoneby').show();

            allEnabled();

            form.dialog({

               buttons: [
                  // {

                  //    text: "Устгах",

                  //    click: function () {

                  //       data = getData(eventId); //get all data
                  //       $.ajax({
                  //          url: base_url + '/maintenance/delete',
                  //          data: data,
                  //          type: "POST",
                  //          dataType: 'json',
                  //          success: function (json) {
                  //             if (json.status == 'success') {
                  //                form.dialog("close");
                  //                showMessage(json.message, 'success');
                  //                $('#calendar').fullCalendar('refetchEvents');
                  //             } else {
                  //                $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                  //             }
                  //          }
                  //       });
                  //    }
                  // },

                  {
                     text: "Засах",
                     click: function () {
                        data = getData(eventId); //get all data
                        $.ajax({
                           url: base_url + '/maintenance/edit',
                           data: data,
                           type: "POST",
                           dataType: 'json',
                           success: function (json) {
                              if (json.status == 'success') {
                                 form.dialog("close");
                                 showMessage(json.message, 'success');
                                 $('#calendar').fullCalendar('refetchEvents');
                              } else {
                                 $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                              }
                           }
                        });
                     }
                  },

                  {
                     text: "Гүйцэтгэх",

                     click: function () {

                        data = getData(eventId); //get all data

                        $.ajax({
                           url: base_url + '/maintenance/done',
                           data: data,
                           type: 'POST',
                           dataType: 'json',
                           success: function (json) {
                              if (json.status == 'success') {
                                 form.dialog("close");
                                 showMessage(json.message, 'success');
                                 $('#calendar').fullCalendar('refetchEvents');
                              } else {
                                 $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                              }
                           }
                        });


                     }
                  },

                  {
                     text: "Болих",
                     click: function () {

                        $('#doneby_id').trigger('chosen:updated');
                        form.dialog("close");
                     }
                  }
               ]
            });

         }else{

            form.dialog({

               buttons: [
                  // {

                  //    text: "Устгах",

                  //    click: function () {

                  //       data = getData(eventId); //get all data
                  //       $.ajax({
                  //          url: base_url + '/maintenance/delete',
                  //          data: data,
                  //          type: "POST",
                  //          dataType: 'json',
                  //          success: function (json) {
                  //             if (json.status == 'success') {
                  //                form.dialog("close");
                  //                showMessage(json.message, 'success');
                  //                $('#calendar').fullCalendar('refetchEvents');
                  //             } else {
                  //                $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                  //             }
                  //          }
                  //       });
                  //    }
                  // },

                  {
                     text: "Засах",
                     click: function () {
                        data = getData(eventId); //get all data
                        $.ajax({
                           url: base_url + '/maintenance/edit',
                           data: data,
                           type: "POST",
                           dataType: 'json',
                           success: function (json) {
                              if (json.status == 'success') {
                                 form.dialog("close");
                                 showMessage(json.message, 'success');
                                 $('#calendar').fullCalendar('refetchEvents');
                              } else {
                                 $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                              }
                           }
                        });
                     }
                  },

                  {
                     text: "Болих",
                     click: function () {

                        $('#doneby_id').trigger('chosen:updated');
                        form.dialog("close");
                     }
                  }
               ]
            });

         }
 
         form.dialog( "open");

      break;

      case "CHIEF":
         form.dialog('option', 'title', 'Үйл ажиллагаа');
         form.dialog({
          buttons: [{
             text: "Зөвшөөрөх",
             click: function() {

                data=getData(eventId); //get all data

                $.ajax({
                   url: base_url+'/maintenance/authorize',
                   data:data,
                   type: "POST",
                   dataType:'json',
                   success: function (json) {
                      if(json.status =='success'){
                         form.dialog("close");
                         showMessage(json.message, 'success');
                         $('#calendar').fullCalendar('refetchEvents');
                      }else{
                         $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                   }
                });
             }
         },
          {
             text: "Устгах",
             click: function() {
                data=getData(eventId); //get all data
                $.ajax({
                   url: base_url+'/maintenance/delete',
                   data:data,
                   type: "POST",
                   dataType:'json',
                   success: function (json) {
                      if(json.status =='success'){
                         form.dialog("close");
                         showMessage(json.message, 'success');
                         $('#calendar').fullCalendar( 'refetchEvents' );
                      }else{
                         $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                   }
                 });
              }
          },
          {
               text: "Засах",
               click: function() {
                   data=getData(eventId); //get all data
                   $.ajax({
                   url: base_url+'/maintenance/edit',
                   data:data,
                   type: "POST",
                   dataType:'json',
                   success: function (json) {
                      if(json.status =='success'){
                         form.dialog("close");
                         showMessage(json.message, 'success');
                         $('#calendar').fullCalendar( 'refetchEvents' );
                      }else{
                         $('p.feedback', form).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                   }
                 });
               }
          },
          {
             text: "Болих",
             click: function() {

                $('#doneby_id').trigger('chosen:updated');
                form.dialog( "close" );
             }
          }
        ]
        });
         form.dialog( "open");
         break;

      default:
         form.dialog('option', 'title', 'Үйл ажиллагаа');
         form.dialog({
                buttons: [
                {
                   text: "Буцах",
                   click: function() {
                      form.dialog( "close" );
                   }
                }]
             });
         form.dialog( "open");
        break;
   }

   /*if ( rolesDo[ role ] ) {
      rolesDo[ role ]();
   } else {
      rolesDo[ "default" ]();
   }*/

   //form.dialog('option', 'title', 'Үйл ажиллагаа');
}

function moveEvent(start, end, eventId, action){

     $.ajax({

       url: base_url+'/maintenance/move',

       data: 'start='+ start +'&end='+ end +'&id='+ eventId+'&action='+action,

       type: "POST",

       dataType:'json',

       success: function(json) {

          if(json.status =='success'){

             showMessage(json.message, 'success');

          }else{

             showMessage(json.message, 'error');
             //$('#calendar').fullCalendar('refetchEvents');

          }

       }

     });

}

function allDisabled(){
    var inputs = $('input[type="text"], input[type="hidden"], select, textarea', form);
    inputs.each(function(){
       var el = $(this);
       el.prop('disabled', 'disabled');
    });
}

function allEnabled(){
    var inputs = $('input[type="text"], input[type="hidden"], select, textarea', form);
    inputs.each(function(){
       var el = $(this);
       el.prop('disabled', false);
    });
}

function getData(eventId){
    var data = {};
    var inputs = $('input[type="text"], input[type="hidden"], select, textarea', form);
    inputs.each(function(){
        var el = $(this);
        data[el.attr('name')] = el.val();
    });
    data['eventId']=eventId;
    data['done']=$("#done").val();
    return data;
}

function showMessage(message, p_class){
    if (!$('p#notification').length)
    {
        $('#nav-bar').prepend('<p id="notification"></p>');
    }

    var paragraph = $('p#notification');
    paragraph.hide();
    paragraph.removeClass();
    // remove all classes from the <p>
    paragraph.addClass(p_class);
    // add the class supplied
    paragraph.html(message);
    // change the text inside

    paragraph.fadeIn('fast', function()
    {
         paragraph.delay(5000).fadeOut();//+100
         // fade out again after 3 seconds

    });
	// fade in the paragraph again
}

// filter
function filter(form_name, target_id, target){

    //var c_id = $('#category_id', form_name).val();
    $.post(base_url+'/maintenance/filter', {id:target_id}, function(newOption) {
        //{id:target_id, field:target_field, table:target}
        //neelttei haalttai,
        var select = $('#'+target+'_id', form_name);

        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }

        $('option', select).remove();

        $.each(newOption, function(val, text) {

           options[options.length] = new Option(text,val);

        });

    });
}

function gen_option(json, form){
   //console.log(JSON.stringify(json));
  // songogdson ИТА-г харуулах
    var select = $('#equipment_id', form);
    if(select.prop) {
       var options = select.prop('options');
    }else {
       var options = select.attr('options');
    }
    $('option', select).remove();
    select.append(new Option("Нэг тоног төхөөрөмж сонго", 0));
    $.each(json, function(val, text) {
       options[options.length] = new Option(text, val);
    });
}


var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
      
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {

			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
      
      });
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};
