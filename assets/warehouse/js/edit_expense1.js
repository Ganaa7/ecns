$(function(){
	//validator
   spare_dialog = $("#spare_dialog");
   spare_dialog.dialog({
       autoOpen: false,
       width: 580,       
       resizable: false,    
       modal: true,
       // Хаах товч
       close: function () {
          $('p.feedback', spare_dialog).html('').hide();
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select, textarea', spare_dialog).val('');
          // clear the input values on form    
          $(this).dialog("close");
           $('#qty_wrap', spare_dialog).html('');
       }
   });
//validator
	 spare_edit_dialog = $("#spare_edit_dialog");
	 spare_edit_dialog.dialog({
	     autoOpen: false,
	     width: 580,       
	     resizable: false,    
	     modal: true,
	     // Хаах товч
	     close: function () {
	        $('p.feedback', spare_edit_dialog).html('').hide();
	        // clear & hide the feedback msg inside the form
	        $('input[type="text"], input[type="hidden"], select, textarea', spare_edit_dialog).val('');
	        // clear the input values on form    
	        $(this).dialog("close");
	         $('#qty_wrap_edit', spare_edit_dialog).html('');
	     }
	 });




  // Хэсгийг сонгоход тухайн тасаг харагдах хэсэг
  $('#section_id').on('change', function(evt, params) {
	    $.post( base_url+'/wh_spare/index/get_equipby_id', {id:$(this).val(), flag:'yes'}, function(newOption) {  
	        var select = $('#equipment_id');
	        if(select.prop) {
	           var options = select.prop('options');
	        }else {
	           var options = select.attr('options');
	        }
	        $('option', select).remove();      
	        
	        $.each(newOption, function(val, text) {
	            options[options.length] = new Option(text, val);        
	        });
	 	});
	 	//sector-g avah
	 	$.post( base_url+'/wh_spare/index/get_section_id', {id:$(this).val(), flag:'no'}, function(newOption) {  
	        var select = $('#sector_id');
	        if(select.prop) {
	           var options = select.prop('options');
	        }else {
	           var options = select.attr('options');
	        }
	        $('option', select).remove();      
	        
	        $.each(newOption, function(val, text) {
	            options[options.length] = new Option(text, val);        
	        });
	 	});
   });  //end section filter

    // Тасгийг сонгоход тухайн тасгийн төхөөрөмж харагдах хэсэг
  $('#sector_id').on('change', function(evt, params) {
	  	var section = $('#section_id').val();
	    $.post( base_url+'/wh_spare/index/get_equipby_id', {id:$(this).val(), section_id:section, flag:'no'}, function(newOption) {  
	        var select = $('#equipment_id');
	        if(select.prop) {
	           var options = select.prop('options');
	        }else {
	           var options = select.attr('options');
	        }
	        $('option', select).remove();      
	        
	        $.each(newOption, function(val, text) {
	            options[options.length] = new Option(text, val);        
	        });
	 	});
	 });  //end sector filter

  // Шинэ сэлбэг товчийг дарахад
  $('#equipment_id').on('change', function(){
  	  //Төхөөрөмж солиход тухайн төхөөрөмжийн дээрх сэлбэгүүдийг дуудна.
  	  //var equipment_id = $(this).val();
  	  var id_section = $("#section_id").val();
  	  var id_sector = $("#sector_id").val();

	  $.post( base_url+'/wh_spare/index/get_spare_id', {id:$(this).val(), section_id:id_section, sector_id:id_sector}, function(newOption) {  
	      var select_spare = $('#spare_id');
	      if(select_spare.prop) {
	         var options = select_spare.prop('options');
	      }else {
	         var options = select_spare.attr('options');
	      }
	      $('option', select_spare).remove(); 
	      $.each(newOption, function(val, text) {
	          options[options.length] = new Option(text, val);        
	      });
	      $('#spare_id').trigger("chosen:updated");
	  });
  });

  //зарлагийн хэсэг дээр
  $('#section_id', '#expense_form').on('change', function(evt, params) {	   
	 	//sector-g avah
	 	$.post( base_url+'/wh_spare/index/get_section_id', {id:$(this).val(), flag:'no'}, function(newOption) {  
	        var select = $('#sector_id', '#expense_form');
	        if(select.prop) {
	           var options = select.prop('options');
	        }else {
	           var options = select.attr('options');
	        }
	        $('option', select).remove();      
	        
	        $.each(newOption, function(val, text) {
	            options[options.length] = new Option(text, val);        
	        });
	 	});
   });

  $(".chosen-select").chosen();

  var spare_id, qty;
  spare_id = $('#spare_id');
  qty = $('#qty', spare_dialog);
  var status =0;
  //dialog option here
  spare_dialog.dialog('option', 'title', 'Зарлага гаргах сэлбэг');
  spare_dialog.dialog({ 
  buttons: {
     "Зарлагад нэмэх": function () {      
     	  var data = {};
          var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#form_supplier');
          
          inputs.each(function(){
            var el = $(this);
            data[el.attr('name')] = el.val();
          });   

          // console.log('spare_id'+$('#spare_id').val());
          //check if id дахин устгаж байвал дахин нэмэхгүй!              
          $("input[name='spare_id[]'").each(function(){
             var val = $(this);
             if($('#spare_id').val() ==val.val()){
             	status = 1;
             }
          });

          var invoice_id = $('#invoice_id');
          // status 
          if(status ==0){
                  if(spare_id.val()==0||spare_id.val()==null){                       
	             alert("Нэг сэлбэг сонгоно уу?");             
	          }else if(qty.val()==0||qty.val()==null||(typeof qty.val() !== 'number' && qty.val() % 1 !== 0)){
	             alert("Тоо хэмжээг оруулна уу? Бүхэл тоогоор оруулна уу!");
	             qty.focus();
	          }else{
	             // add spare, qty, reason to input in lists                                      
	             //inputStr ="<input type='hidden' id='count' name='count' value='"+i+"'>";                   
	             if($("#count").val()>0){ i =$("#count").val(); } else i =0;
	             i++;   
	             $("#count").val(i);
	             inputStr="<input type='hidden' id='spare_id_"+i+"' name='spare_id[]' value='"+spare_id.val()+"'>";
	             inputStr +="<input type='hidden' id='qty_"+i+"' name='qty[]' value='"+qty.val()+"'>";
	             appent_txt ="<tr id=row_"+spare_id.val()+"><td>"+i+"</td><td>"+$('#spare_txt').text()+" Парт дугаар:"+$("#part_number_txt").text()+"</td><td>"+$('#amt', spare_dialog).val()+"</td><td>"+qty.val()+$('#measure_txt').text()+"</td><td>"+Math.floor($('#amt', spare_dialog).val()*qty.val())+" ₮ <a class='remove_it' data-id='857' href='#' onclick='remove_it("+spare_id.val()+","+invoice_id.val()+")'> (Хасах) </a> </td></tr>"+inputStr;
	             $("#expense_table").append(appent_txt);                   
	             
                     var j = 1;                     
	             // check box select 
	             $.each($("input[name='spare_pk[]']:checked", '#spare_dialog'), function() {
	                //spares.push($(this).val());
	                $('#expense_form').append('<input type="hidden" class="dtl_id_'+spare_id.val()+'" name="dtl_id['+spare_id.val()+']['+j+']" value="'+$(this).val()+'" />');
                        j++;
	             }); 

	             //remove tr 
	             $('#remove_tr', '#expense_form').hide();
	             $( this ).dialog( "close" );
	          } 
          }else{
          	 status = 0;
          	 alert('['+$('#spare_txt').text()+'] сэлбэгийг аль хэдийн жагсаалтад нэмсэн тул дахин нэмэх боломжгүй!');
          }
     },
     "Цуцлах": function () {
         spare_dialog.dialog("close");
         $('#qty_wrap>span', '#spare_dialog').remove();
     }
  }
 }); 

  //edit spare dialog

  spare_edit_dialog.dialog('option', 'title', 'Зарлага гаргах сэлбэг');
  spare_edit_dialog.dialog({ 
  buttons: {
     "Зарлага засах": function () {      
        var data = {};
          var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#form_supplier');
          var spare_id = $('#spare_id', '#spare_edit_dialog');
          var qty = $('#qty', '#spare_edit_dialog');
          var amt = $('#amt', '#spare_edit_dialog');
          inputs.each(function(){
            var el = $(this);
            data[el.attr('name')] = el.val();
          });   

          // console.log('spare_id'+$('#spare_id').val());
          //check if id дахин устгаж байвал дахин нэмэхгүй!              
          // $("input[name='spare_id[]'").each(function(){
          //    var val = $(this);
          //    if($('#spare_id').val() ==val.val()){
          //     status = 1;
          //    }
          // });

          console.log('qty'+qty.val());

          var invoice_id = $('#invoice_id');
          // status           
          var j = 1;                     
           var cnt = $('#cnt', '#spare_edit_dialog').val();
          $('#spare_qty_txt_'+cnt).text(qty.val());
          $("#spare_qty_total_"+cnt).text(qty.val()*amt.val());

          $('#qty_'+cnt).val(qty.val());


          // check box select            
          $('#expense_form .dtl_id_'+spare_id.val()).remove();

          $.each($("input[name='spare_pk[]']:checked", '#spare_edit_dialog'), function() {
              //spares.push($(this).val());
              //delete from expense_form              

              $('#expense_form').append('<input type="hidden" class="dtl_id_'+spare_id.val()+'" name="dtl_id['+spare_id.val()+']['+j+']" value="'+$(this).val()+'" />');

                 j++;
           }); 
           $( this ).dialog( "close" );        
          
     },
     "Цуцлах": function () {
         spare_edit_dialog.dialog("close");
         $('#qty_wrap_edit>span', '#spare_edit_dialog').remove();
     }
  }
 });  

  //төхөөрөмжийн сонголтоос сонгоход dialog гаргах  
  $(".chosen-select").chosen();
  $('.chosen-select', '#expense_spare_form').on('change', function(evt, params) {
		var _id = $("#spare_id").chosen().val();	
		var spare = $("#spare_id").chosen().text();	
		//console.log('console'+_id);
		$.ajax({
		    type:    'POST',
		    url:    base_url+'/wh_spare/index/get/',
		    data:   {id:_id, field:'end_qty'},
		    dataType: 'json', 
		    success:  function(json) {
		       if(json.status=='success'){		     	   
		           $('#part_number').val(json.spare.part_number);
		           $('#type').val(json.spare.sparetype);
		           $('#type_id').val(json.spare.sparetype_id);
		           $('#amt').val('');
		           $('#qty').val('');		           
		           $('#measure_txt', spare_dialog).text(json.spare.measure);	
		           //collect texts to teh dialog boxes
		           //console.log();
		           $('#txt_section', '#spare_dialog').text(json.spare.section);
		           $('#txt_sector', '#spare_dialog').text(json.spare.sector);
		           $('#txt_equipment', '#spare_dialog').text(json.spare.equipment);
		           $('#manufacture_id', '#spare_dialog').val(json.spare.manufacture_id);		           
		           $('#spare_type_txt', '#spare_dialog').text(json.spare.sparetype);
		           $('#part_number_txt', '#spare_dialog').text(json.spare.part_number);		           		           
		           //herev qty = 0 bval remove the title of qty		          
		           // $('#end_qty', '#spare_dialog').val(json.spare.end_qty);
		           $('#spare_txt', '#spare_dialog').text(json.spare.spare);	           
		           //spare_id-s 
		           $('#spare_id', spare_dialog).val(json.spare.spare_id);		           
		           get_expense_dtl(_id,  $('#spare_txt', '#spare_dialog').text(), "qty_wrap");
		           //console.log($("#qty_wrap, #spare_dialog").find('table').length);
		           //if($('#qty_wrap', spare_dialog).length)
		           spare_dialog.dialog("open");		           			           			           	
		     	}else
		     	   alert(spare+ ' алдаатай байна! Дахин оролдоно уу!');
		    }
		});
  });

  $('#expense_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  
 
  // Шинэ сэлбэгийг хасах Сонгоход bar code харуулах dialog харуулах
  // Шинэ - сэлбэгийг хасах
  var xTriggered = 0;
  $("#qty", spare_dialog).keyup(function(event) {  
  	  var total = 0;
  	  // console.log('test keyup');
      var chbox = $('input:checkbox', spare_dialog), chboxQty, inQty=$("#qty", spare_dialog).val(), i=1;
      // console.log(chbox);
      xTriggered++;
      //нийт checkbox-г uncheck hiine.       
      chbox.prop('checked', false);
      //check box-Г тоолно
      chboxQty =chbox.length;      
      if(inQty<=chboxQty){
         for(i=1; i<=inQty; i++){
             //console.log("data:"+i);
             $("#check_"+i).attr('checked', true);
             //тухайн нэгж үнийг тодорхойлох хэрэгтэй ба!
             console.log('value'+Math.floor($("#amt_"+i).val()));
             total = total+Math.floor($("#amt_"+i).val());
         } 
         $('#amt', spare_dialog).val(Math.floor(total/inQty));
          //var msg = "Qty value:"+inQty+" Handler for .keyup() called " + xTriggered + " time(s).";
          //console.log(msg);
          //console.log('total:'+total);
          //if done set total to the next input files
      }else 
           alert('Үлдэгдэл хүрэлцэхгүй байна. Зарлагын тоо хэмжээ Агуулахын тооноос их байх ёсгүй!');
    }).keydown(function( event ) {
       if ( event.which == 13 ) {
          event.preventDefault();
       }
    });


     // Шинэ сэлбэгийг хасах Сонгоход bar code харуулах dialog харуулах
   
  $("#qty", spare_edit_dialog).keyup(function(event) {  
      var total = 0;
      // console.log('test keyup');
      var chbox = $('input:checkbox', spare_edit_dialog), chboxQty, inQty=$("#qty", spare_edit_dialog).val(), i=1;
      // console.log(chbox);
      xTriggered++;
      //нийт checkbox-г uncheck hiine.       
      chbox.prop('checked', false);
      //check box-Г тоолно
      chboxQty =chbox.length;      
      if(inQty<=chboxQty){
         for(i=1; i<=inQty; i++){
             //console.log("data:"+i);
             $("#check_"+i).attr('checked', true);
             //тухайн нэгж үнийг тодорхойлох хэрэгтэй ба!
             console.log('value'+Math.floor($("#amt_"+i).val()));
             total = total+Math.floor($("#amt_"+i).val());
         } 
         $('#amt', spare_edit_dialog).val(Math.floor(total/inQty));
          //var msg = "Qty value:"+inQty+" Handler for .keyup() called " + xTriggered + " time(s).";
          //console.log(msg);
          //console.log('total:'+total);
          //if done set total to the next input files
      }else 
           alert('Үлдэгдэл хүрэлцэхгүй байна. Зарлагын тоо хэмжээ Агуулахын тооноос их байх ёсгүй!');
    }).keydown(function( event ) {
       if ( event.which == 13 ) {
          event.preventDefault();
       }
    });

    // submit expense button 
    $('#submit_btn', '#expense_form').click(function (){
       //console.log('test hiij baina');
       var data = {};
       var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#expense_form');
       $('p.feedback', '#expense_form').removeClass('success, notify, error').html('').hide();

       var data = $('#expense_form' ).serialize();
       // var users = $('input:text.users').serialize();        
        inputs.each(function(){
           var el = $(this);
           data[el.attr('name')] = el.val();
        });  
       // data send to the expense save warehoue
       $.ajax({
            type:    'POST',
             url:    base_url+'/wh_spare/index/jx_edit_expense/',
             data:   data,
             dataType: 'json', 
             success:  function(json) {
                if(json.status=='success'){
                       showMessage(json.message, 'success');
                       window.location = base_url+'/wh_spare/index/expense';
                }else{			  	 	
                       $('p.feedback', '#expense_form').removeClass('success, notify').addClass('error').html(json.message).show();
                }
             }
       });
    });
});

//тухайн мөрөөс бичлэгийг хасах
function remove_it(id, inv_id){      
   var count = $('#count').val();   
   $('#count').val(count-1);
   $('#row_'+id).remove(); 
   $('.dtl_id_'+id).remove();    
}

//call expense_dtl
function get_expense_dtl(spare_id, spare, wrapper){    
     if(spare_id!==null){        
        var xmlhttp;         
        if (spare_id===""){
           document.getElementById(wrapper).innerHTML="";           
           return;
        }
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
        }else{// code for IE6, IE5
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
           if (xmlhttp.readyState==4 && xmlhttp.status==200){
           	  if(xmlhttp.responseText){
                 document.getElementById(wrapper).innerHTML=xmlhttp.responseText;                 
                 status = 1;

           	  }
              else{
              	$('p.feedback', '#spare_dialog').addClass('error').html('Агуулахад ['+spare+'] сэлбэг дууссан байна! Үлдэгдэл = 0!').show();
              	//alert('Агуулахад '+spare+' сэлбэг дууссан байна! Үлдэгдэл = 0!');              	
              }
           }
        }
        if(wrapper =='qty_wrap_edit'){
          var status = 'edit';
          var invoice_id  = $('#invoice_id').val();
        }
        else 
          var status = 'none';
        xmlhttp.open("GET",base_url+"/wh_spare/get_exp_dtl?spare_id="+spare_id+"&invoice_id="+invoice_id,true);
        xmlhttp.send();              
     }else{
        alert("Сэлбэг сонгогдоогүй байна! Сэлбэгийг сонгоно уу?");           	
     }
 }  
 
function checkit(checkbox){
   count =$('#qty').val();
   if(checkbox.checked===true){
      ++count;         
      //alert("Сонгосон утга:"+count);
   }else{   count--; //alert("Сонгосон утга:"+count);
   }
       document.getElementById('qty').value=count;
       return;
}

function check_it_edit(checkbox){
   count =$('#qty', '#spare_edit_dialog').val();
   if(checkbox.checked===true){
      ++count;         
      //alert("Сонгосон утга:"+count);
   }else{   count--; //alert("Сонгосон утга:"+count);
   }
       $('#qty', '#spare_edit_dialog').val(count);
       return;
}

function showMessage(message, p_class){
   if (!$('p#notification').length){
      //$('#main_wrap').prepend('<p id="notification"></p>');
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
   paragraph.fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}

//тухайн бичлэгийг Засах фүнкц
function edit_it(cnt, id, inv_id){   
    // тухайн qty* amt Засах шаардлагаггйү зөвхөн тоог засна.
    //call ajax here because            
    $.ajax({
        type:     'POST',
        url:    base_url+'/wh_spare/index/jx_check_edit/',
        data:   {spare_id : id, invoice_id:inv_id},
        dataType: 'json', 
        success:  function(json){ 
           if (json.status == 'success'){                    
            //befor get by equipment id don't follow it    
            $.ajax({
                  type:    'POST',
                  url:    base_url+'/wh_spare/index/get/',
                  data:   {id:id, field:'end_qty'},
                  dataType: 'json', 
                  success:  function(json) {
                     if(json.status=='success'){                                       
                         $('#cnt', '#spare_edit_dialog').val(cnt);               
                         $('#measure_txt', spare_edit_dialog).text(json.spare.measure);                 
                         //console.log();
                         $('#txt_section', '#spare_edit_dialog').text(json.spare.section);
                         $('#txt_sector', '#spare_edit_dialog').text(json.spare.sector);
                         $('#txt_equipment', '#spare_edit_dialog').text(json.spare.equipment);
                         $('#manufacture_id', '#spare_edit_dialog').val(json.spare.manufacture_id);               
                         $('#spare_type_txt', '#spare_edit_dialog').text(json.spare.sparetype);
                         $('#part_number_txt', '#spare_edit_dialog').text(json.spare.part_number);                            
                         //herev qty = 0 bval remove the title of qty             
                         // $('#end_qty', '#spare_edit_dialog').val(json.spare.end_qty);
                         $('#spare_txt', '#spare_edit_dialog').text(json.spare.spare);            
                         //spare_id-s 
                         $('#spare_id', spare_edit_dialog).val(json.spare.spare_id);              
                         get_expense_dtl(id,  $('#spare_txt', '#spare_edit_dialog').text(), "qty_wrap_edit")              
                         //console.log($("#qty_wrap, #spare_edit_dialog").find('table').length);
                         //if($('#qty_wrap', spare_edit_dialog).length)
                         spare_edit_dialog.dialog("open");                                               
                    }else
                       alert(spare+ ' алдаатай байна! Дахин оролдоно уу!');
                  }
              });
            }else{  
                // ямар нэг юм нэмээгүй тохиолдолд
               showMessage(json.message, 'error');
            }
         }  
     });           
    
          
    // $("#edit_"+id).hide();    
    $("#edit_income_btn").attr("disabled", true);
}