/* 
 * events javascript @Oct 22 2013 
 */

$(document).ready(function(){   
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
      width: 480, // set the width of the dialog to 400px
      autoOpen: false, // don't want it to open automatically
      resizable: false, // set it to not resizable
      modal: true, // use a modal overlay background
      close: function(){	// onClose     
         $('p.feedback', form).html('').hide();
         // clear & hide the feedback msg inside the form
         $('input[type="text"], input[type="hidden"], textarea, select', form).val('');
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
         url:base_url+"/event/getevent",
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
          $.post( base_url+'/event/getOne', {id:calEvent.id}, function(data){
             //createdbyId.val(data.createdbyId)
             $("#eventId").text(calEvent.id);
             $("#event").val(data.event);

             if(data.createdby!==null) $("#createdbyId").text(data.createdby);
             else $("#createdbyId").text('');
             eventtypeId.val(data.eventtype_id);
             equipmentId.val(data.equipment_id);
             locationId.val(data.location_id);       
             done.val(data.done);
             if(data.doneby) $("#doneby").text(data.doneby);
             else $("#doneby").text('');
             //isdone=data.isdone;
             clickEvent(calEvent.id, data.active, data.isdone);
          }); 
          $('#rowDoneby').show();
          $("#startdate").val($.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm")); //sstt
          $("#enddate").val($.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm"));                      
           
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
                  url:      base_url+'/event/add',
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
              $('#rowDoneby').hide();              
              allEnabled();
              form.dialog('option', 'title', 'Үйл ажиллагаа гүйцэтгэх');
              form.dialog({
              buttons: [{  
                 text: "Гүйцэтгэх",
                 click: function() {             
                    data=getData(eventId); //get all data
                    $.ajax({                       
                       url: base_url+'/event/done',
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
                       $( this ).dialog( "close" );             
                    }
                }
                ]
             });
          }else{
             //if(done==true) rowdone.show();// not active
             allDisabled();
             form.dialog('option', 'title', 'Үйл ажиллагаа');    
             form.dialog({
                buttons: [               
                {
                   text: "Буцах",
                   click: function() {                  
                      $( this ).dialog( "close" ); 
                      allEnabled();
                   }
                }]
             });
             
          }
          form.dialog( "open");
      break;

      case "ENG_CHIEF":
         form.dialog('option', 'title', 'Үйл ажиллагаа');
         form.dialog({
            buttons: [{
             text: "Устгах",
             click: function() { 
                data=getData(eventId); //get all data
                $.ajax({
                   url: base_url+'/event/delete',
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
                   url: base_url+'/event/edit',
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
                form.dialog( "close" );                 
             }
          }
        ]
        });
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
                   url: base_url+'/event/authorize',
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
                   url: base_url+'/event/delete',
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
                   url: base_url+'/event/edit',
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
       url: base_url+'/event/move',
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