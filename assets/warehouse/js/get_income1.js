  $(function(){	
  	 $("#spare_form").validate({
  	 	errorLabelContainer: "#feedback",
  		wrapper: "li",
   			rules: {
					section_id: "required",
					sector_id: "required",
					equipment_id: "required",
					spare_id:"required",
					type:"required",
					part_number:"required",
					amt:{required:true						
					},
					qty:{
						required:true,
						number: true
					}
				},
				messages: {
					section_id: "Хэсгийг сонгоно уу!",
					sector_id: "Тасгийг сонгоно уу!",
					equipment_id: "Төхөөрөмжийг сонгоно уу!",						
					spare_id:  "Сэлбэгээс сонгоно уу!",
					type:"Төрөл утга шаардлагатай",
					part_number:"Парт № утга шаардлагатай",
					amt:"Үнэ утга өгөх шаардлагатай",
					qty:"Тоо утга өгөх шаардлагатай"
				},
				errorPlacement: function( error, element ) {
			error.insertAfter( element.parent() );
		}
	
	});	 
  	//console.log(form_1);
  	$("#income_form").validate(
			{
				rules: {
					income_no: "required",
					income_date: "required",
					supplier_id: "required",
					count: {
						required: true,
						minlength: 1
					}					
				},
				messages: {
					income_no: "Орлогийн дугаар оруулна уу!",
					income_date: "Орлогийн огноо оруулна уу!",
					supplier_id: "Нийлүүлэгчийг сонгоно уу!",						
					count: {
						required: "Сэлбэгээс сонгож орлогийн жагсаалтад оруулна уу!"
						
					}
					
				}
			});
  	
	 open = $("#add_dialog");
	 open.dialog({
	     autoOpen: false,
	     width: 550,       
	     resizable: false,    
	     modal: true,
	     // Хаах товч
	     close: function () {
	        $('p.feedback', open).html('').hide();
	        // clear & hide the feedback msg inside the form
	        $('input[type="text"], input[type="hidden"], select, textarea', open).val('');
	        // clear the input values on form    
	        $(this).dialog("close");
	     }
	 });

 	$(".chosen-select").chosen();
  	$(".chosen-select-deselect").chosen();

    $("#amt").maskMoney({thousands:',', decimal:'.', allowZero:true, suffix: ' ₮'});
    
  	$('#income_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

    var inc_form = $('#income_form');  
    var spare = $('#spare_id', inc_form);
    $("#count").val(0);

  // Хэрэв Хэсэг өөрчлөхөд тухайн хэсгийн filter-р хэсэгийг сонгох хэрэгтэй    
	$('.chosen-select', '#spare_form').on('change', function(evt, params) {
		var _id = $("#spare_id").chosen().val();	   
		 $.ajax({
		    type:    'POST',
		    url:    base_url+'/wh_spare/index/get/',
		    data:   {id:_id, target:'equipment'},
		    dataType: 'json', 
		    success:  function(json) {
		       if(json.status=='success'){
		     	   //var equip_id = json.equipment_id;
		      		// $("#_id_section option[value="+json.spare.section_id+"]").attr("selected", "selected");
		      		// $("#_id_sector option[value="+json.spare.sector_id+"]").attr("selected", "selected");	
		      		// $("#_id_equipment option[value="+json.spare.equipment_id+"]").attr("selected", "selected");
		           $('#part_number').val(json.spare.part_number);
		           $('#type').val(json.spare.sparetype);
		           $('#type_id').val(json.spare.sparetype_id);
		           $('#amt').val('');
		           $('#qty').val('');
		           $('#measure').val(json.spare.measure);		        	
		     	}
		    }
		});		
	});
   
  // Хэсгийг сонгоход тухайн тасаг харагдах хэсэг
  $('#_id_section').on('change', function(evt, params) {
	    $.post( base_url+'/wh_spare/index/get_equipby_id', {id:$(this).val(), flag:'yes'}, function(newOption) {  
	        var select = $('#_id_equipment');
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
	        var select = $('#_id_sector');
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
  $('#_id_sector').on('change', function(evt, params) {
	  	var section = $('#_id_section').val();
	    $.post( base_url+'/wh_spare/index/get_equipby_id', {id:$(this).val(), section_id:section, flag:'no'}, function(newOption) {  
	        var select = $('#_id_equipment');
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

	var spare_list = [];
   
   
// Нэмэх товч дарахад
var appent_txt = '';

$('#add_btn').mousedown(function(){	  		  	
	var spare = $("#spare_id").chosen().val();
	var spare_name = $('#spare_id_chosen .chosen-single').text() 	
	var qty = $('#qty');
	var amt = $('#amt').maskMoney('unmasked')[0]; 
	var type_id = $('#type_id');
	var part_number = $('#part_number');
	var measure = $('#measure');
	var equipment = $('#_id_equipment');
	var section = $('#_id_section');
	var sector = $('#_id_sector');
	var equipment = $('#_id_equipment');	
	var name = spare_list.indexOf("'"+spare_name+"'");
	
	 if(spare_list.indexOf(spare_name)!='-1'){
	 	alert(spare_name+'-г орлогийн жагсаалтад нэмэгдсэн тул дахин жагсаалтад нэмэх боломжгүй!');
	 }else{
		if($("#spare_form").valid()){
			//alert('hitehre');
			spare_list.push(spare_name);
	  		// тухайн баганыг Remove хийнэ			  		
	  		$("#remove_tr").remove();   
	  		//Тоолно хэдэн ширхэг орсныг
			if($("#count").val()>0){ i=$("#count").val(); }else i=0; i++;                 			
			$("#count").val(i);			

			var total;
			total = amt*qty.val();
			
			inputStr ="<input type='hidden' id='spare_id_"+i+"' name='spare_id' value='"+spare+"'>";
			inputStr +="<input type='hidden' id='qty_"+i+"' name='qty[]' value='"+qty.val()+"'>";
			inputStr +="<input type='hidden' id='amt_"+i+"' name='amt[]' value='"+amt+"'>";
			inputStr +="<input type='hidden' id='type_"+i+"' name='type_id[]' value='"+type_id.val()+"'>";
			//new spares heree
			inputStr +="<input type='hidden' id='spare["+i+"]' name='spare["+i+"][name]' value='"+spare_name+"'>";
			inputStr +="<input type='hidden' id='spare["+i+"]' name='spare["+i+"][id]' value='"+spare+"'>";
			inputStr +="<input type='hidden' id='order["+i+"]' name='spare["+i+"][order]' value='"+i+"'>";					
			inputStr +="<input type='hidden' id='equpment["+i+"]' name='spare["+i+"][equipment_id]' value='"+equipment.val()+"'>";
			inputStr +="<input type='hidden' id='sector_id["+i+"]' name='spare["+i+"][sector_id]' value='"+sector.val()+"'>";
			inputStr +="<input type='hidden' id='type_id["+i+"]' name='spare["+i+"][type_id]' value='"+type_id.val()+"'>";
			inputStr +="<input type='hidden' id='qty["+i+"]' name='spare["+i+"][qty]' value='"+qty.val()+"'>";
			inputStr +="<input type='hidden' id='amt["+i+"]' name='spare["+i+"][amt]' value='"+amt+"'>";
			appent_txt ="<tr id="+i+"><td>"+i+"</td><td><strong>"+spare_name+"</strong>. <b>P/N: </b><i>"+part_number.val()+"</i></td><td>"+amt+"</td><td>"+qty.val()+' '+measure.val()+"</td><td>"+total.toFixed(2)+"<a class='remove_it' data-id='"+spare+"' href='#'> (Хасах) </a></td></tr>"+inputStr;				
			 // after append clear all inputs	
			 // update spares
			$('#spare_id').trigger("chosen:updated");			
	  	}
	 }
	//check spare, partnumber, type selected?	    			
  }).mouseup(function(){  		
        if(appent_txt.length){
           $("#income_table").append(appent_txt);	
           //clear al functions
           $(".chosen-select").val('').trigger("chosen:updated");
           $('#qty').val('');
           $('#amt').val('');
           $('#type_id').val('');
           $('#part_number').val('');
           $('#measure').val('');		   
        } 		
  }); 
  
  	// click Шинэ сэлбэг товчийг дарахад
	$('#_id_equipment').on('change', function(){
	  	  //Төхөөрөмж солиход тухайн төхөөрөмжийн дээрх сэлбэгүүдийг дуудна.
	  	  //var equipment_id = $(this).val();
	  	  var id_section = $("#_id_section").val();
	  	  var id_sector = $("#_id_sector").val();

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

	// additional section added here III/16
	$( "#dialog" ).dialog({
		width: 600,
		resizable: false,    
       	modal: true,
		autoOpen:false,
		close: function () {        
           $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');          
           $('#txt_section').text('');
		   $('#txt_sector').text('');
		   $('#txt_equipment').text('');
           $(this).dialog("close");
       	}
	});

	$( "#form_supplier" ).dialog({
		width: 580,
		resizable: false,    
       	modal: true,
		autoOpen:false,
		close: function () {        
           $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');
           $(this).dialog("close");
           $('p.feedback', $(this)).html('').hide();        
       	}
	});

        $('#spare_id').change(function() {
  	   if($(this).val()==-1){ //шинэ сэлбэг нэмэх
     	   	new_spare();   
  	   }
	});
	// supplier change here
	$('#supplier_id').change(function() {
  	   if($(this).val()==-1){ //шинэ сэлбэг нэмэх
     	  // 1.call dialog
     	  //2 input vales
     	  //3 save dialog      	   	  
    	  // $('#section_id', '#add_spare_form').val($('#_id_equipment').val());
		  // $('#sector_id', '#add_spare_form').val($('#_id_sector').val());
		  // $('#equipment_id', '#add_spare_form').val($('#_id_equipment').val());
   		  $('#form_supplier').dialog('option', 'title', 'Нийлүүлэгч шинээр бүртгэх');
   		  $('#form_supplier').dialog({ 
    	  buttons: {
	         "Хадгалах": function () {      
	         	  var data = {};
	              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#form_supplier');
	              
	              inputs.each(function(){
	                var el = $(this);
	                data[el.attr('name')] = el.val();
	              });      

	              $.ajax({
	                 type:     'POST',
	                 url:    base_url+'/wh_spare/index/jx_add_supplier/',
	                 data:   data,
	                 dataType: 'json', 
	                 success:  function(json){ 
	                    if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
	                       //энд үндсэн утгуудыг нэмэх болно.                       
	                       showMessage(json.message, 'success');
	                      // show the success message                      
	                       $('#supplier_id').append('<option value="'+json.supplier_id+'">'+json.supplier+'</option>');  
	                       $('#supplier_id').trigger('chosen:updated');

	                      $('#form_supplier').dialog('close');                      	                       
	                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
	                  	   $('p.feedback', '#form_supplier').removeClass('success, notify').addClass('error').html(json.message).show();
	                    }
	                  }
	              });// send the data via AJAX to our controller             
	         },
	         "Цуцлах": function () {
	             $('#form_supplier').dialog("close");
	         }
     	  }
  		 }); 
   		 $('#form_supplier').dialog('open');
  	   }  	   
	});

  });    

function new_spare(){
	//add text from add income
	console.log('here is loading' +$('#_id_equipment').val());
	$('#txt_section').text($('#_id_section :selected').text());
	$('#txt_sector').text($('#_id_sector :selected').text());
	$('#txt_equipment').text($('#_id_equipment :selected').text());
	
	$('#section_id', '#add_spare_form').val($('#_id_section').val());
	$('#sector_id', '#add_spare_form').val($('#_id_sector').val());
	$('#equipment_id', '#add_spare_form').val($('#_id_equipment').val());

   $('#dialog').dialog('option', 'title', 'Шинэ сэлбэг бүртгэх');
   $('#dialog').dialog({ 
      buttons: {
         "Хадгалах": function () {      
         	  var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#add_spare_form');
              
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });      

              $.ajax({
                 type:     'POST',
                 url:    base_url+'/wh_spare/index/jx_add_spare/',
                 data:   data,
                 dataType: 'json', 
                 success:  function(json){ 
                    if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                       //энд үндсэн утгуудыг нэмэх болно.                       
                      showMessage(json.message, 'success');
                      // show the success message                      
                      $('#spare_id').append('<option value="'+json.spare_id+'">'+json.spare+'</option>');  
                      $('#spare_id').trigger('chosen:updated');
                      $('#dialog').dialog('close');                      
                       // close the dialog                      
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                  		showMessage(json.message, 'error');
                    }
                  }
              });// send the data via AJAX to our controller             
         },
         "Цуцлах": function () {
             $('#dialog').dialog("close");
         }
      }
   }); 
   $('#dialog').dialog('open');
}

    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

