
var open, edit, approve, close, view, quality, role, action_str, sel_str, file,  level, num;
window.open=false;
var form;


$(document).ready(function(){

   // $('#employee_id').chosen();

    $('#spare_id').on('change', function(e) {

         // console.log("value changed");

      $.post( base_url+'/flog/index/get_spare', {spare_id:$(this).val()}, function(json) {   

          $('#part_serial').val(json.part_number);
          
          $('#part_number', '#edit_form').val(json.part_number);

          $('#spare').val(json.spare);
      
      });

    });

   role=$("#user_role").val();

   sel_str=set_select();

   var c_form = $("#create_form");   

   var form_filter = $("#log_filter");  

    // var ss_section_id =sessionStorage.getItem('section_id');

   // $('#section_id option[value="'+ss_section_id+'"]').attr('selected', true);
  
   filter_event(form_filter);     

   $('#start_dt').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

   $('#end_dt').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

   // form filter section_id change 
   $('#section_id', form_filter).change( function(){

       $('#gs_section option[value="'+$('#section_id option:selected', form_filter).text()+'"]').attr('selected', true);

   });

  //filter here
  $('#filterBtn', form_filter).click(function (){  

      sessionStorage.clear();

      //clear all values in top filters
     $('#gs_q_level').val('');
     $('#gs_section').val('');
     $('#gs_section').val('');
     $('#gs_log_num').val('');
     $('#gs_created_datetime').val('');
     $('#gs_location').val('');
     $('#gs_equipment').val('');
     $('#gs_defect').val('');
     $('#gs_closed_datetime').val('');
     $('#gs_duration_time').val('');
     $('#gs_completion').val('');

     // if filterbtn selected section should give to gs_section value!
     var sel_section = $('#section_id option:selected', form_filter).text();
     
     // console.log('section:'+$('#section_id option:selected', form_filter).text());
     $('#gs_section option[value="'+sel_section+'"]').attr('selected', true);
     // $('#section_id option[value="'+ss_section_id+'"]').attr('selected', true);

     // $('#gs_section').prop('disabled', 'disabled');

     sessionStorage.setItem('section_id', $('#section_id option:selected', form_filter).val());    
     
     var _data = {};
     
     var f_inputs = $('input[type="text"], input[type="hidden"], select', form_filter);
     f_inputs.each(function(){
        var el = $(this); 
        _data[el.attr('name')] = el.val();
     });
     
     if(_data['start_dt']||_data['end_dt']){
          //check data option
          if(_data['date_option']=='0'){           
             alert("Огнооны төрлийг сонгоно уу!");
             $( "#date_option" ).focus();             
          }else{
             jQuery('#grid').jqGrid('setGridParam', { url: base_url+'/flog/index/grid', page: 1, search:false, postData:{'filters': "", 'filterBtn':true, 'section_id': _data['section_id'], 'sector_id': _data['sector_id'],'equipment_id':_data['equipment_id'], 'log':_data['log'], 'date_option':_data['date_option'], 'start_dt':_data['start_dt'], 'end_dt':_data['end_dt']}}).trigger("reloadGrid");
          }
      }else     
        jQuery('#grid').jqGrid('setGridParam', {
           url: base_url + '/flog/index/grid',
           page: 1,
           search: false,
           postData: {
              'filters': "",
              'filterBtn': true,
              'section_id': _data['section_id'],
              'sector_id': _data['sector_id'],
              'equipment_id': _data['equipment_id'],
              'log': _data['log'],
              'date_option': _data['date_option'],
              'start_dt': _data['start_dt'],
              'end_dt': _data['end_dt']
           }
        }).trigger("reloadGrid");

   }); 

   // create log here   
  if (sessionStorage.getItem("created_dt")) {
        // Restore the contents of the text field
      var session_ct_dt = sessionStorage.getItem("created_dt");
      
      $("#created_dt" , c_form).val(session_ct_dt);
      
      sessionStorage.clear();
  }

   //log filter 
   $( document ).tooltip();

   $( "#created_dt" ,c_form).datetimepicker({
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });
   
   $('#reason_log_1', c_form).hide();
   $('#reason_log_2', c_form).hide();
   //filter location change here
   filter_location(c_form);  
   var c_location = $('#location_id', c_form);   

   // Хэрэв Гэмтлийн ангилал өөрчлөгдөхөд тухайн гэмтлийн байршилийг filter Хийх хэрэгтэй   
   $('#section_id', c_form).change(function(){
      // холбооны төхөөрөмжтэй байшлуудыг харуулна.
      // Харуулах байршил сонгох хэлбэрт байна 
      // Доорх table-с авна vw_section_equipment
      sec_id = $( "#section_id option:selected", c_form).val();  

   
      $.post( base_url+'/flog/index/filter_sec', {section_id:sec_id}, function(newOption) {   
         
         var select = $('#location_id', c_form);
         
         if(select.prop) {
            
            var options = select.prop('options');
         }else {
            
            var options = select.attr('options');
          }
         
         $('option', select).remove();
         // equipment option remove
         
         $('option', '#vw_loc_equip_id').remove();

         
         $.each(newOption, function(val, text) {
         
            options[options.length] = new Option(text, val);        
         
         });

         var my_options = $("#location_id option", c_form);

         my_options.sort(function (a, b) {
            if (a.text > b.text) return 1;
            else if (a.text < b.text) return -1;
            else return 0;
         });

         $('#location_id', c_form).empty().append(my_options).prop('selected', true);

         
      });

   
   });

   //тоног төхөөрөмж сонгоход change хийх хэрэгтэй
   $('#vw_loc_equip_id', c_form).change(function(){
      //console.log($( "#vw_loc_equip_id option:selected" ).val());
      //call here ftree here :D
      var data = {};
      equipment_id = $( "#vw_loc_equip_id option:selected", c_form ).val();            
      created_dt = $('#created_dt', c_form).val();
      var section_id = $('#section_id', c_form).val();
      var location_id = $('#location_id', c_form).val();
      //data['equipment_id'] = $( "#vw_loc_equip_id option:selected" ).val();                  
      sessionStorage.setItem('created_dt', created_dt);                 
      sessionStorage.setItem('change_form_flag', 'false');                 
      // Cookies.set('change_form_flag', 'false'); // => 'value'     

      location.href=base_url+'/flog/index/create_form/?equipment_id='+equipment_id+'&section_id='+section_id+'&location_id='+location_id+'&selected=y';
    });

   if($('.overflow').length){
      var equip = $("#vw_loc_equip_id option:selected").text();
      if(!$('.tree').length){
          $('.overflow').append('<p class="error">Алдааны мод олдсонгүй! ['+equip+'] төхөөөрөмж дээр Алдааны мод үүсгээгүй байна! \n Тухайн тоног төхөөрөмж хариуцсан системийн инженерт мэдэгдэнэ үү! ')
      } 
   }
   
   //button submit
   $('#btn_save', c_form).click(function(){       
       // get data from form and 
       var data = {};    var node =[];
       
       var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#create_form');       
       
        $("input[class=node]").each(function() {
            node.push({                
                node_id: $(this).val()
            });
        });
        // then to get the JSON string
       var jsonString = JSON.stringify(node);
       // console.log('nodes:'+jsonString);
       inputs.each(function(){
          var el = $(this);          
             data[el.attr('name')] = el.val();             
       });
       
       reset(data['equipment_id']);

       data['nodes']=jsonString;
       $.ajax({
          type:     'POST',
          url:    base_url+'/flog/index/add/',
          data:   data,
          dataType: 'json', 
          async: false,
          success:  function(json){ 
             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                // close the dialog                                                
                showMessage(json.message, 'success');
                // amjilttai bolson tohioldold ene heseg uruu shidne
               location.href=base_url+'/flog/index';
               // show the success message               
             }else{  // ямар нэг юм нэмээгүй тохиолдолд
                // jump to the top                
                //$("#containerDiv").animate({ scrollTop: 0 }, "fast");
                 $("#container").animate({   scrollTop: 0
                }, 400);
                 
                $('p.feedback', c_form).removeClass('success, notify').addClass('error').html(json.message).show();
             }
          }
      });// send the data via AJAX to our controller    
   });

   //Хэрэв Гэмтлээс хамаарсан id 5
   $("#reason_id", c_form).change(function(){
    // console.log('here is loading');
      var reason_id = $( "#reason_id option:selected", c_form).val();  
      var reason = $( "#reason_id option:selected", c_form).text();  
      // Validate hiih heregtei tuhain bairshiliig sonogoson esehiig
      switch(reason_id){        
        //Гадны байгууллагаас хамаарсан бол
        case '6':
          if($('#reason_log_1', c_form).is(':visible'))  $('#reason_log_1', c_form).hide();          
          $('#reason_log_2', c_form).show();
          // end post hiij tuhain байршлаар авч харуулах хэрэгтэй        
          var c_location_id = $('#location_id', c_form).val();
          $.post( base_url+'/flog/index/jx_fequip', {location_id:c_location_id}, function(newOption) {              
            var select = $('#equip_com_id');
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
          $('#is_reason').val(6);
        break;

        case '5': //Бусдаас хамааралтай гэмтэл бол           
            if($('#reason_log_2', c_form).is(':visible'))  $('#reason_log_2', c_form).hide();
            var c_dt =$('#created_dt', c_form).val();
            var c_location_id = $('#location_id', c_form).val();            
            if(c_dt&&c_location_id){               
               $('#reason_log_1', c_form).show(); 
               // send ajax create concat dropdown and show to chosen select
               $.post(base_url+'/flog/index/jx_reason', {location_id: c_location_id, created_dt:c_dt, action:'create', id:0}, function(newOption){
                  var select = $('#parent_id');
                  if(select.prop)
                     var options = select.prop('options');                 
                  else
                     var options = select.attr('options');                 
                    $('option', select).remove();
                    $.each(newOption, function(val, text){
                        $('#parent_id').append("<option value='"+val+"'>"+text+"</option>");                    
                        $('#parent_id').trigger("chosen:updated");
                    });               
                });
                $('#is_reason').val(5);
            }else{
              alert("Гэмтэл нээсэн цаг болон Байршлийг сонгох ёстой!");
              // $( "#reason_id option:selected", c_form).val(0);  
              $("#reason_id option").prop("selected", false);
            }           
            
        break;

        default:                  
          if($('#reason_log_2', c_form).is(':visible'))  $('#reason_log_2', c_form).hide();
          if($('#reason_log_1', c_form).is(':visible')) $('#reason_log_1', c_form).hide(); 
          $('#is_reason', c_form).val(0);
          break;
      }

      $('#reason_title').text(reason);
   });

   // create form hidee comment field
   $('#comment_field', c_form).hide();

   $("#set_comment", c_form).click(function(){
      // close comment trigger
      $('#trigger_comment', c_form).hide();
      // show comment
      $('#comment_field', c_form).show();

   });
   
   //хэрэв бусдаас хамарасан бол id 7
   // бусад байгууллагаас хамаарсан төхөөрөмж сонговол 
   // байршил болон төхөөрөмжөөр компанийг сонгоно
   // create log ends here

   // close form START HERE
   f_close = $('#close_form');

   $( "#closed_dt" , f_close).datetimepicker({
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });

   //Шалтгаануудаар сонгож тухайн байршлийг сонгох!
   var f_reason = $('#reason_id', f_close);   
   var f_reason_2 = $('#reason_log_2', f_close);
   var f_reason_1 = $('#reason_log_1', f_close);
   f_reason_2.hide();
   f_reason_1.hide();
   
   var f_location_id = $('#location_id', f_close).val();            
   // haruulah heseg
   switch(f_reason.val()){
         case '5':         
            if(f_reason_2.is(':visible')) f_reason_2.hide();
            f_reason_1.show();
            break;
         case '6':
            if(f_reason_1.is(':visible')) f_reason_1.hide();
            f_reason_2.show();         
            break;
         default :
            if(f_reason_2.is(':visible')) f_reason_2.hide();
         break;
  }

  //Хаах хэсгийн шалтгаан өөрчлөхөд
   f_reason.change(function(){   

      // $("#reason_id option", f_close).prop("selected", true); 

      // console.log('selected'+$(this).val());

      switch(f_reason.val()){
         case '5'://busad gemtlees hamaarsan 
            if(f_reason_2.is(':visible')) f_reason_2.hide();            

            var c_dt =$('#created_dt', f_close).val(); 
            var log_id =$("input[name='log_id']", f_close).val();            
            // console.log("created_date: "+log_id);

            if(c_dt&&f_location_id){               
               f_reason_1.show();               
               // send ajax create concat dropdown and show to chosen select
               $.post(base_url+'/flog/index/jx_reason', {location_id: f_location_id, created_dt:c_dt, action:'close', id:log_id}, function(newOption){
                  var select = $('#parent_id', f_close);
                  if(select.prop)
                     var options = select.prop('options');                 
                  else
                     var options = select.attr('options');                 
                    $('option', select).remove();
                    $.each(newOption, function(val, text){
                        $('#parent_id', f_close).append("<option value='"+val+"'>"+text+"</option>");
                        $('#parent_id', f_close).trigger("chosen:updated");
                    });               
                });                
            }else{
              alert("Гэмтэл нээсэн цаг болон Байршлийг сонгох ёстой!");
              // $( "#reason_id option:selected", c_form).val(0);  
              $("#reason_id option", f_close).prop("selected", false);
            } 
            break;

         case '6':
            if(reason_1.is(':visible')) reason_1.hide();  
               reason_2.show();  
              // gadnii bguullagaas bolson bol end bichne                                          
              // end post hiij tuhain байршлаар авч харуулах хэрэгтэй                      
              $.post( base_url+'/flog/index/jx_fequip', {location_id:_location_id}, function(newOption) {
                var select = $('#equip_com_id', f_close);
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
            if(f_reason_1.is(':visible')) f_reason_1.hide();  
            if(f_reason_2.is(':visible')) f_reason_2.hide();
         break;
      }
  });


 // Засвар. IX/28 засварласан байдал 7 бол 
 // Сэлбэгийн төрөл, Сэлбэгийн нэр, сериал дугаарыг харуулна!
 // Сэлбэгийн програмаас дуудах эсэх!
// close dialgo deerh сэлбэгийн field-дийг хаах

  var close_fixing_field = $('.fixing_field', f_close);
   close_fixing_field.hide();      
   // if completion type changed     
   $('#completion_id', f_close).change(function(){
      // console.log('here is loading'+$(this).val());
      switch($(this).val()){
        case '7': 
           // show the form            
          // close_fixing_field.show();    
           $(".spare_fields", f_close).show();      
        break;
        default: 
          // close_fixing_field.hide();    
            if($(".spare_fields", f_close).is(':visible')) 
                $(".spare_fields", f_close).hide();          
        break;
      }
   });


   // Хаахад ашиглах зүйлс!!!   
   $(".spare_fields", f_close).hide();

   var spare_close = $("#is_spare", f_close);   
   
   // console.log('closeing form: '+JSON.stringify(_spare));
    spare_close.change(function(){
         var is_spare = $('#is_spare:checked', f_close).val();
         if(is_spare=='Y') // SHOW ALL FORM
            $(".spare_fields", f_close).show();
         else{              
            if($(".spare_fields", f_close).is(':visible')) 
                $(".spare_fields", f_close).hide();               
          }
    });

   //хаасан үед
   $('#btn_save', f_close).click(function(){       
       // Тодорхойгүй болон 
       // basic, undevelop-d-с өөр failure сонгогдсон эсэхийг мэдэх хэрэгтэй.

       // get data from form and 
       var data = {};    
       var node =[];       
       var inputs = $('input[type="text"], input[type="hidden"],input[type="radio"]:checked, select, textarea', f_close);                 
        $("input[class=node]").each(function() {
            node.push({                
                node_id: $(this).val()
            });
        });
       // odoo hereggui then to get the JSON string as node
       var jsonString = JSON.stringify(node);       
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
          url:    base_url+'/flog/index/close/',
          data:   data,
          dataType: 'json', 
          success:  function(json){ 
             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                // close the dialog                
                showMessage(json.message, 'success');

                location.href=base_url+'/flog/index';               
                
             }else{  // ямар нэг юм нэмээгүй тохиолдолд                
                $('p.feedback', f_close).removeClass('success, notify').addClass('error').html(json.message).show();
                $("#container").animate({   scrollTop: 0
                }, 400);
             }
          }
       });
       // send the data via AJAX to our controller    

   }); 
   // close END HEREhidehidehidehide

   edit = $('#edit_dialog');
   approve = $('#approve_dialog');
   approve.dialog({
       autoOpen: false,
       width:570,
       resizable:false,    
       modal: true,
       close: function () {
          $('p.feedback', approve).removeClass('error').html('').hide();
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select', approve).val('');
          // clear the input values on form    
          $(this).dialog("close");          
       }
   });

   //view dialog
   view=$('#view_dialog');
   $('#equip_comp', view).hide();  
   if($('#equp_comp', view).css("display")!=='none') $('#equip_comp', view).hide();    

   view.dialog({
       autoOpen: false,
       width: 580,       
       resizable: false,    
       modal: true,
       // Хаах товч
       close: function () {      
          if($('#equp_comp', view).css("display")!=='none') $('#equip_comp', view).hide();    
          $('#equip_com_id', view).text('');
          $('input[type="text"], input[type="hidden"], select, radio, textarea', view).val('');
          $(this).dialog("close");
       }
   });

  file = $('#file_dialog');
   file.dialog({
     autoOpen: false,
       width: 570,       
       resizable: false,    
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();          

          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');          

          $('#file_link', file).empty();
          
          if($('#cert_file', file).css('display', 'none')){

            $('#cert_file', file).show();

          }

          $(this).dialog("close");
       }
   });

   quality=$('#quality_dialog');
   quality.dialog({
     autoOpen: false,
       width: 570,       
       resizable: false,    
       modal: true,
       close: function () {
          
          $('p.feedback', $(this)).html('').hide();          
          
          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');         

          $('#wp_require').hide();

          $(this).dialog("close");
       }
   });


   //hide require wrapper file
   $('#wp_require').hide();
 

   $('#level', quality).change(function(){

       level= $(this).val(); 

      if(num){

         var q_val = num+$(this).val();
       
         var quality =  [ '5C', '4C', '3C', '5D', '4D', '3D','2D', '1D', '5E', '4E', '3E', '2E', '1E' ];

         console.log('hi its show the file asking '+q_val);

         //herev utgatai bval shuud uzuul       
         if($.inArray(q_val, quality)>-1){

             // if($('#wp_require', quality).css('display') =='none'){

            //    $('#wp_require', quality).show();

            // }

             $('#wp_require').show();

         }else $('#wp_require').hide(); 

      }


   });

   

   //level change
   // check num if its fit all of this

   $('#num', quality).change(function(){

    //5A, 5B, 5C, 5D, 5E, 4A, 4B, 4C, 3A, 3B, 3C, 2A, 2B

    //4d, 4e, 3d, 3e, 2c, 2d, 2e, 
      num = $(this).val(); 

     if(level){

       var q_val = $(this).val()+level;
       
      var quality =  [ '5C', '4C', '3C', '5D', '4D', '3D','2D', '1D', '5E', '4E', '3E', '2E', '1E' ];


       //herev utgatai bval shuud uzuul       
       if($.inArray(q_val, quality)>-1){

          // check wp_require if true then show
          // if($('#wp_require', quality).css('display')=='none'){

          //      $('#wp_require', quality).show();
          // }

           $('#wp_require').show();

          // console.log('hi its show the file asking ');

       }else

          $('#wp_require').hide();

     }


     
   });

   
   // call grid
   if ( $( "#main_wrap" ).length ) {
     jqgrid();       

   }
   // button binding
   button_bind();
   $("#show").on("click", function () {
      $("#open_dialog").dialog("open");
   });    

    $( "#c_closed_dt").datetimepicker({
        dateFormat: 'yy-mm-dd',      
        changeMonth: true,
        showOtherMonths: true,
        showWeek: true,
        opened:   false
     });

    $('.spare_fields', view).hide();

    // edit function data here
   var Tag_datas = [];
   var log_id = $('#id').val(); 
   var basic_node;
   var basic_id;
   var flag = 1;
   $('.module').each(function(index){     
        // basic = $(this);
        // basic_id = $(this).attr("id");
        module = $(this);
        module_id = $(this).attr("id");
        
        // console.log("basic:"+module_id);
        if(flag==1){
          console.log("callded: module");
          flag=0;
        }

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

     
   $( document ).tooltip();      
   //Edit change хийхэд 
   $('#section_id', e_form).change(function(){     
      sec_id = $( "#section_id option:selected", e_form).val();  

      $.post(base_url+'/flog/index/filter_sec', {section_id:sec_id}, function(newOption) {   
           var select = $('#location_id', e_form);
           if(select.prop) {
              var options = select.prop('options');
           }else {
              var options = select.attr('options');
           }
           $('option', select).remove();
           // equipment option remove
           $('option', '#vw_loc_equip_id').remove();
           $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);        
           });
      });
   });
   
     //when cliked section call sector and equipment    
    $("#location_id", e_form).change(function() {
       //section _id
       var _id = $(this).val();       
       //filter_post 
       filter_post(e_form, _id, 'equipment_id', 'vw_loc_equip');
       //location change        
    }); 
    
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
      //$('#myTags').tagit('removeAll');
      //TODO call here ftree here :D      
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
      $.post( base_url+'/flog/index/filter_sec', {section_id:type_id}, function(newOption) {   
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
          url:    base_url+'/flog/index/update/',
          data:   data,
          dataType: 'json', 
          success:  function(json){ 
             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                // close the dialog                

                showMessage(json.message, 'success');

                // TODO:remove
                location.href=base_url+'/flog/index';
   
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
               $.post(base_url+'/flog/index/jx_reason', {location_id: _location_id, created_dt:c_dt, action:'edit', id:log_id}, function(newOption){
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
              $.post(base_url+'/flog/index/jx_fequip', {location_id:_location_id}, function(newOption) {
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
  
   // herev spare_fields hide
   var _spare = $("#spare_radio", e_form);

   var fixing_field = $('.fixing_field', e_form);

   if(completion_id.val()==7){
     
      fixing_field.show();  

      $(".spare_fields", e_form).show();
   }else{

      fixing_field.hide(); 

      $(".spare_fields", e_form).hide();
   }    

   // if completion type changed     
   $('#completion_id', e_form).change(function(){      

      switch($(this).val()){

        case '7': 
           
           fixing_field.show();   

           $(".spare_fields", e_form).show();       

           $("#spare_id").val(0);

           $("#spare_id").trigger("chosen:updated");

           $("#sparetype_id").val(0);

           $("#part_number").val(0);

           $("#qty").val(0);

        break;

        default: 
           
           $('input[type="radio"][value="N"]', e_form).attr("checked", "checked");
           
           $('.spare_fields', e_form).hide();    
           
           fixing_field.hide();
           
           $(".spare_fields", e_form).hide();  
        break;
      }

   });

   // removed by id
   // var is_spare = $('#is_spare:checked', e_form).val();
   // if(is_spare=='Y'){
   //   fixing_field.show();
   //   $(".spare_fields", e_form).show();
   // }

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

      $.post( base_url+'/flog/index/filter_sec', {category_id:cat_id}, function(newOption) {   
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

   // edit ends here
   $('#cert_file', file).change(function (){         
      var uploadfile = new FormData($("#file_dialog")[0]);        
      $.ajax({
          url:   base_url+'/flog/index/upload/',
          type:    'POST',                               
          data:   uploadfile,                                              
          processData: false,  // tell jQuery not to process the data
          contentType: false,
          success:  function(json){                               
            //console.log('json'+JSON.stringify(json));
            if (json.status == "success") {      
            // if ajax return success                 
            // show the success message                 
             feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!');              
               // hide file 
              if(json.name){
                $("input[type='file", file).val('').hide();                    
                  if(!$('#_file', file).lenght)
                     $("#_file", file).append("<span id='file_link'><a href='"+base_url+"/download/log_files/"+json.name+"' download style='color:blue'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.log_id+", \""+json.name+"\")'>Устгах</a>)</span>");                      
                    //onclick='_file("+json.log_id+")'
               }                                              
            }                  
            else{  // ямар нэг юм нэмээгүй тохиолдолд                                              
              feeds('error', json.message);
              $("input[type='file", file).val('');
            }
          }
      }); 
   });

}); 
// jquery ends here

function feeds(css_class, msg){
  if($('p.feedback', file).hasClass('error')) $('p.feedback', file).removeClass('error');  
  if($('p.feedback', file).hasClass('success')) $('p.feedback', file).removeClass('success');

  $('p.feedback', file).addClass(css_class).html(msg).show();                        
  $('p.feedback', file).stop().fadeIn('fast', function(){
       $('p.feedback', file).delay(5000).fadeOut();
      // fade out again after 3 seconds  
  });
}

// EDIT FUNCTIONS HERE
//filter by this equipments
function filter_post(form_name, target_id, target_field, target){
    //used in edit form change location
    var section_id = $('#section_id', form_name).val(); 

    $.post( base_url+'/flog/index/input_jx', {id:target_id, field:target_field, cat_id:section_id, table:target}, function(newOption) {   
         
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

           // sorging select
          var my_options = $('#'+target+'_id option', form_name);

           my_options.sort(function (a, b) {
               if (a.text > b.text) return 1;
               else if (a.text < b.text) return -1;
               else return 0;
            });

          $('#' + target +'_id', form_name).empty().append(my_options).prop('selected', true);

    });
}

// filter nemelted zoriulsan filteruud
function filter_front(form_name, target_id, target_field, target){
    //var c_id = $('#category_id', form_name).val(); 
    $.post(base_url+'/flog/index/jx_front_filter', {id:target_id, field:target_field, table:target}, function(newOption) {   
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
           options[options.length] = new Option(text, val);        
        });
    });
}
 
function filter_event(_form){
    //section filter
     $("#section_id", _form).change(function() {
       //section _id
       var sec_id = $(this).val();
       filter_front(_form, sec_id, 'section_id', 'sector');
       //filter_post 
       filter_front(_form, sec_id, 'section_id', 'equipment');
    });
    
    $("#sector_id", _form).change(function() {
       //sector _id
       var filter_id = $(this).val();           
       // section select 
       $('#section_id option[value='+(~~(filter_id/10))+']', _form).attr('selected', 'selected');
       //filter_post(form_filter, filter_id, 'sector_id', 'section');

       //filter_post 
       filter_front(_form, filter_id, 'sector_id', 'equipment');
    }); 
    
    //when cliked section call sector and equipment    
    $("#location_id", _form).change(function() {
       //section _id
       var _id = $(this).val();       
       //filter_post 
       filter_post(_form, _id, 'equipment_id', 'vw_loc_equip');
       //location change        
    });  
}


function set_select(){   
  var str="", cnt=0;
   $("#section_id option").each(function(){
      str =$(this).text()+":"+$(this).text(); cnt++;
   });     
   //if(cnt>1)
   console.log("count"+cnt);

    str = ':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;ЧОУНБ (NUBIA):ЧОУНБ(NUBIA)';
   return str;
}

// create log fn here
//тухайн тоног төхөөрөмжийн filter-г хийнэ
function filter_equipment(form_name, target_id, target_field, target){
    // тухайн төрлөөр бас давхар шүүх хэрэгтэй байна.
    // Холбооны бол холбооны хэсгийн гэмтлийг шүүнэ
    // Тоног төхөөрөмжөөр шүүнэ
    var cat_id = $('#section_id', form_name).val();    
    $.post(base_url+'/flog/index/input_jx', {id:target_id, field:target_field, cat_id:cat_id, table:target}, function(newOption) {   
           var select = $('#'+target+'_id');
           if(select.prop) {
              var options = select.prop('options');
           }else {
              var options = select.attr('options');
           }
           $('option', select).remove();
           $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);        
           });

         // sorging select
         var my_options = $('#' + target + '_id option', form_name);

         my_options.sort(function (a, b) {
            if (a.text > b.text) return 1;
            else if (a.text < b.text) return -1;
            else return 0;
         });

         $('#' + target + '_id', form_name).empty().append(my_options).prop('selected', true);
    });
}
 
 // байршил өөрчлөхөд
function filter_location(_form){
    //when cliked section call sector and equipment    
    $("#location_id", _form).change(function() {
       //section _id
       var _id = $(this).val();       
       //filter_post 
       filter_equipment(_form, _id, 'equipment_id', 'vw_loc_equip');
       //location change        
    });  
}
// create log fn end

function reset(id){
  //alert('reset called: '+id);         
  $.ajax({          
  type:    'POST',
  url:    base_url+'/ftree/jx_reset/',
  data:   {equipment_id:id},
    dataType: 'json',
    success: function(json){
       if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
        console.log('reset success '+id);
       }else
        console.log('reset: failed '+id);
      }                   
  });
 }

// grid start here
function jqgrid(){
    // console.log('base1:'+base_url);
    $("#grid").jqGrid({       
        url:base_url+'/flog/index/grid',
        datatype: 'xml', 
        mtype: 'GET', 
        colNames:['Зэрэглэл', 'Гэмтэл №',  'Хэсэг', 'Нээсэн / t', 'Байршил', 'Төхөөрөмж',  'Төрөл', 'Гэмсэн модуль, дэд хэсэг','Хаасан / t','Үргэлж/цаг','Шалтгаан','Гүйцэтгэл', 'Үйлдэл',  'Closed', 'equipment_id'], 
        colModel :[ 
            {name:'q_level', index:'q_level', width:25, align:'center' }, 
            {name:'log_num', index:'log_num', width:50, align:'center'}, 
            {name:'section', index:'section', width:80, stype:'select'},
            {name:'created_dt', index:'created_dt', width:80, searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
            {name:'location', index:'location', width:60, align:'center', formatter:view_link },                     
            {name:'equipment', index:'equipment', width:90, search:false}, // formatter:view_link
            {name:'log_type', index:'log_type', width:70,  formatter:view_link},
            {name:'node', index:'node', width:120, align:'left', formatter:view_link, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']} },    
            {name:'closed_dt', index:'closed_dt', width:80, align:'right', searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
            {name:'duration', index:'duration', width:50, align:'center'}, 
            {name:'reason', index:'reason', width:90, sortable:true, align:'center' },                                         
            {name:'completion', index:'completion', width:90, sortable:true, align:'center' },                                         
            {name:'act',index:'act',width:110, align:'center',sortable:false,formatter:log_action                   
            },
            {name:'status', index:'status', hidden:true, viewable:true},            
            {name:'equipment_id', hidden:true, viewable:true}// hidden:true,                 
            
            ], 
        pager: '#pager', 
        rowNum:20, 
        rowList:[10,20,30,40],                    
        sortname: 'log_id', 
        sortorder: "desc", 
        viewrecords: true, 
        gridview: true, 
        // imgpath: 'themes/basic/images', 
        caption: 'Гэмтэл дутагдал',
        autowidth:true,       
        height:500,
        width:'100%' ,
        editurl: 'server.php',        
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){ 
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));                            
              //console.log(JSON.stringify(rowData));
              switch(rowData.status){
                //create
                 case "C": 
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('warning');
                  break;
                  // active
                  case "A":
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('argent');
                  break;
                  // closed and not qualified
                  case "N":
                     trElement.removeClass('ui-widget-content');
                     trElement.addClass('warning');
                  break; 

                  //need qualify
                  case "Q":
                     trElement.removeClass('ui-widget-content');
                     trElement.addClass('qualify');
                  break;
                  //need file
                  case "F":
                     trElement.removeClass('ui-widget-content');
                     trElement.addClass('file');
                  break;                  
              }            
           }         
       }         
    }).navGrid("#pager",{edit:false,add:false,del:false, search:true});
    jQuery("#grid").jqGrid('filterToolbar',{searchOperators : true, stringResult: true, defaultSearch:'cn'});
  
    // jQuery("#grid").jqGrid('filterToolbar',{searchOperators : true, stringResult: true,searchOnEnter : false, beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }});
   
   // footerbar XLS файлыг 
//   $("#grid").jqGrid('navButtonAdd','#pager',{
//      caption:"XLS файл", 
//      onClickButton : function () { 
//          jQuery("#grid").jqGrid('excelExport',{"url":"export"});
//      }
//    });
}

function view_link(cellValue, options){
   return "<a href='#' onclick='init_view(" + options.rowId + ")' >"+cellValue+"</a>"; 
}

function log_action (cellvalue, options, rowObject) {     
   action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_none' onclick='init_view("+options.rowId+")' class='ui-icon ui-icon-extlink'></span></div></div>";
   var fields = $( "#action" ).serializeArray();
   
   
   
   $('input.action').each(function() {
     switch($(this).val()){
        case 'activate':                      
            action_str=action_str+"<div title='Зөвшөөрөл өгөх' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_approve' onclick='initApprove("+options.rowId+")' class='ui-icon ui-icon-unlocked'></span></div>";                      
        break;
        // case 'quality':                      
        //     action_str=action_str+"<div title='Эрсдэл үнэлэх' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_quality("+options.rowId+")' class='ui-icon ui-icon-check'></span></div></div>";
        // break;
        case 'close':
            action_str=action_str+"<div title='Хаах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_close("+options.rowId+")' class='ui-icon ui-icon-wrench'></span></div></div>";
        break;
        case 'edit':
            action_str=action_str+"<div style='margin-left:8px;'><div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><a href="+base_url+'/flog/index/edit/'+options.rowId+"><span class='ui-icon ui-icon-pencil'></span></a></div>";
        break;
        
        case 'delete':
            action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+options.rowId+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
        break;

        case 'quality':
            action_str=action_str+"<div title='Эрсдэл үнэлэх' style='float:left;cursor:pointer;' class='ui-pg-div'><span onclick='init_quality("+options.rowId+")' target ="+options.log_num+" class='ui-icon  ui-icon-check'></span></div></div>";    
        break;
        
        case 'file':
            action_str=action_str+"<div title='Тайлангийн файл хавсаргах' style='float:left;cursor:pointer;'><span onclick='init_file("+options.rowId+")' target ="+options.log_num+" class='ui-icon  ui-icon ui-icon-link'></span></div></div>";    
        break;

        case 'show_ftree':
            action_str=action_str+"<div title='Алдааны мод харах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_ftree("+options.rowId+")' target ="+options.log_num+" class='ui-icon  ui-icon-alert'></span></div></div>";    
        break;
        
        case 'attach': 
            action_str=action_str+"<div title='Файл шаардлагатай' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='attach("+options.rowId+")' class='ui-icon  ui-icon-tag'></span></div></div>";    
        break;
        
        
        

    }
   });
   return action_str;
}

// edit buttons here
//approve dialog
function initApprove(id){ 
   var post = { id: id }, state;  
   $.ajax({
      type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   post,
       dataType: 'json', 
       success:  function(json) {
          //alert(json);
          // log_num-г өгөх хэрэгтэй.
          $("#log_id", approve).val(json.log.log_id);
          $("#status", approve).val(json.log.status);
          $('#log_num', approve).val(json.log.log_num);   
          $('#category', approve).text(json.log.section);   
          //console.log('category;'+category)
          $('#equipment_id', approve).val(json.log.equipment_id);   
          if(json.log.log_num===null) $('#log_num_txt', approve).text('Зөвшөөрөх дарахад автоматаар олгогдоно.');
          else $('#log_num_txt', approve).text(json.log.log_num);                
          $('#section', approve).text(json.log.section);
          $('#created_dt', approve).text(json.log.created_dt);
          $('#createdby_id', approve).text(json.log.createdby);
          $('#location_id', approve).text(json.log.location);
          $('#equipment_id', approve).text(json.log.equipment);
          $('#log_type', approve).text(json.log.log_type);
          $('#node', approve).text(json.log.node);
          $('#reason', approve).text(json.log.reason);
          
          // approve hiihed herev 
          // if(json.log.inst){            
          //   $('#inst option[value='+json.log.inst+']', approve).attr('selected', 'selected');
          //   $('#level option[value='+json.log.level+']', approve).attr('selected', 'selected');
          //   $("#inst", approve).prop('disabled', true);
          //   $("#level", approve).prop('disabled', true);            
          // }
          if(json.log.status=='Y'||json.log.status=='N'){     
              $('#closed_dt', approve).text(json.log.closed_dt);
              $('#duration', approve).text(json.log.duration);           
              $('#closedby_id', approve).text(json.log.closedby);         
              $('#completion', approve).text(json.log.completion);
              $('#wrap_closed', approve).show();               
           }else{                  
               $('#wrap_closed', approve).hide();    
           }                        
          if(json.log.log_num) 
              title = '"'+json.log.log_num+'" дугаартай гэмтэл';
          else 
             title = 'Гэмтэл';   
         
          if(json.log.status=='A'||json.log.status=='Y'||json.log.status=='Q'||json.log.status=='F') state ='activated';
          else state='activate';
       }
    }).done(function(){
        // console.log('state'+state);
       _call_fn(state, title);
    });    
}

function approve_dialog(title){
   approve.dialog('option', 'title', title);
   approve.dialog({ 
      buttons: {
         "Зөвшөөрөх": function () {
             $('p.feedback', approve).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
                // logical function here
                var data = {};
                var inputs = $('input[type="text"], input[type="hidden"], select' , approve);
                
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });
                // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/flog/index/active/',
                    data:   data,
                    dataType: 'json', 
                    success:  function(json){ 
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                        approve.dialog("close");
                        // close the dialog                         
                        showMessage(json.message, 'success');
                        // show the success message
                        reload();
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', approve).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller 
         },
        "Хаах": function () {
            approve.dialog("close");
         }
      }
   }); 
   approve.dialog('open');   
}

 function button_bind(){
     //Хаах үйлдэл хийгдэхэд       
     $('#_close').click(function(){              
       var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
        if(!log_id){
           alert('[Хаах] гэмтлийг гэмтлийн жагсаалтаас сонгоно уу!');
        }else
           init_close(log_id);
     });

 //Зөвшөөрөх гэмтэл
     $('#_active').click(function(){              
       var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
        if(!log_id){
           alert('[Зөвшөөрөх] гэмтлийн жагсаалтаас гэмтлийг сонгоно уу!');
        }else
           initApprove(log_id);
     });
     // //Үнэлэх гэмтэл
      $('#quality').click(function(){              
        var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
        if(!log_id){
            alert('Эрсдэл үнэлэх гэмтлийг сонгоно уу!');
         }else
           init_quality(log_id);
     });
     // Устгах гэмтэл
     $('#_delete').click(function(){     
        var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');         
        if(!log_id){
           alert('[Устгах] гэмтлийг сонгоно уу!');
        }else
           _delete(log_id);
     }); 
      // Засах гэмтэл
     $('#_edit').click(function(){              
       var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
        if(!log_id){
           alert('[Засах] гэмтлийг гэмтлийн жагсаалтаас сонгоно уу! \n ');
        }else
           init_edit(log_id);
     });
 }


//Гэмтэл хаах энд эхлэнэ
function init_close(id){    
   var post = { id: id }, state;
   $.ajax({
       type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   post,
       dataType: 'json', 
       success:  function(json) {                  
       if(json.log.status=="N") state="closing"; //хаах хүсэлт илгээсэн
          else if(json.log.status=="Y"||json.log.status=="Q"||json.log.status=="F") state="closed"; //бүрэн хаагдсан 
          else if(json.log.status=="C") state="created" //нээх зөвшөөрөл өгөөгүй гэмтлийн бүртгэл дээр энэ үйлдэл боломжгүй!!!                    
          else state="close";  // хааж болно
       }
    }).done(function(){
        _call_fn(state, title=null, id);
       //console.log('here is loading'+state);
    });
}

function close_dialog(id){   
   location.href=base_url+'/flog/index/close_form/'+id;   
}
// Гэмтэл хаах дуусна

//call reload grid
function reload(){
   $("#grid").trigger("reloadGrid"); 
}

//delete
function _delete(log_id){  
   var data = { id: log_id }; 
   var rowData = $("#grid").getRowData(log_id);   
    // ask confirmation before delete 
    var confirm ;
    if(rowData.log_num!=='-')
        confirm = window.confirm("Та '"+rowData.log_num+"' дугаартай гэмтлийг устгахдаа итгэлтэй байна уу?");
    else confirm = window.confirm("Та [Хаах] үйлдэл хийгдээгүй гэмтлийг устгахдаа итгэлтэй байна уу?");

   if(confirm){
      $.ajax({
         type:    'POST',
         url:    base_url+'/flog/index/delete/',
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                  // close the dialog                         
                 showMessage(json.message, 'success');
                // show the success message
                reload();
           }
         }
       });
    }
}

function _ftree(log_id){
  //alert(log_id);
  var rowData = $("#grid").getRowData(log_id);   
  //alert(rowData.equipment_id);  
  window.location = '/ftree/tree/'+rowData.equipment_id;  
}

//function attach file
function attach(log_id){
   var post = { id: log_id }, state;
   $.ajax({
       type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   post,
       dataType: 'json', 
       success:  function(json) {                  
       if(json.log.status=="N") state="closing"; //хаах хүсэлт илгээсэн
          else if(json.log.status=="Y"||json.log.status=="Q") state="closed"; //бүрэн хаагдсан 
          else if(json.log.status=="F") state ="attached";
          else if(json.log.status=="C") state="created" //нээх зөвшөөрөл өгөөгүй гэмтлийн бүртгэл дээр энэ үйлдэл боломжгүй!!!                    
          else state="close";  // хааж болно
       }
    }).done(function(){       
       //attached-s бусад тохиолдолд файл хавсаргахыг шаардаж болно.        
       if(state=='attached'){
           if (confirm("Энэ гэмтэлд аль хэдийн файл шаардлагатай үйлдлийг хийсэн байна.\n Файл шаардах үйлдлийг цуцлах уу?") == true) {
              $.ajax({
                type:    'POST',
                url:    base_url+'/flog/index/_cancel/',
                data:   {id:log_id},
                dataType: 'json', 
                success:  function(json) {
                   if(json.success){
                     //then remove link
                     showMessage(json.message, 'success');          
                     reload();
                   }else{                    
                     showMessage(json.message, 'error');
                     reload();
                   }
                }
              });
           }
       }else if(state=="created"||state=="closing") alert('Гэмтлийг нээж байна! Хаасны дараа энэ үйлдэл боломжтой!');
       else if(state=='closed'){
            $.ajax({
               type:    'POST',
               url:    base_url+'/flog/index/_attach/',
               data:   {id:log_id},
               dataType: 'json', 
               success:  function(json) {
                  if(json.status=='success'){
                    //then remove link
                    showMessage(json.message, 'success');          
                    reload();
                  }else{                    
                    showMessage(json.message, 'error');
                    reload();
                  }
               }
             });
       }
    });
    
}

//function attach file
function cancel(log_id){
    alert('cancel loaded'+log_id);
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
   // add the class supplied`
   paragraph.html(message);
   // change the text inside
   paragraph.fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}

//init_view() view all data in log
function init_view(log_id){
   //alert('init_view called'+str);
   $('.fixing_field', '#view_dialog').hide();  
   $('#field_closed_comment', view).hide();
   $('#field_comment', view).hide();

    var data = { id: log_id };          
    $.ajax({
       type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          //утгуудыг авч edit_dialog уруу дамжуулна    
          $("#log_id", view).val(json.log.log_id);
          $('#log_num', view).val(json.log.log_num);                
          $('input[name=created_datetime]', view).val(json.log.created_dt);
          $('#createdby_id', view).text(json.log.createdby);
          $('#category', view).val(json.log.section);
          $('#location_id option[value='+json.log.location_id+']', view).attr('selected', 'selected');
          $('#equipment_id option[value='+json.log.equipment_id+']', view).attr('selected', 'selected');          
          $('#log_type_id option[value='+json.log.type_id+']', view).attr('selected', 'selected');
          $('#defect', view).val(json.log.node);          
          if(json.log.comment){             
             $('#field_comment', view).show();
             $('#comment', view).val(json.log.comment);               
          }          
          $('#reason_id option[value='+json.log.reason_id+']', view).attr('selected', 'selected');
           // console.log('log34343'+json.log.equip_comp);
          if(json.log.equip_com_id&&json.log.equip_com_id!==null){
                $('#equip_comp', view).show();
                $('#equip_com_id').text(json.log.equip_comp);
          }
          //herev closed baival utguudiig haruulna
          if(json.log.status=='Y'||json.log.status=='N'||json.log.status=='Q'||json.log.status=='F'){     
             $('#closed_datetime', view).val(json.log.closed_dt);
             $('#duration', view).val(json.log.duration);           
             $('#closedby_id', view).text(json.log.closedby); 
             $('#completion_id option[value='+json.log.completion_id+']', view).attr('selected', 'selected');             
             $('#wrap_closed', view).show();             
             $("input[name=is_spare][value=" + json.log.is_spare + "]").attr('checked', 'checked');


             if(json.log.is_spare=='Y'){// show spare_fields                  
               $('.spare_fields').show();               
               $('#sparetype_id option[value='+json.log.sparetype_id+']', view).attr('selected', 'selected');
               $('#spare').val(json.log.spare);
             }else{
                $('.spare_fields').hide();               
             }

             // 
            if(json.log.closed_comment){
               $('#field_closed_comment', view).show();
               $('#closed_comment', view).val(json.log.closed_comment);  
            }

          }else{                  
             $('#wrap_closed', view).hide();    
          }
          if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтэлийн дэлгэрэнгүй';
          else title = 'Гэмтлийн дэлгэрэнгүй';

           if(json.log.filename)
            $('#show_file', view).html("<span>Тайлангийн файл:</span>  <span id='file_link'><a href='"+base_url+"/download/log_files/"+json.log.filename+"' download style='color:blue'>"+json.log.filename+"</a></span>");                      
          else
             $('#show_file', view).html("<span>Тайлангийн файл:</span>  <span id='file_link'>Файл оруулаагүй байна!</span>");
       }
    }).done(function() {
       view.dialog('option', 'title', title);
       view.dialog({ 
          buttons: {             
             "Хаах": function () {
                 view.dialog("close");
             }
          }
       }); 
       view.dialog('open');
    });
}

//initial check function
function init_file(log_id){      
    var data = { id: log_id }, state; 
    $.ajax({
       type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          if(json.status=='success'){
             $("#log_id", file).val(json.log.log_id);
             $('#log_num', file).text(json.log.log_num);
             $('#section', file).text(json.log.section);
             $('#created_dt', file).text(json.log.created_dt);
             $('#createdby_id', file).text(json.log.createdby);
             $('#location_id', file).text(json.log.location);
             $('#equipment_id', file).text(json.log.equipment);
             $('#node', file).text(json.log.node);
             $('#log_type', file).text(json.log.log_type);
             $('#reason', file).text(json.log.reason);               

          if(json.log.status=='Y'||json.log.status=='Q'||json.log.status=='N'||json.log.status=='F'){     
             $('#closed_dt', file).text(json.log.closed_dt);
             $('#duration', file).text(json.log.duration);           
             $('#closedby_id', file).text(json.log.closedby);         
             $("#completion", file).text(json.log.completion);
             $("#level", file).text(json.log.level);
             $("#num", file).text(json.log.num);
             $('#wrap_closed', file).show();
          }else{                  
             $('#wrap_closed', file).hide();    
          }
          if(json.log.status=='C'||json.log.status=='Q'||json.log.status=='A'||json.log.status=='N') state='nofile';
          else if(json.log.status=='Y') state='hasfile';
          else if(json.log.status=='F') state='file';

          }else
            alert('Ямар нэг зүйл буруу учраас алдаа гарлаа!!!');
          
        if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтлийн эрсдэл үнэлэх';
        else title = 'Гэмтлийн эрсдэл үнэлэх';
     }
    }).done(function() {      
        console.log("STATE"+state);
       _call_fn(state, title);
          //alert('Бүрэн хаасан гэмтлийг сонгож "Эрсдлийг үнэлэнэ" үү!');
          //showMessage('Бүрэн хаасан гэмтлийг сонгож "Эрсдлийг үнэлэнэ" үү!', 'error');       
    });
 }


//initial check function
function init_quality(log_id){      
    var data = { id: log_id }, state; 
    $.ajax({
       type:    'POST',
       url:    base_url+'/flog/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          $("#log_id", quality).val(json.log.log_id);
          $('#log_num', quality).text(json.log.log_num);
          $('#section', quality).text(json.log.section);
          $('#created_dt', quality).text(json.log.created_dt);
          $('#createdby_id', quality).text(json.log.createdby);
          $('#location_id', quality).text(json.log.location);
          $('#equipment_id', quality).text(json.log.equipment);
          $('#node', quality).text(json.log.node);
          $('#type', quality).text(json.log.log_type);
          $('#reason', quality).text(json.log.reason);  
          if(json.log.qualityby_id || json.log.status =='Y') state = 'qualified';
          else if(json.log.status == 'Q') state = 'quality';
          else if(json.log.status=='N') state = 'notactive';           
          else state ='created';            
             $('#closed_dt', quality).text(json.log.closed_dt);
             $('#duration', quality).text(json.log.duration);           
             $('#closedby_id', quality).text(json.log.closedby);         
             $("#completion", quality).text(json.log.completion);
             $('#wrap_closed', quality).show();         

        if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтлийн эрсдэл үнэлэх';
        else title = 'Гэмтлийн эрсдэл үнэлэх';
     }
    }).done(function() {
      console.log('state: '+state);
       _call_fn(state, title);
          //alert('Бүрэн хаасан гэмтлийг сонгож "Эрсдлийг үнэлэнэ" үү!');
          //showMessage('Бүрэн хаасан гэмтлийг сонгож "Эрсдлийг үнэлэнэ" үү!', 'error');       
    });
 }

function _quality(title){
    quality.dialog('option', 'title', title);
    quality.dialog({ 
       buttons: { 
          "Хадгалах": function () {
             $('p.feedback', quality).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
                var data = {};
                var inputs = $('input[type="text"], input[type="hidden"], select' , quality);
              
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });
               // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:   'POST',
                  url:    base_url+'/flog/index/quality/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      quality.dialog("close");
                      // close the dialog                         
                      showMessage(json.message, 'success');
                      // show the success message
                      reload();
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', quality).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller 
          },            
          "Хаах": function () {
              quality.dialog("close");
          }
       }
      }); 
    quality.dialog('open'); 
}

function _call_fn(state, title, id){
   switch (state) {    
      case "activated":
         alert('Аль хэдийн "Зөвшөөрөх" үйлдэл хийгдсэн тул дахиж хийх шаардлагагүй!');
         break;

      case "activate":
          approve_dialog(title);
          break;

      case "notactive":            
         alert('"Ерөнхий зохицуулагч инженер" зөвшөөрөх үйлдэл хийсний дараа энэ үйлдэл боломжтой!');
         //showMessage('Гэмтлийг "Ерөнхий зохицуулагч инженер" зөвшөөрсний дараа энэ үйлдэл боломжтой!', 'warning');
         break; 

      //CLOSE
      case "closing":
         alert("Хаах хүсэлтийг аль хэдийн илгээсэн тул энэ үйлдэл шаардлагагүй!");
        break;

      case "closed":            
         alert('Энэ гэмтэл аль хэдийн хаагдсан тул дахиж хаах шаардлагагүй!');
         break;               

      case "close":
         close_dialog(id);
         break;

      case "created":            
         alert('Шинээр нээсэн гэмтэл дээр энэ үйлдэл боломжгүй!');
         break; 

      case "qualified":
          alert("Энэ гэмтлийн эсрдлийг аль хэдийн үнэлсэн байна!");
          break;

      case "quality":
          _quality(title);
          break;

      case "edit":
          edit_dialog(title);
          break;

      case "nofile":
          alert("Энэ гэмтэлд файл хавсаргах боломжгүй! Эрсдлийн үнэлгээ хийгдсэний дараа тайлангийн файл хавсаргах эсэх нь шийдэгдэнэ");
          break;

      case "file":
        _upload(title);
        break; 
     }  
}

function warn_page(){    
   var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
   
    var status = $('#grid').jqGrid ('getCell', log_id, 'status');
   if(!log_id){
          alert('Нэг гэмтлийг сонгоно уу!');
   }else{ 
       if(status =='C'||status =='N'||status =='A')
             alert('Энэ гэмтэл хаагдаагүй байна, хаагдсан гэмтлийг сонгоно уу!');
       else 
         window.location = base_url+'/flog/warnpage/'+log_id;
               
   }
}


function _upload(title){  
  // if there is log_id  there is file uploaded
  file.dialog('option', 'title', title);
  file.dialog({ 
     buttons: {
        "Хаах": function () {
            file.dialog("close");

            reload();
         }
      }
  }); 
  file.dialog('open');
}

function del_file(log_id, file_name){
  if (confirm("["+file_name+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',
         url:    base_url+'/flog/index/del_file/'+log_id,
         data:   {id:log_id, file_name:file_name},
         dataType: 'json', 
         success:  function(json) {
            if(json.success){
              //then remove link
              feeds('success', json.message)
              $('#file_link', file).remove();
              $("#cert_file").show();
            }else{
              feeds('error', json.message)
              $('#file_link', file).remove();
              $("#cert_file").show();
            }
         }
       });
   
  } else {
     // nothing 
     return 0;
  }
}

