var add;

$(function() {
  
// var pageWidth = $(window).width();  
//     var width = $('#main_wrap').width();    
    var role=$("#role").val();        

    sel_str=set_type();
    str_section=get_section();
    page_open = $("#page_dialog");
    page_open.dialog({
       autoOpen: false,
       width: 550,       
       resizable: false,    
       modal: true,
       position: ['center',100],
       // Хаах товч
       close: function () {          
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select, textarea', page_open).val('');
          // clear the input values on form    
          $(this).dialog("close");
          if($('#file_link', page_open).length){
            $('#file_link', page_open).remove();
          } 
       }
   });    
    //edit dialog
    add = $("#add_dialog");

    edit = $("#edit_dialog");

    edit.dialog({
       autoOpen: false,
       width: 'auto',       
       resizable: false,    
       modal: true,
       position: ['center',100],
       // Хаах товч
       close: function () {          
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"],input[type="file"], select, textarea', edit).val('');
          // clear the input values on form    
          if($('#file_link', edit).length){
            $('#file_link', edit).remove();
          } 
            
          $('p.feedback', edit).text('');
          $('p.feedback', edit).removeClass('success, error');
          $(this).dialog("close");
       }
   });     

   add.dialog({
       autoOpen: false,
       width: 'auto',       
       resizable: false,    
       modal: true,
       position: ['center',100],
       // Хаах товч
       close: function () {          
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"],input[type="file"], select, textarea', add).val('');
          // clear the input values on form    
          if($('#file_link', add).length){
            $('#file_link', add).remove();
          } 
            
          $('p.feedback', add).text('');
          $('p.feedback', add).removeClass('success, error');
          $(this).dialog("close");
       }
   });   

    //certificate file
    license = $("#license_dialog");

    license.dialog({
       autoOpen: false,
       width: 'auto',       
       resizable: false,    
       modal: true,
       position: ['center',100],
       // Хаах товч
       close: function () {          
          // clear & hide the feedback msg inside the form
          $('p.feedback', edit).text('');
          $('p.feedback', edit).removeClass('success, error');          
       }
    });    


    jQuery("#grid").jqGrid({        
        url:base_url+'/certificate/index/grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260',
        colNames:['#','Гэрчилгээ #','Байршил', 'Тон/төхөөрөмж', 'Сериал/он', 'Олгосон /t','Дуусах /t', 'Хэсэг'],
        colModel:[
          {name:'id',index:'id',search:false, width:30, align:"center"},
          {name:'cert_no',index:'cert_no', width:40,align:"center", formatter:view_link, searchoptions:{sopt:['cn']} },
          {name:'location',index:'location.location', width:60, align:"center", searchoptions:{sopt:['cn']}},    //, stype:'select', searchoptions:{value:sel_str}
          {name:'equipment',index:'equipment', width:110, align:"left", formatter:view_link, searchoptions:{sopt:['cn']}},
          {name:'serial_no_year',index:'serial_no_year', width:80,align:"right", formatter:view_link, searchoptions:{sopt:['cn']}},
          {
             name: 'issueddate',
             index: 'issueddate',
             width: 60,
             align: 'center',
             searchoptions: {
                sopt: ['cn'], dataInit: function (el) {
                   $(el).datepicker({
                      dateFormat: "yy-mm-dd"
                   }).change(function () {
                      $("#grid")[0].triggerToolbar();
                   });
                }
             }
          },
          {
             name: 'validdate',
             index: 'validdate',
             width: 60,
             align: 'center',
             searchoptions: {
                sopt: ['cn'],
                dataInit: function (el) {
                   $(el).datepicker({
                      dateFormat: "yy-mm-dd"
                   }).change(function () {
                      $("#grid")[0].triggerToolbar();
                   });
                }
             }
          },
          {name:'section',index:'section', width:70, stype:'select', searchoptions:{value:str_section}}
        ],
         jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "id"
        },
        rowNum:20,
        rowList:[10,20,30],
        pager: '#pager',
        sortname: 'validdate',
        viewrecords: true,
        sortorder: "asc",
        caption:".: Гэрчилгээ :.",
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          

           for (var i=0;i<rowIds.length;i++){ 

              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);

              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));

              if(check_date(rowData.validdate)<=45&&rowData.validdate.length>0){

                  trElement.removeClass('ui-widget-content');

                  trElement.addClass('argent'); //red                
              }   

              if(check_date(rowData.validdate)>45&&check_date(rowData.validdate)<=60&&rowData.validdate.length>0){

                 trElement.removeClass('ui-widget-content');

                 trElement.addClass('warning');                  

              }

             trElement.addClass('context-menu');

           }   

       }  
    });

    jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

    // Ашиглалтаас хасагдсан ТТ-ын гэрчилгээ
    jQuery("#grid2").jqGrid({        
        url:base_url+'/certificate/index/out_grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260',
        colNames:['#','Гэрчилгээ #','Байршил', 'Тон/төхөөрөмж', 'Сериал/он', 'Олгосон /t','Дуусах /t', 'Хэсэг'],
        colModel:[
          {name:'id',index:'id',search:false, width:30, align:"center"},
          {name:'cert_no',index:'cert_no', width:40,align:"center", formatter:view_link },
          {name:'location',index:'location', width:60, align:"center"},    //, stype:'select', searchoptions:{value:sel_str}
          {name:'equipment',index:'equipment', width:110, align:"left", formatter:view_link },
          {name:'serial_no_year',index:'serial_no_year', width:80,align:"right", formatter:view_link},
          {name:'issueddate',index:'issueddate', width:60, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },   
          {name:'validdate',index:'validdate', width:60, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},   
          {name:'section',index:'section', width:70, stype:'select', searchoptions:{value:str_section}}
        ],
         jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "id"
        },
        rowNum:20,
        rowList:[10,20,30],
        pager: '#pager',
        sortname: 'validdate',
        viewrecords: true,
        sortorder: "asc",
        caption:".: Ашиглалтаас гарсан тоног төхөөрөмжийн гэрчилгээ :.",
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){ 
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid2'));
              trElement.removeClass('ui-widget-content');
              trElement.addClass('inactive'); //red                 
           }         
       }  
    });


    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
     jQuery("#grid2").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
     //edit page 
      var appent_txt;
     
      // Education Button Clicked!        
      $( "#edu_button_add" ).click(function(){        
        var appent_txt="<tr><td><input type='datetime' name='school[]'></td><td><input type='datetime' name='enter_dt[]' class='date_class'></td><td><input type='text' name='grade_dt[]' class='date_class'></td><td><textarea name='detail[]' cols='25'></textarea></td></tr>";
           $("#education").append(appent_txt).find('.date_class').datepicker();      
      });
      //Мөр хасан товч дарагдахад
      $('#edu_button_sub').click(function(){
        var rowCount = $('#education tr').length;
        if(rowCount>2) //Хасах боломжтой
             $('#education tr:last').remove();
          else
             alert("Сүүлчийн мөрийг хасах боломжгүй!");
      });

      $("#back").click(function(){
         document.location=base_url+"/training/";    
      });

      $('#valid_date').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
      }); 

      $('#issued_date').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     });   

     $('.enter_dt').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     }); 

     $('.edit_date', edit).datepicker({
         dateFormat: 'yy-mm-dd'
     });  

     $( "#submit" ).click(function() {  
        var trainer_form = $('#trainer_form'), data = {};             
        var inputs = $('input[type="text"], input[type="hidden"], select, textarea', trainer_form);         
        var data = $( trainer_form ).serialize();
        //console.log(data);
        $.ajax({
           type:     'POST',
           url:    base_url+'/training/index/update/',
           data:   data,
           dataType: 'json', 
           success:  function(json){ 
              if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
                 // close the dialog                         
                 $('p.feedback', trainer_form).removeClass('error').hide();
                 showMessage(json.message, 'success');
                 // show the success message      
                 document.location="/training/";
              }                  
              else{  // ямар нэг юм нэмээгүй тохиолдолд
                $('p.feedback', trainer_form).removeClass('success, notify').addClass('error').html(json.message).show();                        
              }
            }
          });
      });

     // here is file click that upload file     
     $('#cert_file', edit).change(function (){
        var uploadfile = new FormData($("#edit_dialog")[0]);        
        $.ajax({
            url:    base_url+'/certificate/index/upload/',
            type:    'POST',                               
            data:   uploadfile,                                              
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            success:  function(json){                   
              if (json.status == "success") {      
              // if ajax return success                 
              // show the success message                                                     
                feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!');                
                 // hide file 
                if(json.name){
                  $("input[type='file']", edit).val('').hide();       

                  $("input[name='cert_file']", edit).val(json.name);                   
                    
                  if(!$('#_file', edit).lenght)
                       $("#_file", edit).append("<span id='file_link'><a href='#' style='color:blue' onclick='_file("+json.cert_id+")'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.cert_id+", \""+json.name+"\")'>Устгах</a>)</span>");                      
                 }                                              
              }                  
              else{  // ямар нэг юм нэмээгүй тохиолдолд                                
                feeds('error', json.message);
                $("input[type='file", edit).val('');
              }
            }
        }); 
     });  

     // here is file click that upload file     
     $('#cert_file', add).change(function (){
        
        var uploadfile = new FormData($("#add_dialog")[0]);    

        $.ajax({
            url:    base_url+'/certificate/index/add_upload/',
            type:    'POST',                               
            data:   uploadfile,                                              
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            success:  function(json){                   
              if (json.status == "success") {      
              // if ajax return success                 
              // show the success message                                                     
                feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!');                
                 // hide file 
                if(json.name){
                  $("input[type='file']", add).val('').hide();                    

                  $("input[name='cert_file']", add).val(json.name);

                    if(!$('#_file', add).lenght)
                       $("#_file", add).append("<span id='file_link'><a href='"+base_url+'/download/cert_files/'+json.name+"' target='_blank' style='color:blue' >"+json.name+"</a> (<a href='#' style='color:red' onclick='delete_file( \""+json.name+"\")'>Устгах</a>)</span>");                      
                 }                                              
              }                  
              else{  // ямар нэг юм нэмээгүй тохиолдолд                                
                feeds('error', json.message);
                $("input[type='file", add).val('');
              }
            }
        }); 
     });
}); 

//check_date function 
function check_date(vdate){   
   var cdate = new Date(current_date());
   var vdate = new Date (vdate);
   
   var diff =vdate-cdate;   // Math.abs
   var diffDays = Math.ceil(diff / (1000 * 3600 * 24));

   return diffDays;
}

function current_date(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();
  
  if(dd<10) {
     dd='0'+dd;
  } 

  if(mm<10) {
      mm='0'+mm;
  }
   return  yyyy+'-'+mm+'-'+dd;
}

function view_link(cellValue, options){
   return "<a href='#' onclick='page_view(" + options.rowId + ")' >"+cellValue+"</a>"; 
}

function set_type(){   
  var str="";
  str =':Бүгд;Алтай:Алтай;Арвайхээр:Арвайхээр;Баруун-Туруун:Баруун-Туруун;Баруун-Урт:Баруун-Урт;Бор-Өндөр:Бор-Өндөр;Баянхонгор:Баянхонгор;Булган - Булган:Булган-Булган;Булган - Ховд:Булган-Ховд;Далан:Далан;Даланзадгад:Даланзадгад;Өлгий:Өлгий;Өндөрхаан:Өндөрхаан;Мандалговь:Мандалговь;Мөрөн:Мөрөн;Мөнх-Өлзийт:Мөнх-Өлзийт;Сайншанд:Сайншанд;Тосонцэнгэл:Тосонцэнгэл;Улаанбаатар:Улаанбаатар;Улаангом:Улаангом;Улиастай:Улиастай;Ургамал:Ургамал;Хархорин:Хархорин;Ховд:Ховд;Хэнгэрэгтэй:Хэнгэрэгтэй;Ханбумбат:Ханбумбат;Чойбалсан:Чойбалсан';  
  return str;
}

//хэрэв section байхгүй бол 4 section-г харуулна.
function get_section(){   
   
  var sec_code=$("#sec_code").val();

  var sections = ["COM", "SUR", "ELC", "NAV", "CHI"];

  var a = sections.indexOf(sec_code), str="";  

  if(a>-1){ //олдох юм бол
     //Холбоо, Ажиглалт
     switch(sec_code){
         case 'COM': str ='Холбоо:Холбоо';
             break;

        case 'NAV': str ='Навигаци:Навигаци';
             break;

        case 'SUR': str ='Ажиглалт:Ажиглалт';
             break;
        
        case 'CHI': str ='ЧОУНБХ (NUBIA): ЧОУНБХ (NUBIA)';
             break;
        
        default:  str ='Гэрэл суулт цахилгаан:ГСЦ';
       }  
  }else str =':Бүгд;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;Навигаци:Навигаци;Холбоо:Холбоо';  
  return str;
}

function t_action (cellvalue, options, rowObject) {     
	   action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_none' onclick='init_view("+rowObject.id+")' class='ui-icon ui-icon-extlink'></span></div></div>";
	   if($("input").hasClass('action')){
  	    $('input.action').each(function() {
  	        switch($(this).val()){	    
    	        case 'edit':
    	            action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-plus'><span onclick='init_edit("+rowObject.id+")' class='ui-icon ui-icon-wrench'></span></div></div>";
    	        break;	        
    	        case 'delete':
                  action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+rowObject.id+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
              break; 
              case 'file':
                  action_str=action_str+"<div title='Файл-г харах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-document'><span onclick='_file("+rowObject.id+")' class='ui-icon ui-icon-document'></span></div></div>";    
              break;  
              case 'license':
                  action_str=action_str+"<div title='Үнэмлэхийг харах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-note'><span onclick='_license("+rowObject.id+")' class='ui-icon ui-icon-note'></span></div></div>";    
              break;  
              case 'outservice':
    	            action_str=action_str+"<div title='Ашиглалтаас хасах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-closethick'><span onclick='outservice("+rowObject.id+")' class='ui-icon  ui-icon-cancel'></span></div></div>";    
    	        break;
  	    }
  	   });
    }
	  return action_str;
}

function init_view(id) {
  page_view(id);
}

function init_edit(id) {  
   edit_dialog(id);
}

//=delete
function _delete(cert_id) {    
  var ask=confirm("ТА ЭНЭ ГЭРЧИЛГЭЭГ УСТГАХДАА ИТГЭЛТЭЙ БАЙНА УУ?");
  if(ask){       
     var post = { id: cert_id };
     $.ajax({
           type:     'POST',
           url:    base_url+'/certificate/index/delete/',
           data:   post,
           dataType: 'json', 
           success:  function(json){ 
              if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
                 // close the dialog                         
                 //$('p.feedback', trainer_form).removeClass('error').hide();
                 showMessage(json.message, 'success');
                 // show the success message      
                 reload();
              }                  
              else{  // ямар нэг юм нэмээгүй тохиолдолд
                showMessage(json.message, 'error');
                //$('p.feedback', trainer_form).removeClass('success, notify').addClass('error').html(json.message).show();                        
              }
          }
     });
  }
}

function showMessage(message, p_class){
   if (!$('p#notification').length){
      //$('#main_wrap').prepend('<p id="notification"></p>');
      $('#nav-bar').prepend('<p id="notification"></p>');
   }
   var paragraph = $('p#notification');
   // paragraph.hide();
   if(paragraph.hasClass("success"))
      paragraph.removeClass('sucess')
   else if(paragraph.hasClass("error"))
      paragraph.removeClass('error')
   
      // remove all classes from the <p>
   paragraph.addClass(p_class);
   // add the class supplied
   paragraph.html(message);
   // change the text inside
   paragraph.stop().fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}

function feeds(css_class, msg){
  if($('p.feedback', edit).hasClass('error')) $('p.feedback', edit).removeClass('error');  
  if($('p.feedback', edit).hasClass('success')) $('p.feedback', edit).removeClass('success');

  $('p.feedback', edit).addClass(css_class).html(msg).show();                        
  $('p.feedback', edit).stop().fadeIn('fast', function(){
       $('p.feedback', edit).delay(5000).fadeOut();
      // fade out again after 3 seconds  
  });
}

function reload(){
   //$("#grid").trigger("reloadGrid"); 
   $("#grid").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
   $("#gs_cert_no").val('');
   $("#gs_equipment").val('');
   $("#gs_equipment").val('');
   $("#gs_serial_no_year").val('');
   $("#gs_issueddate").val('');
   $("#gs_validdate").val('');
   $("#gs_section").val('');
}

function page_view(cert_id){   
    var data = { id: cert_id };        
      title = 'Тоног төхөөрөмжийн гэрчилгээ';
      $.ajax({
         type:    'POST',
         url:    base_url+'/certificate/index/page/',
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            //herev closed baival utguudiig haruulna   
              $("#cert_no", page_open).val(json.cert_no);
              $("#equipment", page_open).val(json.equipment.equipment);
              $('#location', page_open).val(json.location.location);   
              $('#serial_no_year', page_open).val(json.serial_no_year);   
              $('#section', page_open).val(json.section);   
              $('#intend', page_open).val(json.equipment.intend);
              $('#issueddate', page_open).text(json.issueddate);
              $('#validdate', page_open).text(json.validdate); 
              if(json.cert_file){                                              
                 $('#_file', page_open).append("<span id='file_link'><a href='#' style='color:blue;' onclick='_file("+cert_id+")'>"+json.cert_file+"</a></span>");
              }else       
                 $('#_file', page_open).append("<span id='file_link' style='color:red'>Файл байхгүй!</span>");
                 
         }
      }).done(function() {
         page_open.dialog('option', 'title', title);

         page_open.dialog({  
                  buttons: {             
                    "Хаах": function () {
                        page_open.dialog("close");
                    }                  
                  }
               }); 
         page_open.dialog('open');
      });
}

//edit dialog comes out
function edit_dialog(cert_id){ 
    var data = { id: cert_id };         
    title = 'Тоног төхөөрөмжийн гэрчилгээ: Засах';
    //ajax-с утгуудыг авч inputed- харуулах
    // дараа нь 
    $.ajax({
       type:    'POST',
       url:    base_url+'/certificate/index/page/'+cert_id,
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          //herev closed baival utguudiig haruulna   
            $("#cert_id", edit).val(cert_id);
            $("#cert_no", edit).val(json.cert_no);
            $("#equipment", edit).val(json.equipment);
            $('#location_id option[value='+json.location_id+']', edit).attr('selected', 'selected');
            $('#serial_no_year', edit).val(json.serial_no_year);   
            $('#section', edit).val(json.section);   
            $('#intend_2', edit).val(json.equipment.intend);
            $('#equipment_id option[value='+json.equipment_id+']', edit).attr('selected', 'selected');
            $('input[type="text"][name="issueddate"]', edit).val(json.issueddate);
            $('input[type="text"][name="validdate"]', edit).val(json.validdate);       
            if(json.cert_file){ 
               $("input[name='cert_file']").val(json.cert_file);
               $('#cert_file', edit).hide();               
               $('#_file', edit).append("<span id='file_link'><a href='#' style='color:blue;' onclick='_file("+cert_id+")'>"+json.cert_file+"</a> (<a href='#' style='color:red' onclick='del_file("+cert_id+",\""+json.cert_file+"\")'> Устгах </a>)</span>");
            }else       
               if(('#cert_file',edit).is(':hidden')) $('#cert_file', edit).show();
       }
    }).done(function() {
       edit.dialog('option', 'title', title);
       edit.dialog({ 
          buttons: {  
            "Хадгалах": function(){                     
                //here call ajax to send edit
                //collect data here
                var data = {id:cert_id};
                var inputs = $('input[type="text"], input[type="hidden"], select, textarea', edit);

                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });

                $.ajax({
                    type:     'POST',
                    url:    base_url+'/certificate/index/edit/',
                    data:   data,
                    dataType: 'json', 
                    success:  function(json){ 
                      if (json.status == "success") {      
                      // if ajax return success
                         edit.dialog('close');
                         // close the dialog                         
                         showMessage(json.message, 'success');
                         // show the success message
                         reload();
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', edit).removeClass('success, notify').addClass('error').html(json.message).show();                        
                      }
                    }
                });                           
             //   edit.dialog("close");
             },           
             "Хаах": function () {
                 edit.dialog("close");
             }
          }
       }); 
       edit.dialog('open');
    });
}

// add dialog comes out
function add_dialog(){ 
    
    title = 'Шинэ: Тоног төхөөрөмжийн гэрчилгээ ';
    // дараа нь 
   add.dialog('option', 'title', title);

   add.dialog({ 
     
      buttons: {  

        "Хадгалах": function(){                     
            //here call ajax to send add
            //collect data here
             var data = {};

            var inputs = $('input[type="text"], input[type="hidden"], select, textarea', add);

            inputs.each(function(){
              var el = $(this);
              data[el.attr('name')] = el.val();
            });

            $.ajax({
                type:     'POST',
                url:    base_url+'/certificate/index/add/',
                data:   data,
                dataType: 'json', 
                success:  function(json){ 

                  if (json.status == "success") { 

                  // if ajax return success
                     add.dialog('close');
                     // close the dialog        

                     showMessage(json.message, 'success');

                     // show the success message
                     reload();
                  }                  

                  else{  // ямар нэг юм нэмээгүй тохиолдолд
                    $('p.feedback', add).removeClass('success, notify').addClass('error').html(json.message).show();                        
                  }
                }
            });                           

        },

        "Хаах": function () {
           
           add.dialog("close");

        }

       }

    }); 

    add.dialog('open');
}

//file haruulah heseg
function _file(cert_id){   
   var data = { id: cert_id };                
    $.ajax({
       type:    'POST',
       url:    base_url+'/certificate/index/page/'+cert_id,
       data:   data,
       dataType: 'json'
    }).done(function(json) { 
          $.ajax({
              url:base_url+'/download/cert_files/'+json.cert_file,
              type:'HEAD'              
          }).done(function(data, textStatus, xhr){              
              window.open(base_url+"/pdf/web/viewer.html?file=../../download/cert_files/"+json.cert_file, '_blank');
          }).fail(function(data, textStatus, xhr){
                if($(".ui-dialog").is(":visible")){
               feeds('error', 'Энэ тоног төхөөрөмжийн гэрчилгээний файлыг хавсаргаагүй тул харуулах боломжгүй!');
            }else
              showMessage('Энэ тоног төхөөрөмжийн гэрчилгээний файлыг хавсаргаагүй тул харуулах боломжгүй!', 'error');         
          });
    });
}

function del_file(cert_id, file){
  if (confirm("["+file+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',
         url:    base_url+'/certificate/index/del_file/'+cert_id,
         data:   {id:cert_id, file_name:file},
         dataType: 'json', 
         success:  function(json) {

            if(json.status=="success"){
              //then remove link
              $('input[name=cert_file]', edit).val('');

              feeds('success', json.message)
              

              $('#file_link', edit).remove();
              $("#cert_file", edit).show();
            }else{
              feeds('error', json.message)
              $('#file_link', edit).remove();
              $("#cert_file", edit).show();
            }
         }
       });
   
  } else {
     // nothing 
     return 0;
  }
}

function delete_file(file){
  if (confirm("["+file+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',

         url:    base_url+'/certificate/index/delete_file/',

         data:   { file_name:file},

         dataType: 'json', 

         success:  function(json) {

            if(json.success){
              //then remove link
              feeds('success', json.message)
              $('#file_link', add).remove();
              $("#cert_file", add).show();
              $("input[name='cert_file']", add).val('');
            }else{
              feeds('error', json.message)
              $('#file_link', add).remove();
              $("#cert_file", add).show();
            }
         }
       });
   
  } else {
     // nothing 
     return 0;
  }
}

function _license(id){
   var data = { id: id };                
    $.ajax({
       type:    'POST',
       url:    base_url+'/certificate/index/page/'+id,
       data:   data,
       dataType: 'json'
    }).done(function(json) { 
       console.log(json);
       // call here dialog 
       // call here 
    });
  // dialog call
  // then text area dotor training 
}

function outservice(e) {
   if (1 != confirm(' Энэ Гэрчилгээг "Ашиглалтаас хасахдаа" итгэлтэй байна уу?')) return 0;
   
   $.ajax({
      type: "POST",
      url: base_url + "/certificate/index/set_outservice/" + e,
      data: {
         id: e
      },
      dataType: "json",
      success: function (e) {
         e.success ? showMessage(e.message, "success") : showMessage(e.message, "error"), reload()
      }
   });
}

