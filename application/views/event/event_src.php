<?
/*
 * created by Ganaa
 * created dt Sep 24, 2013 @9:50am
 * call event page here
 */
?>
<style>
body {
	font-size: 14px;
	font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
}

#calendar {
	width: 90%;
	height: 80%;
	margin: 20px auto;
}
</style>
<script>
$(document).ready(function(){	
    var role = $("#role").val();
   // this dialog used only SUPVISOR /  
   //alert(role);
   $("#dialog" ).dialog({
      autoOpen: false,
      width: 450,
      heigth:800,
      modal:true,        
      buttons: [                  
        {
           text: "Зөвшөөрөх",
           click: function() {
              var id=$("#eventid").val();              
              // update script here kk                	  
              $.ajax({
                 url: 'http://localhost/ecns/event/authorize',
                 data:'id='+id, 
                 type: "POST",
                 success: function (json) {
                   //   alert ( 'OK' );		
                    $('#calendar').fullCalendar('refetchEvents');
                    //window.location = "http://localhost/calendar";                 
                 }
              });
              $(this).dialog("close");
           }
        },   
        {
           text: "Устгах",
           click: function() {
             	var id=$("#eventid").val();  
                $.ajax({
                    url: 'http://localhost/calendar/delete.php ',
                    data:'id='+id, 
                    type: "POST",
                    success: function (json) {
                     //   alert ( 'OK' );		
                       $('#calendar').fullCalendar( 'refetchEvents' );
                             //window.location = "http://localhost/calendar";
                             //calendar;			         
                     }
                 });
             	$(this).dialog("close");
             }
        }, 
        {
             text: "Засах",
             click: function() {
                $("#eeventid").val($("#eventid").val());
             	$("#eevent").val($("#event").text());
             	var estartDate=$("#startdate").text();
             	var eendDate=$("#enddate").text();
             	estartDate=estartDate.replace('@', '');
             	eendDate=eendDate.replace('@', '');
             	$("#estartdate").val(estartDate);
             	$("#eenddate").val(eendDate);
             	$( "#edialog" ).dialog( "open" );
             	$(this).dialog("close");
             }
        },         
        {
             text: "Болих",
             click: function() {                  
                $( this ).dialog( "close" );
               // clear();
             }
         }
        ]
   });       
   
   $("#edialog" ).dialog({
      autoOpen: false,
      width: 450,
      heigth:800,
      modal:true,        
      buttons: [
      {
           text: "Засах",
           click: function() {
              var id=$("#eeventid").val();              
              var eevent=$("#eevent").val();              
              var start=$("#estartdate").val();              
              var end=$("#eenddate").val();              
           	  // update script here kk                	  
           	  $.ajax({
			     url: 'http://localhost/calendar/edit.php',			     
			     data: 'event='+ eevent+'&start='+ start +'&end='+ end +'&id='+id,
			     type: "POST",	
			     success: function (json) {
			     //   alert ( 'OK' );		
			        $('#calendar').fullCalendar( 'refetchEvents' );
			         	//window.location = "http://localhost/calendar";
			        	//calendar;			         
			     }
			   });
              $(this).dialog("close");
           }
        },   
       {
             text: "Болих",
             click: function() {                  
                $( this ).dialog( "close" );
               // clear();
             }
         }
        ]
    });
       
    
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
             $.ajax({
              url: ' http://localhost/ecns/event/addEvent',
              data:'title='+ etitle+'&start='+startDate+'&end='+endDate+'&createdby_id='+createdby_id+'&location_id='+location_id+'&equipment_id='+equipment_id, 
              type: "POST",
              success: function (json) {
                     //alert ( 'OK' );		
                     $('#calendar').fullCalendar( 'refetchEvents' );
                     //window.location = "http://localhost/calendar";
                     //calendar;
                     etitle='';
                     startdate='';
                     endDate='';
               }
          });	
            $(this).dialog("close");
         }
      },         
      {
          text: "Болих",
          click: function() {                  
             $( this ).dialog( "close" );
             clear();
          }
      }
      ] });
  
   //Finish event
   $("#fdialog" ).dialog({
      autoOpen: false,
      width: 400,
      heigth:300,
      modal:true,        
      buttons: [
      {
         text: "Хадгалах",
         click: function() {
            var etitle=$("#eEvent").val(), startDate = $("#cStartdate").val(), endDate=$("#cEnddate").val(),
                createdby_id = $("#createdby_id").val(), equipment_id =$("#equipment_id").val(), location_id=$("#location_id").val();                
             $.ajax({
              url: ' http://localhost/ecns/event/dEvent',
              data:'title='+ etitle+'&start='+startDate+'&end='+endDate+'&createdby_id='+createdby_id+'&location_id='+location_id+'&equipment_id='+equipment_id, 
              type: "POST",
              success: function (json) {                     
                     $('#calendar').fullCalendar( 'refetchEvents' );                     
                     etitle='';
                     startdate='';
                     endDate='';
               }
          });	
            $(this).dialog("close");
         }
      },         
      {
          text: "Болих",
          click: function() {                  
             $( this ).dialog( "close" );
             clear();
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
   
   $('#estartdate').datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   }); 
   $('#eenddate').datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });
   // onclick 
   var date = new Date();
   var d = date.getDate();
   var m = date.getMonth();
   var y = date.getFullYear();		
// start fullCalendar
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
      eventColor: <? $i=1; if($i==1) echo "'#FFD900'"; else echo "'#FF6600'"; ?>,       
      eventTextColor: '#000000',
      eventborderColor:'red',
      // here add event js
      selectable: true ,
      selectHelper: true ,      
      // Хэрэв засах бол ерөнхий инженер
      editable: true,
      eventDrop: function(event, delta) {
          if(event.color=='#ccf0af')          
            start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");            
            //role & activateby_id is set
            $.ajax({
               url: 'http://localhost/ecns/event/move',
               data: 'start='+ start +'&end='+ end +'&id='+ event.id ,
               type: "POST",
               success: function(json) {
                  alert("Өдрийг амжилттай зөөлөө.");			         
               }
             });
      }, 
      eventResize: function(event){
        start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
        end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
        $.ajax({
           url: 'http://localhost/ecns/event/move',
           data: 'start='+ start +'&end='+ end +'&id='+ event.id ,
           type: "POST",
           success: function(json) {
              alert("Хугацааг амжилттай сунгалаа!."); 
           }
        }); 
      },
      <?=$action?>
      eventClick: function(calEvent, jsEvent, view) {
            // post-с утгийг авах id-гаар авч хэрэв active_id утгагүй байвал
            $.post( '/ecns/event/getOne', {id:calEvent.id}, function(data){
               if(data.equipment_id) $('#cequipment_id').text(data.equipment_id);
               if(data.doneby_id&&data.done){
                  $("#tdone").show();
                  $('#done').text(data.done);
               }else 
                  $("#tdone").hide();
               if(data.activedby_id||data.approvedby_id)
                  $(":button:contains('Зөвшөөрөх')").prop("disabled", true).addClass("ui-state-disabled");
               else                  
                  $(":button:contains('Зөвшөөрөх')").prop("disabled", false).removeClass("ui-state-disabled");               
               // console.log(data.doneby_id);
               // console.log(data.done);
            });            
                           
            $( "#dialog" ).dialog( "open" );        
            event.preventDefault();
            $("#event").text(calEvent.title);
            $("#startdate").text($.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd @HH:mm")); //sstt
            $("#enddate").text($.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd @HH:mm"));
            $("#eventid").val(calEvent.id);         
            //change the border color just for fun
            $(this).css('border-color', 'red');		        
         }          
     });
     //end     
});
</script>
<div id='calendar'></div>
<input type="hidden" id="role" value="<? echo $role; ?>" />

<div id="dialog" title="Үйл ажиллагаа">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Тоног төхөөрөмж:</td>
				<td><span id='cequipment_id'></span></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><span id='event'></span></td>
			</tr>
			<tr id='tdone'>
				<td>Гүйцэтгэл:</td>
				<td><span id='done'></span></td>
			</tr>
			<tr>
				<td>Эхэлсэн огноо:</td>
				<td><span type="text" id="startdate" /></span></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><span type="text" id="enddate" /></span></td>
			</tr>
		</table>
		<input type='hidden' name='eventid' id='eventid' />
	</div>
</div>

<!-- Шинэ -->
<!-- ашиглах эрхүүд АДМИН, ENG, TECH, SUPENG-->
<div id="cDialog" title="Шинэ бүртгэл үүсгэх">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<input type='hidden' id='createdby_id' value=<? echo $createdby_id; ?> />
		<table>
			<tr>
				<td>Тоног төхөөрөмж</td>
				<td><? echo form_dropdown('equipment_id',$equipment, null, 'id=equipment_id'); ?></td>
			</tr>
			<tr>
				<td>Байршил</td>
				<td><? echo form_dropdown('location_id',$location, null, 'id=location_id'); ?></td>
			</tr>
			<tr>
				<td>Тодорхойлолт/Шалтгаан:</td>
				<td><textarea name='event' id='cEvent' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="cStartdate" size=16 /></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="cEnddate" size=16 /></td>
			</tr>
		</table>
	</div>
</div>
<!--Засах-->
<!-- ашиглах эрхүүд АДМИН, ENG, TECH, SUPENG-->
<div id="edialog" title="Үйл ажиллагаа засах">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Үйл ажиллагаа:</td>
				<td><textarea name='event' id='eevent' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="estartdate" size=16></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="eenddate" size=16></td>
			</tr>
		</table>
		<input type='hidden' name='eventid' id='eeventid' />
	</div>
</div>

<!--Done-->
<? if($role=='ENG'||$role=='UNITCHIEF'||$role=='SUPENG'||$role=='TECH'){ ?>
<div id="fdialog" title="Үйл ажиллагаа гүйцэтгэх">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td>Үйл ажиллагаа:</td>
				<td><textarea name='event' id='event' col="40" row="10"></textarea></td>
			</tr>
			<tr>
				<td>Эхлэх огноо:</td>
				<td><input type="text" id="startdate" size=16 /></td>
			</tr>
			<tr>
				<td>Дуусах огноо:</td>
				<td><input type="text" id="enddate" size=16 /></td>
			</tr>
		</table>
		<input type='hidden' name='eventid' id='eeventid' />
	</div>
</div>
<? } ?>
