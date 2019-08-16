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

    // мөнгөний формат
    $("#amt").maskMoney({thousands:',', decimal:'.', allowZero:true, suffix: ' ₮'});
    
//    $("input name=['spare_amt[]']").maskMoney({thousands:',', decimal:'.', allowZero:true, suffix: ' ₮'});
    
  $('#income_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

  	var inc_form = $('#income_form');  
  	var spare = $('#spare_id', inc_form);
        var invoice_id = $("#invoice_id").val();

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
        var spare_array = $("input name=['spare']");
        var count = $("#count").val();

        var same = 0;
               
        //console.log(JSON.stringify(spare_array));
        for(var i=1; i<=count; i++){
//            console.log($("input[name='spare["+i+"][name]']").val());
            if($("input[name='spare["+i+"][name]']").val()===spare_name){
                same = 1;
            }            
        }                        
//        $("input[name^='card']").each(function () {
//            console.log(JSON.stringify($(this).val()));
//        });
	
	 if(same ===1){         
             //spare_list.indexOf(spare_name)!='-1'
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
			appent_txt ="<tr id='row_"+spare+"'><td>"+i+"</td><td><strong>"+spare_name+"</strong>. <b>P/N: </b><i>"+part_number.val()+"</i></td><td>"+amt+"</td><td>"+qty.val()+' '+measure.val()+"</td><td>"+total.toFixed(2)+"<a class='remove_it' data-id='"+spare+"' href='#' onclick = remove_it('"+spare+", "+invoice_id+"')> (Хасах) </a></td></tr>"+inputStr;				
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
		   //$('#_id_equipment').val('');	
		   //$('#_id_equipment').val('0');
		   // console.log(spare_list.toString());
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

//13:54 pm submit button 
//btn submit
// colect input form data
// validation data
// return data
// if success show the form of success

$('#edit_income_btn').click(function (){
    //combine data from form
    var data = {};   
    //collect spare
    var spare =[];
    
    var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#income_form');       
       
    $("input[class=node]").each(function() {
        spare.push({                
            spare_id: $(this).val()
        });
    });
    // then to get the JSON string
    var spareString = JSON.stringify(spare);
    // console.log('nodes:'+jsonString);
    inputs.each(function(){
      var el = $(this);          
         data[el.attr('name')] = el.val();             
    });
    
    // check income date-> income qty here don't missed up
    // 
    //check validation ajax
     $.ajax({
         type:    'POST',
         url:    base_url+'/wh_spare/index/jx_edit_income/',
         data:   data,
         dataType: 'json', 
         success:  function(json) {
             if(json.status=='success'){
                //console.log('successs here');
                //submit this form
                 $( "#income_form" ).submit();
             }else{
                 $('p.feedback', '#income_form').removeClass('success, notify').addClass('error').html(json.message).show();
             }
             
         }
      });    
});
//end funciton edit

//remove spare of list
//$('.remove_it').click(function(){
//   var id = $(this).attr("data-id");
//   var count = $('#count').val();   
//   $('#count').val(count-1);
//   console.log(count);
//   $('#row_'+id).remove();
//   //count тоолуур -1      
//});

//
////Edit input qty change then edit нийт үнэ
//$( "#spare_qty_1" ).change(function() {
//    console.log('its canged');
//    console.log("console"+$(this).closest('tr').attr('id'));    
//});
//
//
});

//тухайн мөрөөс бичлэгийг хасах
function remove_it(id, inv_id){      
   var count = $('#count').val();   
   $('#count').val(count-1);
   
   console.log(count+'ID'+id+"inv_id"+inv_id);
   $.ajax({
        type:     'POST',
        url:    base_url+'/wh_spare/index/jx_check_id/',
        data:   {spare_id : id, invoice_id:inv_id},
        dataType: 'json', 
        success:  function(json){ 
           if (json.status == "success") { 
              // амжилттай нэмсэн тохиолдолд энд үндсэн утгуудыг нэмэх болно.                       
              $('#row_'+id).remove();             
           }else{  
               // ямар нэг юм нэмээгүй тохиолдолд
              showMessage(json.message, 'error');
           }
        }  
    });   
    
}

//тухайн мөрөөс бичлэгийг Засах
function edit_it(cnt, id, inv_id){   
    var spare_qty_txt =  $('#spare_qty_txt_'+cnt);
    spare_qty_txt.hide();
    $("#spare_qty_"+cnt).show();   
    var spare_amt_txt =  $('#spare_amt_txt_'+cnt);
    spare_amt_txt.hide();
    $("#spare_amt_"+cnt).show();   
    //hide all table-row change input as editable    
    //console.log($('#row_'+id+' td:nth-child(2)').text()); 
    $('#spare_qty_'+cnt ).change(function() {
        $.ajax({
            type:     'POST',
            url:    base_url+'/wh_spare/index/jx_check_id/',
            data:   {spare_id : id, invoice_id:inv_id},
            dataType: 'json', 
            success:  function(json){ 
               if (json.status == 'success'){                    
                   var qty = parseFloat($('#spare_qty_'+cnt).val());
                    var amt =parseFloat($('#spare_amt_'+cnt).val());   
                    
                    var row_id = $(this).closest('tr').attr('id');
                    var total = qty*amt;   
                    console.log('total'+total);
                    $('#total_row_'+id).text(total);         
                    $('#spare_qty_txt_'+cnt).text(qty);   
                    $('input[id=\"qty\\['+cnt+'\\]\"]').val(qty);
               }else{  
                   // ямар нэг юм нэмээгүй тохиолдолд
                  showMessage(json.message, 'error');
               }
            }  
        });           
    });    
    
    $('#spare_amt_'+cnt ).change(function() {
//        console.log('its changed here!');
          $.ajax({
            type:     'POST',
            url:    base_url+'/wh_spare/index/jx_check_id/',
            data:   {spare_id : id, invoice_id:inv_id},
            dataType: 'json', 
            success:  function(json){ 
               if (json.status == 'success'){                                       
                   var qty = parseFloat($('#spare_qty_'+cnt).val());
                    var amt = $('#spare_amt_'+cnt).val();                       
                    var total = qty*amt;
                    $('#total_row_'+id).text(total);         
                    $('#spare_amt_txt_'+cnt).text(amt);                       
                    $('input[id=\"amt\\['+cnt+'\\]\"]').val(amt);
               }else{  
                   // ямар нэг юм нэмээгүй тохиолдолд
                  showMessage(json.message, 'error');
               }
            }  
        });        
    });
    
    $("#edit_"+id).hide();
    $('#save_'+id).show();
    $("#edit_income_btn").attr("disabled", true);
}

function save_it(cnt, id){
    //hide input 
    // add spare[i][value change
    // enable button  
    // шалгана хэрэв тухайн утгаар зарлага гарсан байвал хадгалаж болохгүй!!!
    // Гараагүй байвал болно!!!   
    $('#spare_amt_'+cnt).hide();
    $('#spare_amt_txt_'+cnt).show();    
    $('#spare_qty_'+cnt).hide();
    $('#spare_qty_txt_'+cnt).show();    
    //hide save
    $('#save_'+id).hide();
    $('#edit_'+id).show();
    $("#edit_income_btn").attr("disabled", false);                
}

function spare_qty_edit(){
    console.log('hi there');
};
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

