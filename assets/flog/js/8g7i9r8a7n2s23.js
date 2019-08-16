$(function(){
   var Tag_datas = [];
   var log_id = $('#id').val(); 
   var basic_node;
   var basic_id;
   $('.module').each(function(index){     
        // basic = $(this);
        // basic_id = $(this).attr("id");
        module = $(this);
        module_id = $(this).attr("id");
        
        console.log("basic:"+module_id);
        $('.node').each(function( index ) {
         // herev basic node dotor selected node bval 
         // change color red mark as selected
         //console.log("node"+$(this).val())
             if(module_id==$(this).val()){
                 module.addClass('failure');
             }
        });
   });

   //EDIT PAGE HERE
    var e_form = $("#edit_form");  

  if (sessionStorage.getItem("created_dt")) {
        // Restore the contents of the text field
         var session_ct_dt = sessionStorage.getItem("created_dt");
        $("#created_dt", e_form).val(session_ct_dt);
        sessionStorage.clear();
   }

  console.log('checksum'+sessionStorage.getItem("check_form_flag"));
   //log filter 
   $( document ).tooltip();  
    
    //filter section change    
    filter_event(e_form); 

   $( "#created_dt", e_form ).datetimepicker({
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });

    $( "#closed_dt", e_form ).datetimepicker({
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });
 

   //төхөөрөмжийг солиход
   $('#vw_loc_equip_id', e_form).change(function(){
      //console.log($( "#vw_loc_equip_id option:selected" ).val());
      // buh tag-g remove hiih 
      $('#myTags').tagit('removeAll');
      //call here ftree here :D      
      var data = {};
      equipment_id = $( "#vw_loc_equip_id option:selected", e_form ).val();            
      created_dt = $('#created_dt', e_form).val();
      var type_id = $('#type_id', e_form).val();
      var location_id = $('#location_id', e_form).val();
      var id = $('#id', e_form).val();
      //data['equipment_id'] = $( "#vw_loc_equip_id option:selected" ).val();            
      
      sessionStorage.setItem('created_dt', created_dt);       
//      $("#edit_form").append("<div id='check_form'><script>$(window).bind('beforeunload', function(){if (window.confirm('Do you really want to leave?')){ return true; }else return false; }); </script></div>");
      $( "#edit_form" ).submit();
    });

    // хэрэв гэмтлийн төрөл өөчлөгдөхөд тухайн гэмтлийн байршилийг filter Хийх хэрэгтэй
   $('#type_id', e_form).change(function(){
      // холбооны төхөөрөмжтэй байшлуудыг харуулна.
      // Харуулах байршил сонгох хэлбэрт байна 
      // Доорх table-с авна vw_section_equipment
      type_id = $( "#type_id option:selected", e_form ).val();  
      $.post( '/ecns/flog/index/filter_sec', {section_id:type_id}, function(newOption) {   
           var select = $('#location_id', e_form);
           if(select.prop) {
              var options = select.prop('options');
           }else {
              var options = select.attr('options');
           }
           $('option', select).remove();
           // equipment option remove
           $('option', '#vw_loc_equip_id', e_form).remove();
           $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);        
           });
      });
   });


   //button save clicked here
   $('#btn_save', e_form).click(function(){       
       // get data from form and 
       var data = {};    var node =[];       
       var inputs = $('input[type="text"], input[type="hidden"], select, input[type="radio"]:checked, textarea', '#edit_form');       
       
        $("input[class=node]", e_form).each(function() {
            node.push({                
                node_id: $(this).val()
            });
        });
        // then to get the JSON string
       var jsonString = JSON.stringify(node);
       //console.log('nodes:'+jsonString);
       inputs.each(function(){
          var el = $(this);          
             data[el.attr('name')] = el.val();             
       });
       if(data['closed_dt']>data['created_dt'])
            data['duration']=1;
        else data['duration']=0;

       data['nodes']=jsonString;
       $.ajax({
          type:     'POST',
          url:    '/ecns/flog/index/update/',
          data:   data,
          dataType: 'json', 
          success:  function(json){ 
             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                // close the dialog                
                showMessage(json.message, 'success');
                location.href='/ecns/flog/index';
               // show the success message               
             }else{  // ямар нэг юм нэмээгүй тохиолдолд
                 window.scrollTo(0, 100);
                // edit_form.scrollTop( 100 );
                $('p.feedback', e_form).removeClass('success, notify').addClass('error').html(json.message).show();
             }
          }
      });// send the data via AJAX to our controller    
   });


   var reason = $('#reason_id', e_form);   
   var reason_2 = $('#reason_log_2');
   var reason_1 = $('#reason_log_1');
   reason_2.hide();
   reason_1.hide();
   var _location_id = $('#location_id', e_form).val();            
   // haruulah heseg
   switch(reason.val()){
         case '5':         
            if(reason_2.is(':visible')) reason_2.hide();
            reason_1.show();
            break;
         case '6':
            if(reason_1.is(':visible')) reason_1.hide();
            reason_2.show();         
            break;
         default :
            if(reason_2.is(':visible')) reason_2.hide();
         break;
  }

   reason.change(function(){   
      switch(reason.val()){
         case '5'://busad gemtlees hamaarsan 
            if(reason_2.is(':visible')) reason_2.hide();            
            var c_dt =$('#created_dt', e_form).val();            
            var log_id =$('#id', e_form).val();            
            if(c_dt&&_location_id){               
               reason_1.show();               
               // send ajax create concat dropdown and show to chosen select
               $.post('/ecns/flog/index/jx_reason', {location_id: _location_id, created_dt:c_dt, action:'edit', id:log_id}, function(newOption){
                  var select = $('#parent_id', e_form);
                  if(select.prop)
                     var options = select.prop('options');                 
                  else
                     var options = select.attr('options');                 
                    $('option', select).remove();
                    $.each(newOption, function(val, text){
                        $('#parent_id', e_form).append("<option value='"+val+"'>"+text+"</option>");
                        $('#parent_id', e_form).trigger("chosen:updated");
                    });               
                });                
            }else{
              alert("Гэмтэл нээсэн цаг болон Байршлийг сонгох ёстой!");
              // $( "#reason_id option:selected", c_form).val(0);  
              $("#reason_id option", e_form).prop("selected", false);
            } 
            break;
         case '6':
            if(reason_1.is(':visible')) reason_1.hide();  
               reason_2.show();  
              // gadnii bguullagaas bolson bol end bichne                                          
              // end post hiij tuhain байршлаар авч харуулах хэрэгтэй                      
              $.post( '/ecns/flog/index/jx_fequip', {location_id:_location_id}, function(newOption) {
                var select = $('#equip_com_id', e_form);
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
              //is_reason flag-g 1 bolgoh yostoi                     
              break;
         default :
            if(reason_1.is(':visible')) reason_1.hide();  
            if(reason_2.is(':visible')) reason_2.hide();
         break;
      }
  });


   var status = $('#status', e_form).val();  
   var completion_id = $('#completion_id', e_form);
   if(status=='C'||status=='A'){
     $('#wrap_closed', e_form).hide();
   }
      
  
   $(".spare_fields", e_form).hide();
   // herev spare_fields hide

   var _spare = $("#spare_radio", e_form);      

   var fixing_field = $('.fixing_field', e_form);
   if(completion_id.val()==3){
      fixing_field.show();  
   }else{
      fixing_field.hide(); 
   }    

   // if completion type changed     
   $('#completion_id', e_form).change(function(){      
      switch($(this).val()){
        case '3': 
           // show the form 
           fixing_field.show();          
        break;
        default: 
           $('input[type="radio"][value="N"]', e_form).attr("checked", "checked");
           $('.spare_fields', e_form).hide();    
           fixing_field.hide();
        break;
      }
   });

   var is_spare = $('#is_spare:checked', e_form).val();
   if(is_spare=='Y'){
     fixing_field.show();
     $(".spare_fields", e_form).show();
   }

    _spare.change(function(){      
       if($('#is_spare:checked', e_form).val()=='Y') // SHOW ALL FORM
           $(".spare_fields", e_form).show();
       else{ //No bol clear all value of sparetype_id
           if($(".spare_fields", e_form).is(':visible')) $(".spare_fields", e_form).hide();           
           $('#sparetype_id').val(0);
           $('#spare').val(null);
       }      
    });

   $('#category_id', e_form).change(function(){
      // холбооны төхөөрөмжтэй байшлуудыг харуулна.
      // Харуулах байршил сонгох хэлбэрт байна 
      // Доорх table-с авна vw_section_equipment
      cat_id = $( "#category_id option:selected",e_form).val();  

      $.post( '/ecns/flog/index/filter_sec', {category_id:cat_id}, function(newOption) {   
           var select = $('#location_id', e_form);
           if(select.prop) {
              var options = select.prop('options');
           }else {
              var options = select.attr('options');
           }
           $('option', select).remove();
           // equipment option remove
           $('option', '#vw_loc_equip_id', e_form).remove();
           $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);        
           });
      });
   });

});

//filter by this equipments
function filter_post(form_name, target_id, target_field, target){
    var c_id = $('#category_id', form_name).val(); 
    $.post( '/ecns/flog/index/input_jx', {id:target_id, field:target_field, cat_id:c_id, table:target}, function(newOption) {   
           var select = $('#'+target+'_id', form_name);
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
}
 
function filter_event(_form){
    //when cliked section call sector and equipment    
    $("#location_id", _form).change(function() {
       //section _id
       var _id = $(this).val();       
       //filter_post 
       filter_post(_form, _id, 'equipment_id', 'vw_loc_equip');
       //location change        
    });  
}