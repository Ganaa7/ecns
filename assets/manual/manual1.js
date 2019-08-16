var new_form, _id;
$(function() {
// var pageWidth = $(window).width();  
//     var width = $('#main_wrap').width();

    $("input[type='file", new_form).show();

    var role=$("#role").val();        

    sel_str=set_type();
    str_section=get_section();
    page_open = $("#page_dialog");
    page_open.dialog({
       autoOpen: false,
       width: 640,
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
    edit = $("#edit_dialog");

    // new form called for dialog
    new_form = $('#new_form');

    // Шинээр нэмэхэд
    new_form.dialog({
        autoOpen: false,
        width: 'auto',
        resizable: false,
        modal: true,
        position: ['center',100],
        // Хаах товч
        close: function () {
            // clear & hide the feedback msg inside the form
            $('input[type="text"], input[type="hidden"],input[type="file"], select, textarea', new_form).val('');
            // clear the input values on form
            if($("input[type='file']", new_form).css('display') == 'none'){
                $("input[type='file']", new_form).show();
            }
            if($('#file_link', new_form).length){
                $('#file_link', new_form).remove();
            }
            $('p.feedback', new_form).text('');
            $('p.feedback', new_form).removeClass('success');
            $('p.feedback', new_form).removeClass('error');
            $(this).dialog("close");
        }
    });

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
        url:base_url+'/manual/index/grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260',
        colNames: ['#', 'Хэсэг', 'Тон/төхөөрөмж', 'Техник Ашиглалтын заавар', 'Индекс', 'Шинэчлэгдсэн огноо', 'Батлагдсан огноо', 'Файл', 'Үйлдэл'],
        colModel:[
          {name:'id',index:'id',search:false, width:30},
          {name:'section',index:'section', width:60,align:"center", stype:'select', searchoptions:{value:str_section}},
          {name:'equipment',index:'equipment', width:110, align:"left", formatter:view_link },
          {name:'manual',index:'manual', width:80,align:"right", formatter:view_link},
          {name:'doc_index',index:'doc_index', width:60, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },
          {
            name: 'updated_at',
            index: 'updated_at',
            width: 60,
            align: 'center',
            searchoptions: {
              dataInit: function (el) {
                $(el).datepicker({
                  dateFormat: "yy-mm-dd"
                }).change(function () {
                  $("#grid")[0].triggerToolbar();
                });
              }
            }
          },
          {
            name: 'update_date',
            index: 'update_date',
            width: 60,
            align: 'center',
            searchoptions: {
              dataInit: function (el) {
                $(el).datepicker({
                  dateFormat: "yy-mm-dd"
                }).change(function () {
                  $("#grid")[0].triggerToolbar();
                });
              }
            }
          },
          {name:'filename',index:'filename', width:70 },
          {name:'action',index:'action',width:75, align:'center',sortable:false,search:false, formatter:t_action},
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
        sortname: 'id',
        viewrecords: true,
        sortorder: "asc",
        caption:".: Техник ашиглалтын заавар :."
    });

    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
    jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
     //edit page 
    var appent_txt;
     
    //datepicker({ dateFormat:"yy-mm-dd" })
    $('#update_dated', new_form).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        showOtherMonths: true,
        showWeek: true
    });

    $("#edit_update_date", edit).datepicker({
        dateFormat:'yy-mm-dd'
    });

    $('#issued_date').datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     });

     $('.edit_date', edit).datepicker({
         dateFormat: 'yy-mm-dd'
     });  

     // fileupload funciton here
     $('#manual_file', new_form).change(function (){
        var uploadfile = new FormData($("#new_form")[0]);
        // alert("hello dear "+uploadfile);
        $.ajax({
            url:    base_url+'/manual/index/upload/',
            type:    'POST',
            data:   uploadfile,
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            success:  function(json){
              if (json.status == "success") {
              // if ajax return success show the success message
                feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!', new_form);
                 // hide file
                 $("input[type='file']", new_form).val('').hide();
                $("#uploaded_file", new_form).val(json.name);

                if(!$('#_file', new_form).lenght)
                   $("#_file", new_form).append("<span id='file_link'><a href='#' style='color:blue' onclick='_file(\""+json.name+"\")'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.id+", \""+json.name+"\", new_form)'>Устгах</a>)</span>");

              }else{  // ямар нэг юм нэмээгүй тохиолдолд
                feeds('error', json.message, new_form);
                $("input[type='file", new_form).val('');
              }
            }
        });
     });

    // fileupload funciton here
    $('#manual_file', edit).change(function (){
        var uploadfile = new FormData($("#edit_dialog")[0]);
        //console.log("upload edit called");
        $.ajax({
            url:    base_url+'/manual/index/upload/',
            type:    'POST',
            data:   uploadfile,
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            success:  function(json){
                if (json.status == "success") {
                    // if ajax return success show the success message
                    feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!', edit);
                    // hide file
                    $("input[type='file", edit).val('').hide();
                    $("#uploaded_file", edit).val(json.name);

                    if(!$('#_file', edit).lenght)
                        $("#_file", edit).append("<span id='file_link'><a href='#' style='color:blue' onclick='_file(\""+json.name+"\")'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.id+", \""+json.name+"\", edit)'>Устгах</a>)</span>");

                }else{  // ямар нэг юм нэмээгүй тохиолдолд
                    feeds('error', json.message, edit);
                    $("input[type='file", edit).val('');
                }
            }
        });
    });

    // Шинэ дээр хэсэг сонгоход
     $('#section_id', edit).change(function () {
         console.log('changing..');
         $.ajax({
             data:{section_id:$(this).val()},
             type:     'POST',
             url:  base_url+'/manual/index/filter',
             async: false,
         }).done(function(newOption){
             var select = $('#equipment_id', edit);
             if(select.prop) {
                 var options = select.prop('options');
             }else {
                 var options = select.attr('options');
             }
             $('option', select).remove();
             // equipment option remove
             $.each(newOption, function(val, text) {
                 options[options.length] = new Option(text, val);
             });
         });
     });

     //Засах дээр хэсэг сонгоход
    $('#section_id', new_form).change(function () {
        console.log('changing..');
        $.ajax({
            data:{section_id:$(this).val()},
            type:     'POST',
            url:  base_url+'/manual/index/filter',
            async: false,
        }).done(function(newOption){
            var select = $('#equipment_id', new_form);
            if(select.prop) {
                var options = select.prop('options');
            }else {
                var options = select.attr('options');
            }
            $('option', select).remove();
            // equipment option remove
            $.each(newOption, function(val, text) {
                options[options.length] = new Option(text, val);
            });
        });
    });

}); 

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
  var fruits = ["COM", "SUR", "ELC", "NAV"];
  var a = fruits.indexOf(sec_code), str="";  
  if(a>-1){ //олдох юм бол
     //Холбоо, Ажиглалт
     switch(sec_code){
         case 'COM': str ='Холбоо:Холбоо';
             break;

        case 'NAV': str ='Навигаци:Навигаци';
             break;

        case 'SUR': str ='Ажиглалт:Ажиглалт';
             break;
        
        default:  str ='Гэрэл суулт цахилгаан:ГСЦ';
       }  
  }else str =':Бүгд;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;Навигаци:Навигаци;Холбоо:Холбоо';  
  return str;
}

function t_action (cellvalue, options, rowObject) {     
	   action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_none' onclick='page_view("+rowObject.id+")' class='ui-icon ui-icon-extlink'></span></div></div>";
	   if($("input").hasClass('action')){
  	    $('input.action').each(function() {
  	        switch($(this).val()){	    
    	        case 'edit':
    	            action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-plus'><span onclick='edit_dialog("+rowObject.id+")' class='ui-icon ui-icon-wrench'></span></div></div>";
    	        break;	        
    	        case 'delete':
                  action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+rowObject.id+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
              break; 
              case 'file':
                  action_str=action_str+"<div title='Файл-г харах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-document'><span onclick='_file("+rowObject.id+")' class='ui-icon ui-icon-document'></span></div></div>";    
              break;
  	    }
  	   });
    }
	  return action_str;
}

//=delete
function _delete(_id) {
  var ask=confirm("ТА энэ ТАЗ-ыг устгахдаа итгэлтэй байна уу?");
  if(ask){       
     var post = { id: _id };
     $.ajax({
           type:     'POST',
           url:    base_url+'/manual/index/delete/',
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

function feeds(css_class, msg, form_name){
  if($('p.feedback', form_name).hasClass('error')) $('p.feedback', form_name).removeClass('error');
  if($('p.feedback', form_name).hasClass('success')) $('p.feedback', form_name).removeClass('success');

  $('p.feedback', form_name).addClass(css_class).html(msg).show();
  // $('p.feedback', form_name).stop().fadeIn('fast', function(){
  //      $('p.feedback', form_name).delay(5000).fadeOut();
  //     // fade out again after 3 seconds
  // });
}

function reload(){
   //$("#grid").trigger("reloadGrid"); 
   $("#grid").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
}

//manual id get manual infot from veb_page
//should declare base_url()

function page_view(id){
    var data = { id: id};
      title = 'Техник ашиглалтын заавар';
      $.ajax({
         type:    'POST',
         url:    base_url+'/manual/index/get/'+id,
         data:   data,
         dataType: 'json', 
         success:  function(json) {
             // alert(json);
            //herev closed baival utguudiig haruulna   
               $("#manual", page_open).val(json.manual);
               $("#equipment", page_open).val(json.equipment);
               $('#doc_index', page_open).val(json.doc_index);
               $('#update_date', page_open).text(json.update_date);
             if(json.filename){
                 $('#_file', page_open).append("<span id='file_link'><a href='#' style='color:blue;' onclick='_file(\""+json.filename+"\")'>"+json.filename+"</a></span>");
             }else
                 $('#_file', page_open).append("<span id='file_link' style='color:red'>Файл байхгүй!</span>");
         }
      }).done(function() {
         page_open.dialog('option', 'title', title);
         switch($('#role').val()){
            case "ADMIN":
            page_open.dialog({  
              buttons: {             
                "Засах": function(){
                    //CALL OPEN EDIT DIALOG
                    page_open.dialog("close");
                    edit_dialog(id);

                 },
                 "Хаах": function () {
                     page_open.dialog("close");
                 }            
              }
           }); 
           break;
           //хэсгийн дарга
           case "CIEFENG":
             page_open.dialog({  
                buttons: {                 
                  "Засах": function(){
                      page_open.dialog("close");
                      edit_dialog(id);
                  },           
                  "Хаах": function () {
                       page_open.dialog("close");
                  }                  
                }
             }); 
              break;
           case "TECHENG":
             page_open.dialog({  
                buttons: {
                  "Засах": function(){
                     page_open.dialog("close");    
                     edit_dialog(id);
                  },
                  "Хаах": function () {
                      page_open.dialog("close");
                  }                  
                }
             }); 
             break;
             //headman, eng, headman
             default: 
               page_open.dialog({  
                  buttons: {             
                    "Хаах": function () {
                        page_open.dialog("close");
                    }                  
                  }
               }); 
             break;
         }
         page_open.dialog('open');
      });
}

//edit dialog comes out
function edit_dialog(_id){
    var data = { id: _id};
    title = 'Техник ашиглалтын заавар: Засах';
    //ajax-с утгуудыг авч inputed- харуулах
    // дараа нь 
    $.ajax({
       type:    'POST',
       url:    base_url+'/manual/index/get/'+_id,
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          //herev closed baival utguudiig haruulna   
            $("#id", edit).val(_id);
            $('#section_id option[value='+json.section_id+']', edit).attr('selected', 'selected');
            $('#equipment_id option[value='+json.equipment_id+']', edit).attr('selected', 'selected');
            $('#manual', edit).val(json.manual);
            $('#doc_index', edit).val(json.doc_index);
            $('#edit_update_date').val(json.update_date);
            if(json.filename){
               $('#manual_file', edit).hide();
               $("#uploaded_file", edit).val(json.filename);
               $('#_file', edit).append("<span id='file_link'><a href='#' style='color:blue;' onclick='_file(\""+json.filename+"\")'>"+json.filename+"</a> (<a href='#' style='color:red' onclick='del_file("+_id+",\""+json.filename+"\", edit)'> Устгах </a>)</span>");
            }else       
               if(('#manual_file',edit).is(':hidden')) $('#manual_file', edit).show();
       }
    }).done(function() {
       edit.dialog('option', 'title', title);
       edit.dialog({ 
          buttons: {  
            "Хадгалах": function(){                     
                //here call ajax to send edit
                //collect data here
                var data = {id:id};
                var inputs = $('input[type="text"], input[type="hidden"], select, textarea', edit);

                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });

                $.ajax({
                    type:     'POST',
                    url:    base_url+'/manual/index/edit/',
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
                 reload();
             }
          }
       }); 
       edit.dialog('open');
    });
}

//file haruulah heseg
function _file(filename){
    if(filename){
        window.open(base_url+"/pdf/web/viewer.html?file=../../download/taz_files/"+filename, '_blank');
    }else{
       feeds('error', 'Энэ тоног төхөөрөмжийн гэрчилгээний файлыг хавсаргаагүй тул харуулах боломжгүй!');
    }
}

function del_file(id, file, form){
  if (confirm("["+file+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',
         url:    base_url+'/manual/index/del_file/'+id,
         data:   {id:id, file_name:file},
         dataType: 'json', 
         success:  function(json) {
            if(json.success){
              //then remove link
              feeds('success', json.message)
              $('#file_link', form).remove();
              $('#uploaded_file', form).val();
              $("#manual_file", form).show();
            }else{
              feeds('error', json.message)
              $('#file_link', form).remove();
              $("#manual_file", form).show();
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

// taz called dialog here
function add_new (){
    //form called here
    title = 'Техник ашиглалтын заавар:Шинэ';
    new_form.dialog('option', 'title', title);
    new_form.dialog({
        buttons: {
            "Нэмэх": function(){
                var data = {};
                //here call ajax to send edit collect data here
                var inputs = $('input[type="text"], input[type="hidden"], select, textarea', new_form);

                inputs.each(function(){
                    var el = $(this);
                    data[el.attr('name')] = el.val();
                });

                $.ajax({
                    type:     'POST',
                    url:    base_url+'/manual/index/add/',
                    data:   data,
                    dataType: 'json',
                    success:  function(json){
                        if (json.status == "success") {
                            // if ajax return success
                            new_form.dialog('close');
                            // close the dialog
                            showMessage(json.message, 'success');
                            // show the success message
                            reload();
                        }
                        else{  // ямар нэг юм нэмээгүй тохиолдолд
                            $('p.feedback', new_form).removeClass('success').addClass('error').html(json.message).show();
                        }
                    }
                });
                  //new_form.dialog("close");
            },
            "Хаах": function () {
                new_form.dialog("close");
            }
        }
    });
    new_form.dialog('open');


}

