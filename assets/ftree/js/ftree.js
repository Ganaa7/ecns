
var addForm, eventForm;

$(function() {
// var pageWidth = $(window).width();  
// var width = $('#main_wrap').width();   
  addForm= $('#addForm'); 
  addForm.dialog({
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
    

    jQuery("#grid").jqGrid({        
        url:base_url+'/ftree/index/grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260',
        colNames:['#','Тоног төхөөрөмж', 'Гэмтлийн мод', 'Хэсэг', 'Тасаг', 'Бүртгэсэн', 'Огноо/t',  'Үйлдэл'],
        colModel:[
          {name:'id',index:'equipment_id',search:false, width:20},
          {name:'equipment',index:'equipment', width:120, align:"left", formatter:view_link},    
          {name:'node',index:'node', width:100, formatter:view_link},
          {name:'section',index:'section', width:100,align:"left" ,formatter:view_link},
          {name:'sector',index:'sector', width:80, align:"left", formatter:view_link}, ///, formatter:view_link          
          {name:'updated_by',index:'updated_by', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },   
          {name:'updated_at',index:'updated_at', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},   
          {name:'action',index:'action',width:40, align:'center',sortable:false,search:false,formatter:t_action }, 
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
        sortname: 'section',
        viewrecords: true,
        sortorder: "asc",
        caption:".::+ Алдааны мод жагсаалт +::.",
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){ 
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));

           }         
       } 

    });
    
    //  here is calling jquery training field
    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
     jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true, defaultSearch:'cn'});

     //edit
    copyForm= $('#copyForm'); 
    copyForm.dialog({
     autoOpen: false,
       width: 400,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();                              
          $('#copy_txt').empty();
          $(this).dialog("close");
       }
   });
 

}); 
// end jquery

function t_action (cellvalue, options, rowObject) {     
     action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_view("+rowObject.id+")' class='ui-icon ui-icon-extlink'></span></div></div>";
     var fields = $( "#action" ).serializeArray();
     $('input.action').each(function() {
       switch($(this).val()){     
          case 'add':
              action_str=action_str+"<div title='Нэмэх' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-plus'><span onclick='action_add("+rowObject.id+")' class='ui-icon ui-icon-plus'></span></div></div>";
          break; 
          case 'copy':
              action_str=action_str+"<div title='Хуулах' style='float:left;cursor:pointer;'><span onclick='tree_copy("+rowObject.id+", \""+rowObject.equipment+"\")' class='ui-icon ui-icon-copy'></span></div></div>";
          break;
          case 'paste':
              action_str=action_str+"<div title='Буулгах' style='float:left;cursor:pointer;'><span onclick='tree_paste("+rowObject.id+",\""+rowObject.equipment+"\")' class='ui-icon ui-icon-document-b'></span></div></div>";
          break;          
          case 'delete':
              action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+rowObject.id+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
          break;
      }
     });
     return action_str;
}

function action_add(equipment_id){    
    //jquery add ajax by this equipment_id
    var data = {}; 
    data['equipment_id']=equipment_id;
    data['level']=1;

    //select by jquery
    $('#equipment').val(equipment_id);
    $('#event_id').val(1);

    addForm.dialog('option', 'title', 'Гэмтлийн мод үүсгэх');
    addForm.dialog({ 
     buttons: {
        "Хадгалах": function () {
           $('p.feedback', addForm).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
              var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', addForm);
              
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });

              if(data['closed_datetime']>data['created_datetime'])   data['duration_time']=1;
              else data['duration_time']=0;
              
              $.ajax({
                  type:     'POST',
                  url:    base_url+'/ftree/index/add_tree/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      // close the dialog                         
                      addForm.dialog('close');
                      // show the success message
                      showMessage(json.message, 'success');
                      
                      //not reload to go the tree                      
                        window.location.assign(base_url+'/ftree/tree/'+equipment_id);

                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', addForm).removeClass('success, notify').addClass('error').html(json.message).show();                        
                    }
                  }
              });// send the data via AJAX to our controller 
         },
        "Цуцлах": function () {
            addForm.dialog("close");
         }
      }
    }); 
  addForm.dialog('open');   
    //window.open('/ecns/flog/ftree_list/add/'+equipment_id);
   
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
      paragraph.delay(10000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}

// view link here 

function view_link(cellValue, options){
   if(cellValue)
      return "<a href='#' onclick='_view(" + options.rowId + ")' >"+cellValue+"</a>"; 
   else
      return "<a href='#' onclick='_view(" + options.rowId + ")' ></a>"; 

}

function _view(equipment_id){
  window.location.assign(base_url+'/ftree/tree/'+equipment_id);
}

function tree_copy(equipment_id, equipment){
  // set session copy_id = equioment id    
  var strconfirm = confirm(" ["+equipment+"] төхөөрөмжийг хуулахаар сонгох уу!");
  if(strconfirm==true){
    sessionStorage.setItem('copy_id', equipment_id); 
    sessionStorage.setItem('copy_eqt', equipment); 
  }
 return true;
}

function tree_paste(equipment_id, equipment){
  // set session copy_id = equioment id  
  if (sessionStorage.getItem("copy_id")) {      
        // Restore the contents of the text field      
        var copy_id = sessionStorage.getItem("copy_id");            
        var copy_eqt = sessionStorage.getItem("copy_eqt");            
        sessionStorage.clear();

        if(copy_id == equipment_id){
          alert("["+equipment+"]-ийг ["+copy_eqt+"] -дээр хуулах үйлдэл хийх боломжгүй! Өөр төхөөрөмж сонгож [Буулгах] үйлдэл хийнэ үү!");          
        }
        else{
            copyForm.dialog('open');   
            //call here dialog
            $('#copy_txt', copyForm).append("<div><b>["+copy_eqt+"]</b> -ийн Алдааны модыг <br /> <b>["+equipment+"]</b> -ийн Алдааны мод болгож хуулахдаа итгэлтэй байна уу!</div>");
            copyForm.dialog('option', 'title', 'Гэмтлийн мод хуулах');
            copyForm.dialog({ 
              buttons: {
                "Хуулах": function () {
                  //$('p.feedback', copyForm).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();                  
                  $('#copy_txt', copyForm).prepend('<img id="copyImg" src="'+base_url+'/images/copy.gif" />')
                  // logical function here                  
                  $.post( base_url+'/ftree/index/copy', {copy_id:copy_id, target_id:equipment_id}, function(data, status) {              
                    if(status=='success'){
                        if(data.status=='success'){
                          // grid reload                                                    
                          copyForm.dialog('close');
                          $("#grid").trigger("reloadGrid"); 
                          showMessage(data.message, 'success');
                        }
                        else{
                           copyForm.dialog('close');
                           showMessage(data.message, 'error');
                        }
                    }             
                  }); 
                },
                "Хаах": function () {
                    copyForm.dialog("close");
                 }
              }
            }); 
        }      
      
 }else{
    alert('Хуулах төхөөрөмжөө сонгоогүй байна! ');
 }
}

