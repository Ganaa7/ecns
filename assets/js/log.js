var open, edit, approve, close, view, quality, role, action_str, sel_str, file;
window.open=false;
//':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ'
//import "jquery.popupwin.js";
$(document).ready(function(){
   role=$("#user_role").val();
   sel_str=set_select();

   open = $("#open_dialog");
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

   edit = $('#edit_dialog');
   edit.dialog({
       autoOpen: false,
       width: 570,
       resizable: false,    
       modal: true,       
       close: function () {
          $('p.feedback', edit).removeClass('error').html('').hide();
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"]', edit).val('');
          // clear the input values on form    
          $(this).dialog("close");          
       } 
    });

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

   close = $('#close_dialog');
   close.dialog({
       autoOpen: false,
       width: 550,       
       resizable: false,    
       modal: true,
       // Хаах товч
       close: function () {
          $('p.feedback', $(this)).html('').hide();
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');
          // clear the input values on form    
          $(this).dialog("close");
       }
   });

   //view dialog
   view=$('#view_dialog');
   view.dialog({
       autoOpen: false,
       width: 550,       
       resizable: false,    
       modal: true,
       // Хаах товч
       close: function () {          
          $('input[type="text"], input[type="hidden"], select, textarea', view).val('');
          $(this).dialog("close");
          $('#show_file', view).html('');                    
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
          $(this).dialog("close");
       }
   });

   file = $("#file_dialog");
   file.dialog({
       autoOpen: false,
       width: 550,       
       resizable: false,    
       modal: true,
       // Хаах товч
       close: function () {
          $('p.feedback', file).html('').hide();
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select, textarea', open).val('');
          // clear the input values on form    
          $(this).dialog("close");
       }
   });
   
   // call grid
   jqgrid();       
   // button binding
   button_bind();

   $("#show").on("click", function () {
      $("#open_dialog").dialog("open");
   });
   
   // createddate 
   $('#created_datetime', open).datetimepicker({
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   }); 

   $('#created_datetime_e', edit).datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true      
   }); 
  
   // createddate here
   $('#closed_datetime', edit).datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });   

   $('#c_closed_datetime', close).datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: false
   });  

   // filter_datetime
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

  //log filter 
  var form_filter = $("#log_filter");  
  //filter section change    
  filter_event(form_filter);   

  //filter here
  $('#filterBtn', form_filter).click(function (){    
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
             jQuery('#grid').jqGrid('setGridParam', { url: base_url+'/log/index/grid', page: 1, search:false, postData:{'filters': "", 'section_id': _data['section_id'], 'sector_id': _data['sector_id'],'equipment_id':_data['equipment_id'], 'log':_data['log'], 'date_option':_data['date_option'], 'start_dt':_data['start_dt'], 'end_dt':_data['end_dt']}}).trigger("reloadGrid");
          }
      }else     
        jQuery('#grid').jqGrid('setGridParam', { url: base_url+'/log/index/grid', page: 1, search:false, postData:{'filters': "", 'section_id': _data['section_id'], 'sector_id': _data['sector_id'],'equipment_id':_data['equipment_id'], 'log':_data['log'], 'date_option':_data['date_option'], 'start_dt':_data['start_dt'], 'end_dt':_data['end_dt']}}).trigger("reloadGrid");

   }); 

  //file songoh heseg       
  $('#cert_file', file).change(function (){
      var uploadfile = new FormData($("#file_dialog")[0]);        
      $.ajax({
          url:    base_url+'/log/index/upload/',
          type:    'POST',                               
          data:   uploadfile,                                              
          processData: false,  // tell jQuery not to process the data
          contentType: false,
          success:  function(json){                               
            console.log('json'+JSON.stringify(json));
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

function set_select(){   
  var str="", cnt=0;
   $("#section_id option").each(function(){
      str =$(this).text()+":"+$(this).text(); cnt++;
   });     
   if(cnt>1) str =':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ';
   return str;
}

// grid start here
function jqgrid(){
    jQuery("#grid").jqGrid({ 
        url:base_url+'/log/index/grid',
        datatype: 'xml', 
        mtype: 'GET', 
        colNames:['Зэрэглэл','Хэсэг', 'Гэмтэл №','Нээсэн хугацаа','Байршил','Төхөөрөмж', 'Гэмтэл', 'Хаасан хугацаа', 'Үргэлж/цаг', 'Гүйцэтгэл', 'Үйлдэл', 'Closed'], 
        colModel :[ {name:'q_level', index:'q_level', width:55, align:'center' }, 
                    {name:'section', index:'section', width:90, stype:'select', searchoptions:{value:sel_str} }, //':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ'
                    {name:'log_num', index:'log_num', width:50 }, 
                    {name:'created_datetime', index:'created_datetime', width:80, searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
                    {name:'location', index:'location', width:60, align:'center', formatter:view_link }, 
                    {name:'equipment', index:'equipment', width:90,  formatter:view_link},
                    {name:'defect', index:'defect', width:130, align:'left', formatter:view_link },    
                    {name:'closed_datetime', index:'closed_datetime', width:80, align:'right', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
                    {name:'duration_time', index:'duration_time', width:50, align:'center'}, 
                    {name:'completion', index:'completion', width:160, sortable:true }, 
                    {name:'act',index:'act',width:90, align:'center',sortable:false,formatter:log_action
                     // formatoptions:{
                     //     keys: true, // we want use [Enter] key to save the row and [Esc] to cancel editing.
                     //     //editbutton : true, 
                     //     onEdit:function(rowid) {
                     //         //alert("in onEdit: rowid="+rowid+"\nWe don't need return anything");
                     //         initEdit();
                     //     },
                     //     onSuccess:function(jqXHR) {
                     //         // the function will be used as "succesfunc" parameter of editRow function
                     //         // (see http://www.trirand.com/jqgridwiki/doku.php?id=wiki:inline_editing#editrow)
                     //         alert("in onSuccess used only for remote editing:"+
                     //               "\nresponseText="+jqXHR.responseText+
                     //               "\n\nWe can verify the server response and return false in case of"+
                     //               " error response. return true confirm that the response is successful");
                     //         // we can verify the server response and interpret it do as an error
                     //         // in the case we should return false. In the case onError will be called
                     //         return true;
                     //     },
                     //     onError:function(rowid, jqXHR, textStatus) {
                     //         // the function will be used as "errorfunc" parameter of editRow function
                     //         // (see http://www.trirand.com/jqgridwiki/doku.php?id=wiki:inline_editing#editrow)
                     //         // and saveRow function
                     //         // (see http://www.trirand.com/jqgridwiki/doku.php?id=wiki:inline_editing#saverow)
                     //         alert("in onError used only for remote editing:"+
                     //               "\nresponseText="+jqXHR.responseText+
                     //               "\nstatus="+jqXHR.status+
                     //               "\nstatusText"+jqXHR.statusText+
                     //               "\n\nWe don't need return anything");
                     //     },
                     //     afterSave:function(rowid) {
                     //         alert("in afterSave (Submit): rowid="+rowid+"\nWe don't need return anything");
                     //     },
                     //     afterRestore:function(rowid) {
                     //         alert("in afterRestore (Cancel): rowid="+rowid+"\nWe don't need return anything");
                     //     },
                     //     delOptions: myDelOptions
                     // }
                    },
                    {name:'closed', index:'closed', hidden:true, viewable:true} // hidden:true,                 
                    ], 
        pager: jQuery('#pager'), 
        rowNum:20, 
        rowList:[10,20,30,40],                    
        sortname: 'log_id', 
        sortorder: "desc", 
        viewrecords: true, 
        imgpath: 'themes/basic/images', 
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
              switch(rowData.closed){
                 case "C":
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('warning');
                  break;
                  case "A":
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('argent');
                  break;
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
    }).navGrid("#pager",{edit:false,add:false,del:false, search:false});

    

    jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false, beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }});
    // add custom button to export the data to excel
    // jQuery("#grid").jqGrid('navButtonAdd','#pager',{
    //    caption:"Excel файл", 
    //    onClickButton : function () { 
    //        jQuery("#grid").jqGrid('excelExport',{"url":"exportToExcel"});
    //    }
    //  });
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
        case 'quality':                      
            action_str=action_str+"<div title='Эрсдэл үнэлэх' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_quality("+options.rowId+")' class='ui-icon ui-icon-check'></span></div></div>";
        break;
        case 'close':
            action_str=action_str+"<div title='Хаах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_close("+options.rowId+")' class='ui-icon ui-icon-wrench'></span></div></div>";
        break;
        case 'edit':
            action_str=action_str+"<div style='margin-left:8px;'><div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_edit("+options.rowId+")' class='ui-icon ui-icon-pencil'></span></div>";
        break;
        
        case 'delete':
            action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+options.rowId+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
        break;
        
        case 'fileupload':
            action_str=action_str+"<div title='Тайлангийн файл' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='init_upload("+options.rowId+")' target ="+options.log_num+" class='ui-icon ui-icon-link'></span></div></div>";
        break;
    }
   });
   return action_str;
}

//open dialog 
function open_dialog(){
  // open log dialog
  open.dialog('option', 'title', 'Гэмтлийн бүртгэл шинээр нээх');
  open.dialog({ 
      buttons: {
         "Нээх": function () {
              $('p.feedback', open).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show(); 
              var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', open);
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });
              $.ajax({
                 type:     'POST',
                 url:    base_url+'/log/index/add/',
                 data:   data,
                 dataType: 'json', 
                 success:  function(json){ 
                    if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                       //энд үндсэн утгуудыг нэмэх болно.
                       open.dialog('close');
                       // close the dialog
                       showMessage(json.message, 'success');
                      // show the success message
                       reload();
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                       $('p.feedback', open).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller             
         },
         "Хаах": function () {
             open.dialog("close");
         }
      }
  }); 
  open.dialog('open');
}

// edit buttons here
//approve dialog
function initApprove(id){ 
   var post = { id: id }, state;  
   $.ajax({
      type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   post,
       dataType: 'json', 
       success:  function(json) {
          //alert(json);
          // log_num-г өгөх хэрэгтэй.
          $("#log_id", approve).val(json.log.log_id);
          $("#closed", approve).val(json.log.closed);
          $('#log_num', approve).val(json.log.log_num);   
          $('#equipment_id', approve).val(json.log.equipment_id);   
          if(json.log.log_num===null) $('#log_num_txt', approve).text('Зөвшөөрөх дарахад автоматаар олгогдоно.');
          else $('#log_num_txt', approve).text(json.log.log_num);                
          $('#section', approve).text(json.log.section);
          $('#created_datetime', approve).text(json.log.created_datetime);
          $('#createdby_id', approve).text(json.log.createdby);
          $('#location_id', approve).text(json.log.location);
          $('#equipment_id', approve).text(json.log.equipment);
          $('#defect', approve).text(json.log.defect);
          $('#reason', approve).text(json.log.reason);
          // approve hiihed herev 
          if(json.log.inst){            
            $('#inst option[value='+json.log.inst+']', approve).attr('selected', 'selected');
            $('#level option[value='+json.log.level+']', approve).attr('selected', 'selected');
            $("#inst", approve).prop('disabled', true);
            $("#level", approve).prop('disabled', true);            
          }
          if(json.log.closed=='Y'||json.log.closed=='N'){     
              $('#closed_datetime', approve).text(json.log.closed_datetime);
              $('#duration', approve).text(json.log.duration_time);           
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
         
          if(json.log.closed=='A'||json.log.closed=='Y'||json.log.closed=='Q'||json.log.closed=='F') state ='activated';
          else state='activate';
       }
    }).done(function(){       
       _call_fn(state, title);
    });    
}

function approve_dialog(title){
   console.log('called');

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
                    url:    base_url+'/log/index/active/',
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
    $('#create_log').click(function(){      
       open_dialog();
    });
    
    $('#close_log').click(function(){              
      var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
       if(!log_id){
          alert('Хаах гэмтлийг сонгоно уу!');
       }else
          init_close(log_id);
    });
    //Зөвшөөрөх гэмтэл
    $('#log_active').click(function(){              
      var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
       if(!log_id){
          alert('Зөвшөөрөх гэмтлийг сонгоно уу!');
       }else
          initApprove(log_id);
    });
    //Үнэлэх гэмтэл
    $('#quality').click(function(){              
      var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
       if(!log_id){
          alert('Эрсдэл үнэлэх гэмтлийг сонгоно уу!');
       }else
          init_quality(log_id);
    });
    // Устгах гэмтэл
    $('#log_delete').click(function(){     
       var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');         
       if(!log_id){
          alert('Устгах гэмтлийг сонгоно уу!');
       }else
          _delete(log_id);
    }); 
     // Устгах гэмтэл
    $('#log_edit').click(function(){              
      var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
       if(!log_id){
          alert('Засах гэмтлийг сонгоно уу!');
       }else
          init_edit(log_id);
    });
}

function init_edit(log_id){
    //selected_row = (jQuery('#grid').jqGrid('getGridParam','selrow'));
   var data = { id: log_id }, title, state;
     $.ajax({
        type:    'POST',        
        url:    base_url+'/log/index/catch/',
        data:   data,
        dataType: 'json', 
        success:  function(json) {
           //утгуудыг авч edit_dialog уруу дамжуулна    
           $("#log_id", edit).val(json.log.log_id);
           $('#log_num', edit).val(json.log.log_num);                
           $('#closed', edit).val(json.log.closed);                
           $('#created_datetime_e', edit).val(json.log.created_datetime);
           // $('#createdby_id option[value='+json.log.createdby_id+']', edit).attr('selected', 'selected');
           $('#location_id option[value='+json.log.location_id+']', edit).attr('selected', 'selected');
           $('#equipment_id option[value='+json.log.equipment_id+']', edit).attr('selected', 'selected');
            
           $('#defect', edit).val(json.log.defect);
           $('#reason', edit).val(json.log.reason);

          //herev closed baival utguudiig haruulna
           if(json.log.closed=='Q'||json.log.closed=='N'){                     
              $('#closed_datetime', edit).val(json.log.closed_datetime);
              $('#duration', edit).val(json.log.duration_time);                          
              $("#completion", edit).val(json.log.completion);
              $('#wrap_closed', edit).show();                    
          }else{                  
             $('#wrap_closed', edit).hide();    
          }  

            if(json.log.closed=="C") state="created"; //нээх зөвшөөрөл өгөөгүй гэмтлийн бүртгэл дээр энэ үйлдэл боломжгүй!!!                                                      
            else if(json.log.closed=="A"||json.log.closed=="Q"||json.log.closed=="N")
                state ="edit";
            // Хэрэв Q, N буюу хаагдаагүй гэмтлийг засварлах боломжто
            else state ="no_edit";

            if(json.log.log_num) 
               title = '"'+json.log.log_num+'" дугаартай гэмтэл засах';
            else 
              title = 'Гэмтэл засах';
         }
      }).done(function() {
         //edit_dialog(title);   
         _call_fn(state, title);       
      });
}

 //edit dialog 
function edit_dialog(title){  // open log dialog   
  edit.dialog('option', 'title', title);
  edit.dialog({ 
     buttons: {
        "Засах": function () {
           $('p.feedback', edit).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
              var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', edit);
              
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });

              if(data['closed_datetime']>data['created_datetime'])   data['duration_time']=1;
              else data['duration_time']=0;
              
              $.ajax({
                  type:     'POST',
                  url:    base_url+'/log/index/ajax_update/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
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
              });// send the data via AJAX to our controller 
         },
        "Хаах": function () {
            edit.dialog("close");
         }
      }
  }); 
  edit.dialog('open');
}

function init_close(id){
   var post = { id: id }, state;
   $.ajax({
       type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   post,
       dataType: 'json', 
       success:  function(json) {          
          // log_num-г өгөх хэрэгтэй.
          $("#log_id", close).val(json.log.log_id);                    
          $("#log_num", close).val(json.log.log_num);
          $("#closed", close).val(json.log.closed);
          $('#equipment_id', close).val(json.log.equipment_id);   
          if(json.log.log_num===null) $('#log_num_txt', close).text('Зөвшөөрөх дарахад автоматаар олгогдоно.');
          else $('#log_num_txt', close).text(json.log.log_num);                
          $('#created_datetime_txt', close).text(json.log.created_datetime);
          $('#created_datetime', close).val(json.log.created_datetime);
          $('#location', close).text(json.log.location);          
          $('#equipment', close).text(json.log.equipment);

          //хаасан гэмтлийг хаахгүй  
          if(json.log.closed=="N") state="closing"; //хаах хүсэлт илгээсэн
          else if(json.log.closed=="Y"||json.log.closed=="Q"||json.log.closed=="F") state="closed"; //бүрэн хаагдсан 
          else if(json.log.closed=="C") state="created" //нээх зөвшөөрөл өгөөгүй гэмтлийн бүртгэл дээр энэ үйлдэл боломжгүй!!!
          else state="close";  // хааж болно
          $('#defect', close).text(json.log.defect);
          $('#reason', close).text(json.log.reason);          
       }
    }).done(function(){
       _call_fn(state, title=null);
       //close_dialog();
    });
}

//haah
function close_dialog(){
   close.dialog('option', 'title', 'Гэмтлийн бүртгэл хаах');
   close.dialog({ 
      buttons: {
         "Хадгалах": function () {
            $('p.feedback', close).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
              var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', close);
              
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });
              if(data['closed_datetime']>data['created_datetime'])
                 data['duration_time']=1;
              else data['duration_time']=0;
              // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:     'POST',
                  url:    base_url+'/log/index/close/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      close.dialog('close');
                      // close the dialog                         
                      showMessage(json.message, 'success');
                      // show the success message
                      reload();
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', close).removeClass('success, notify').addClass('error').html(json.message).show();                        
                    }
                  }
              });// send the data via AJAX to our controller 
         },
        "Хаах": function () {
            close.dialog("close");
         }
      }
   }); 
   close.dialog('open');

}
//call reload grid
function reload(){
   $("#grid").trigger("reloadGrid"); 
}

//delete
function _delete(log_id){  
   var data = { id: log_id }; 
   var rowData = $("#grid").getRowData(log_id);   
    // ask confirmation before delete 
   if(window.confirm("Та '"+rowData.log_num+"' дугаартай гэмтлийг устгахдаа итгэлтэй байна уу?")){
      $.ajax({
         type:    'POST',
         url:    base_url+'/log/index/delete/',
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

function init_upload(log_id){
    var data = { id: log_id };          
    $.ajax({
       type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          //утгуудыг авч edit_dialog уруу дамжуулна    
          $("#log_id", file).val(json.log.log_id);
          $('#log_num', file).val(json.log.log_num);                
          $('input[name=created_datetime]', file).val(json.log.created_datetime);
          $('#createdby_id option[value='+json.log.createdby_id+']', file).attr('selected', 'selected');
          $('#location_id option[value='+json.log.location_id+']', file).attr('selected', 'selected');
          $('#equipment_id option[value='+json.log.equipment_id+']', file).attr('selected', 'selected');

          $('#defect', file).val(json.log.defect);
          $('#reason', file).val(json.log.reason);

          //herev closed baival utguudiig haruulna
          if(json.log.closed=='Y'||json.log.closed=='Q'||json.log.closed=='N'||json.log.closed=='F'){     
             $('#closed_datetime', file).val(json.log.closed_datetime);
             $('#duration', file).val(json.log.duration_time);           
             $('#closedby_id option[value='+json.log.closedby_id+']', file).attr('selected', 'selected');         
             $("#completion", file).val(json.log.completion);
             $('#wrap_closed', file).show();

          }else{                  
             $('#wrap_closed', file).hide();    
          }
          if(json.log.closed=='C'||json.log.closed=='Q'||json.log.closed=='A'||json.log.closed=='N') state='nofile';
          else if(json.log.closed=='Y') state='hasfile';
          else if(json.log.closed=='F') state='file';

          if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтэлийн дэлгэрэнгүй';          
       }
    }).done(function(){
       console.log('done:'+state+':'+title);
       _call_fn(state, title);
    });
}

function _upload(title){	
  // if there is log_id  there is file uploaded
  file.dialog('option', 'title', title);
  file.dialog({ 
     buttons: {
        "Хаах": function () {
            file.dialog("close");
         }
      }
  }); 
  file.dialog('open');
}


//file холбоос дээр дарахад харуулна!!
function _file(log_id){    
   var data = { id: log_id };                
    $.ajax({
       type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   data,
       dataType: 'json'
    }).done(function(json) { 
          console.log('file'+json.log.filename);
          $.ajax({
              url:base_url+'/download/log_files/'+json.log.filename,
              type:'HEAD'              
          }).done(function(data, textStatus, xhr){                  
              window.location(base_url+"/log/download/"+json.log.filename);
              //window.href("http://www.google.com", '_blank', "height='100%',width='100%'");
          }).fail(function(data, textStatus, xhr){
                if($(".ui-dialog").is(":visible")){
               feeds('error', 'Энэ гэмтлийн тайлангийн файлыг хавсаргаагүй тул харуулах боломжгүй!');
            }else
              showMessage('Энэ гэмтлийн тайлангийн файлыг хавсаргаагүй тул харуулах боломжгүй!', 'error');         
          });
    });
}


function del_file(log_id, file_name){
  if (confirm("["+file_name+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',
         url:    base_url+'/log/index/del_file/'+log_id,
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

function feeds(css_class, msg){
  if($('p.feedback', file).hasClass('error')) $('p.feedback', file).removeClass('error');  
  if($('p.feedback', file).hasClass('success')) $('p.feedback', file).removeClass('success');

  $('p.feedback', file).addClass(css_class).html(msg).show();                        
  $('p.feedback', file).stop().fadeIn('fast', function(){
       $('p.feedback', file).delay(5000).fadeOut();
      // fade out again after 3 seconds  
  });
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

//filter plugin functions
function filter_post(form_name, target_id, target_field, target){
    $.post( base_url+'/log/log_plugin', {id:target_id, field:target_field, table:target}, function(newOption) {   
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
    });
}
 
function filter_event(form_filter){
    //when cliked section call sector and equipment    
    $("#section_id", form_filter).change(function() {
       //section _id
       var sec_id = $(this).val();
       filter_post(form_filter, sec_id, 'section_id', 'sector');
       //filter_post 
       filter_post(form_filter, sec_id, 'section_id', 'equipment');
    });
    // sector clicked
    $("#sector_id", form_filter).change(function() {
       //sector _id
       var filter_id = $(this).val();           
       // section select 
       $('#section_id option[value='+(~~(filter_id/10))+']', form_filter).attr('selected', 'selected');
       //filter_post(form_filter, filter_id, 'sector_id', 'section');

       //filter_post 
       filter_post(form_filter, filter_id, 'sector_id', 'equipment');
    });    
}
//init_view() view all data in log
function init_view(log_id){
   //alert('init_view called'+str);
    var data = { id: log_id };          
    $.ajax({
       type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          //утгуудыг авч edit_dialog уруу дамжуулна    
          $("#log_id", view).val(json.log.log_id);
          $('#log_num', view).val(json.log.log_num);                
          $('input[name=created_datetime]', view).val(json.log.created_datetime);
          $('#createdby_id option[value='+json.log.createdby_id+']', view).attr('selected', 'selected');
          $('#location_id option[value='+json.log.location_id+']', view).attr('selected', 'selected');
          $('#equipment_id option[value='+json.log.equipment_id+']', view).attr('selected', 'selected');

          $('#defect', view).val(json.log.defect);
          $('#reason', view).val(json.log.reason);

          //herev closed baival utguudiig haruulna
          if(json.log.closed=='Y'||json.log.closed=='N'||json.log.closed=='F'||json.log.closed=='Q'){     
             $('#closed_datetime', view).val(json.log.closed_datetime);
             $('#duration', view).val(json.log.duration_time);           
             $('#closedby_id option[value='+json.log.closedby_id+']', view).attr('selected', 'selected');         
             $("#completion", view).val(json.log.completion);
             $('#wrap_closed', view).show();
             if(json.log.level=='A'||json.log.level=='B'||json.log.level=='C'){ //bval log file-g haruulna
                // console.log('test:'+$('#show_file').length);                
                $('#show_file', view).html('');                
                   // console.log('removed');                
                if(json.log.filename)
                  $('#show_file', view).html("<span>Тайлангийн файл:</span>  <span id='file_link'><a href='"+base_url+"/download/log_files/"+json.log.filename+"' download style='color:blue'>"+json.log.filename+"</a></span>");                      
                else
                   $('#show_file', view).html("<span>Тайлангийн файл:</span>  <span id='file_link'>Файл оруулаагүй байна!</span>");
             }
          }else{                  
             $('#wrap_closed', view).hide();    
          }
          if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтэлийн дэлгэрэнгүй';
          else title = 'Гэмтлийн дэлгэрэнгүй';
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
function init_quality(log_id){      
    var data = { id: log_id }, state;         
    $.ajax({
       type:    'POST',
       url:    base_url+'/log/index/catch/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          $("#log_id", quality).val(json.log.log_id);
          $('#log_num', quality).val(json.log.log_num);
          $('input[name=created_datetime]', quality).val(json.log.created_datetime);
          $('#createdby_id option[value='+json.log.createdby_id+']', quality).attr('selected', 'selected');
          $('#location_id option[value='+json.log.location_id+']', quality).attr('selected', 'selected');
          $('#equipment_id option[value='+json.log.equipment_id+']', quality).attr('selected', 'selected');
          $('#defect', quality).val(json.log.defect);
          $('#reason', quality).val(json.log.reason);
          if(json.log.closed=='C'||json.log.closed=='A') state = 'created';          
          else if(json.log.q_level) state = 'qualified';
          else if(json.log.closed =='N') state ='notactive';
          else if(json.log.closed=='Q') state = 'quality';
          else state = 'closed';

          if(json.log.closed=='N'||json.log.closed=='Q'){     
             $('#closed_datetime', quality).val(json.log.closed_datetime);
             $('#duration', quality).val(json.log.duration_time);           
             $('#closedby_id option[value='+json.log.closedby_id+']', quality).attr('selected', 'selected');         
             $("#completion", quality).val(json.log.completion);
             $('#wrap_closed', quality).show();
          }else  $('#wrap_closed', quality).hide();
         
         $("#level", quality).val(json.log.level);
         $('#inst option[value='+json.log.inst+']', quality).attr('selected', 'selected');

        if(json.log.log_num) title = '"'+json.log.log_num+'" дугаартай гэмтлийн эрсдэл үнэлэх';
        else title = 'Гэмтлийн эрсдэл үнэлэх';
     }
    }).done(function() {      
       _call_fn(state, title);
       console.log('state:'+state);
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
                  url:    base_url+'/log/index/quality/',
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

function _call_fn(state, title){   
   switch (state) {    
      case "activated":
         alert('Аль хэдийн "Зөвшөөрөх" үйлдэл хийгдсэн тул дахиж хийх шаардлагагүй!');
         break;

      case "activate":
          approve_dialog(title);
          break;

      case "notactive":            
         alert('Гэмтлийг "Ерөнхий зохицуулагч инженер" зөвшөөрсний дараа энэ үйлдэл боломжтой!');
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
         close_dialog();
         break;

      case "created":            
         alert('Шинээр нээсэн гэмтэл дээр энэ үйлдэл боломжгүй!');
         break; 

      case "qualified":
          //alert("");
          var r = confirm("Энэ гэмтлийн эсрдлийг аль хэдийн үнэлсэн байна! \n Та дахин үнэлэх үү?");
          if (r == true) {
              _quality();
          } else {
              //x = "You pressed Cancel!";
          }
          break;   

      case "quality":
          _quality();
          break;

      case "edit":
          edit_dialog(title);
          break; 

      case "no_edit":
          alert("Энэ гэмтлийг аль хэдийн үнэлсэн учраас засах боломжгүй!");
          break;

      case "prevent":
          alert('Энэ үйлдэл хийгдэх боломжгүй!!!');
          break;

      case "nofile":
          alert('ААЧУХ гэмтлийн аюулыг шинжилж эрсдлийг тогтоосны дараа \n [А, B, C] эрсдэлтэй гэмтлүүдэд тайлангийн файл хавсаргах шаардлагатай!');
          break; 

      case "hasfile":
          alert('Файл хавсаргах шаардлагагүй!\n Дэлгэрэнгүй хэсэг дээр дарж харна уу!');
          break;

      case "file":
          _upload(title);
          break; 
     }  
}

function warn_page(){    
   var log_id = $('#grid').jqGrid ('getGridParam', 'selrow');
   if(!log_id){
          alert('Нэг гэмтлийг сонгоно уу!');
   }else{                   
        window.location = base_url+'/log/warnpage/'+log_id;
   }
}