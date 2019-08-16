/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){	
    var role = $("#role").val();
    $("#trdone").hide();  
    
    //үүсгэх
    $("#cDialog" ).dialog({
      autoOpen: false,
      width: 400,
      heigth:300,
      modal:true,        
      buttons: [
      {
         text: "Хадгалах",
         click: function() {             
            var etitle=$("#cEvent").val(), startDate = $("#cStartdate").val(), endDate=$("#cEnddate").val(),
                createdby_id = $("#createdby_id").val(), equipment_id =$("#equipment_id").val(), location_id=$("#location_id").val();                   
            if(equipment_id==0)
                alert('Нэг төхөөрөмжийг сонго!');
            else if(location_id==0)
                alert('Нэг байршлийг сонго!');
            else if(etitle=='')
                alert('Тодорхойлолтоо оруул!');                        
            else if(startDate==''||endDate=='')
                alert('Эхлэх хугацаа, дуусах хугацааг шалга!');
            else if((new Date(endDate)-new Date(startDate))/(1000*60)<=0) 
                alert((new Date(endDate)-new Date(startDate))/(1000*60)+'[Дуусах хугацаа] > [Эхлэх хугацаа]-с их байх ёстой!');
            else{
               $.ajax({
                 url: ' http://localhost/ecns/event/addEvent',
                 data:'title='+ etitle+'&start='+startDate+'&end='+endDate+'&createdby_id='+createdby_id+'&location_id='+location_id+'&equipment_id='+equipment_id, 
                 type: "POST",
                 success: function (json) {                      
                    $("#cEvent").val('');
                    $("#equipment_id").val(0);
                    $("#location_id").val(0);                    
                    $('#calendar').fullCalendar( 'refetchEvents' );
                 }
                });	
               $(this).dialog("close");
           }
         }
      },               
      {   text: "Болих",
          click: function() {                  
             $( this ).dialog( "close" );             
          }
      }
      ] });
  // Гүйцэтгэлийн dialog
    $("#dialog" ).dialog({
      autoOpen: false,
      width: 400,
      heigth:300,
      modal:true,        
      buttons: [      
      {  // done button
         text: "Гүйцэтгэх",
         click: function() {
            var etitle=$("#cEvent").val(), startDate = $("#cStartdate").val(), endDate=$("#cEnddate").val(),
                createdby_id = $("#createdby_id").val(), equipment_id =$("#equipment_id").val(), location_id=$("#location_id").val();                
             //if(etitle==''){
                 alert(etitle);                
             //}else{
                 $.ajax({
                 url: ' http://localhost/ecns/event/doneEvent',
                 data:'title='+ etitle+'&start='+startDate+'&end='+endDate+'&doneby_id='+createdby_id+'&location_id='+location_id+'&equipment_id='+equipment_id, 
                 type: "POST",
                 success: function (json) {                     
                        $('#calendar').fullCalendar( 'refetchEvents' );                     
                        etitle='';
                        startdate='';
                        endDate='';
                  }
                });
                $(this).dialog("close");
             //}
            
         }
      },         
      {
          text: "Болих",
          click: function() {                  
             $( this ).dialog( "close" );             
          }
      }
      ] });
  
   $('#cStartdate').datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   }); 
   
   $('#cEnddate').datetimepicker({
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
         url:"http://localhost/ecns/event/getevent",
         cache: true        
      },	
      eventColor: '#FFD900',       
      eventTextColor: '#000000',
      eventborderColor:'red',
      // here add event js
      selectable: true ,
      selectHelper: true ,            
      editable: true,
      eventDrop: function(event, delta) {
          if(event.color=='#ccf0af')          
            var startdt = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var enddt = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");            
            //role & activateby_id is set
            $.ajax({
               url: 'http://localhost/ecns/event/move',
               data: 'start='+ startdt +'&end='+ enddt +'&id='+ event.id ,
               type: "POST",
               success: function(json) {
                  alert("Амжилттай зөөлөө.");			         
               }
             });
      }, 
      select: function (start, end, allday){            
           // $( "#cDialog" ).dialog('option', 'title', 'Шинэ бүртгэл үүсгэх');
            var title=$('#cDialog').dialog('open');   
            if(title){			      
               $('#cStartdate').val($.fullCalendar.formatDate(start,'yyyy-MM-dd HH:mm'));
               $('#cEnddate').val($.fullCalendar.formatDate(end, 'yyyy-MM-dd HH:mm'));
//               calendar.fullCalendar ( 'renderEvent' , {
//                    title: title,
//                    start: start,
//                    end: end,
//                    Allday: Allday
//                  }, true  
//               );
            }
            calendar.fullCalendar ( 'unselect' );
      },
      eventResize: function(event){
        start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
        end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
        $.ajax({
           url: 'http://localhost/ecns/event/move',
           data: 'start='+ start +'&end='+ end +'&id='+ event.id ,
           type: "POST",
           success: function(json) {
              alert("Амжилттай сунгалаа."); 
           }
        }); 
      },
      
      eventClick: function(calEvent, jsEvent, view) {
            // post-с утгийг авах id-гаар авч хэрэв active_id утгагүй байвал
            $("#event").val(calEvent.title);
            $("#startdate").val($.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm")); //sstt
            $("#enddate").val($.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm"));
            $("#cEventid").val(calEvent.id);         
            
            $.post( '/ecns/event/getOne', {id:calEvent.id}, function(data){
               if(data.equipment_id) $('#fequipment_id').val(data.equipment_id);
               if(!data.doneby_id&&data.activedby_id){
                  $("#trdone").show();   //гүйцэтгэлийг харуулна               
                  $(":button:contains('Гүйцэтгэх')").show();
               }else{
                   $(":button:contains('Гүйцэтгэх')").hide();                 
                   $("#trdone").hide();   
               }
               $('#flocation_id').val(data.location_id);
               
            });            
           // $( "#dialog" ).dialog('option', 'title', 'Гүйцэтгэл');
            $( "#dialog" ).dialog( "open");
                        
            //change the border color just for fun
            $(this).css('border-color', 'red');		        
         }          
     });
//end jquery     
    // $("#date1").click(function() {       
      //  calendar.fullCalendar('gotoDate', 2013, 9);
     //});     
});

 