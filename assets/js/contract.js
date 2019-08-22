$(function() {
    sel_str=set_type();
    contract = $("#contract");
    contract.dialog({
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
       }
   }); 

    jQuery("#grid").jqGrid({        
        url:base_url+'/contract/index/grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',         
        width:'1260' ,
        colNames:['#','Гэрээний №','Төрөл', 'Гэрээ', 'Талууд', 'filename', 'Батлагдсан/t', 'Дуусах/t', 'Төлбөрийн баримт', 'Үйлдэл'],
        colModel:[
          {name:'id',index:'id',search:false, width:20},
          {name:'contract_no',index:'contract_no', width:40, align:"center"},    
          {name:'category',index:'category', width:70,align:"left", stype:'select', searchoptions:{value:sel_str}},
          {name:'title',index:'title', width:220, align:"left", formatter:view_link},
          {name:'sides',index:'sides', width:150,align:"right"},          
          {name:'filename',index:'filename', hidden: true},
          {name:'approved',index:'approved', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },   
          {name:'expireddate',index:'expireddate', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},   
          {name:'invoice_file',index:'invoice_file', width:65},
          {name:'action',index:'action',width:40, align:'center',sortable:false,search:false, formatter:t_action},
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
        sortname: 'category',
        viewrecords: true,
        sortorder: "asc",
        caption:".::+ Гэрээний бүртгэл +::.",        
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){ 


              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);

              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
              
              trElement.addClass('context-menu'); 

              if(check_date(rowData.expireddate)<=30&&rowData.expireddate.length>0){
                  trElement.removeClass('ui-widget-content');
                  trElement.addClass('argent'); //red                
              }              

              if(check_date(rowData.expireddate)>=30&&check_date(rowData.expireddate)<=60&&rowData.expireddate.length>0){
                 trElement.removeClass('ui-widget-content');
                 trElement.addClass('warning');                  
              }
           }         
       } 

    });
    // jQuery("#grid").jqGrid('hideCol','sides');
    // jQuery("#grid").jqGrid('showCol','sides');

    //gird 2
      jQuery("#grid2").jqGrid({        
        url:base_url+'/contract/index/archive',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260' ,
        colNames:['#','Харьяа хэсэг', 'Гэрээний №', 'Гэрээ', 'Талууд', 'filename', 'Үйлдэл'],
        colModel:[
          {name:'id',index:'id',search:false, width:20},
          {name:'section',index:'section', width:40, align:"center"},    
          {name:'contract_no',index:'contract_no', width:40, align:"center"},    
          {name:'title',index:'title', width:220, align:"left", formatter:archive_link},
          {name:'sides',index:'sides', width:150,align:"right"},          
          {name:'filename',index:'filename', hidden: true},          
          {name:'action',index:'action',width:40, align:'center',sortable:false,search:false, formatter:archive_action},
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
        caption:" Гэрээний архив ",
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           
           for (var i=0;i<rowIds.length;i++){ 
              
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));


              if(check_date(rowData.expireddate)<=30&&rowData.expireddate.length>0){
                  trElement.removeClass('ui-widget-content');
                  trElement.addClass('argent'); //red                
              }              

              if(check_date(rowData.expireddate)>=30&&check_date(rowData.expireddate)<=60&&rowData.expireddate.length>0){
                 trElement.removeClass('ui-widget-content');
                 trElement.addClass('warning');  

              }

               
           }  
       } 

    });
      //grid2 navigation 
       jQuery("#grid2").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true, defaultSearch:'cn'});
    
    //  here is calling jquery training field
    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
     jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true, defaultSearch:'cn'});


     // grid_freq davtagmiin grid
    //gird 2
    jQuery("#grid_freq").jqGrid({
        url:base_url+'/contract/index/archive',
        datatype: "json",
        mtype: 'GET',
        height: '500',
        width:'1260' ,
        colNames:['#','Харьяа хэсэг', 'Гэрээний №', 'Гэрээ', 'Талууд', 'Төлбөр баримт', 'Үйлдэл'],
        colModel:[
            {name:'id',index:'id',search:false, width:20},
            {name:'section',index:'section', width:40, align:"center"},
            {name:'contract_no',index:'contract_no', width:40, align:"center"},
            {name:'title',index:'title', width:220, align:"left", formatter:archive_link},
            {name:'sides',index:'sides', width:150,align:"right"},
            {name:'filename',index:'filename', hidden: true},
            {name:'action',index:'action',width:40, align:'center',sortable:false,search:false, formatter:t_action},
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
        caption:" Давтамжийн зөвшөөрөл ",
        loadComplete: function (){
            var rowIds = $(this).jqGrid('getDataIDs');
            for (var i=0;i<rowIds.length;i++){
                var rowData=$('#grid_freq').jqGrid('getRowData', rowIds[i]);
                var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
                if(check_date(rowData.expireddate)<=30&&rowData.expireddate.length>0){
                    trElement.removeClass('ui-widget-content');
                    trElement.addClass('argent'); //red
                }
                if(check_date(rowData.expireddate)>=30&&check_date(rowData.expireddate)<=60&&rowData.expireddate.length>0){
                    trElement.removeClass('ui-widget-content');
                    trElement.addClass('warning');
                }
            }
        }

    });
    //grid2 navigation
    jQuery("#grid_freq").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});

     //edit page 
      var appent_txt;     
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
     $('.year_dt').datepicker({
         dateFormat: 'yy,mm,dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     });     

});


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

function set_type(){   
  var str="";
  str =':Бүгд;Даатгалын:Даатгалын;Гадаад байгууллагууд:Гадаад байгууллагууд;Хамтран ажиллах гэрээ:Хамтран ажиллах гэрээ;Түрээсийн гэрээ:Түрээсийн гэрээ;Худалдан авалтын гэрээ:Худалдан авалт;Цахилгаан эрчим хүч, түүнтэй холбоотой:Цахилгаан эрчим хүч;Захиргаа:Захиргаа;Давтамжийн зөвшөөрөл:Давтамжийн зөвшөөрөл;Радиолокаторын:Радиолокаторын;Бусад:Бусад';  
  return str;
}

function archive_action (cellvalue, options, rowObject) {
    action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><a target='_blank' href='"+base_url+"/pdf/web/viewer.html?file=../../download/contract_archive_files/"+rowObject.filename+"' class='ui-icon ui-icon-extlink'></a></div></div>";
    var fields = $( "#action" ).serializeArray();
    $('input.action').each(function() {
        switch($(this).val()){
            case 'edit':
                action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-plus'><span onclick='init_edit("+rowObject.trainer_id+")' class='ui-icon ui-icon-wrench'></span></div></div>";
                break;
            case 'delete':
                action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+rowObject.trainer_id+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";
                break;
        }
    });
    return action_str;
}


function t_action (cellvalue, options, rowObject) {     
  
	   action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><a target='_blank' href='"+base_url+"/pdf/web/viewer.html?file=../../download/contract_files/"+rowObject.filename+"' class='ui-icon ui-icon-extlink'></a></div></div>";
     
     var fields = $( "#action" ).serializeArray();
     
	   $('input.action').each(function() {

	     switch($(this).val()){

	        case 'edit':
	            action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-icon-plus'><span onclick='init_edit("+rowObject.id+")' class='ui-icon ui-icon-wrench'></span></div></div>";
          break;	
                  
	        case 'delete':
	            action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='_delete("+rowObject.id+")' target ="+options.log_num+" class='ui-icon ui-icon-trash'></span></div></div>";    
          break;
          
      }
      
	   });
	   return action_str;
}

function init_view(filename) {
  //alert(id);
  //href='/ecns/pdf/web/viewer.html?file=../../download/contract_files/$row->filename'>".$row->title."</a>
  window.open(base_url+'/pdf/web/viewer.html?file=../../download/contract_files/'+filename+"'", '_blank');
//    echo "<a target='_blank' href='/ecns/pdf/web/viewer.html?file=../../download/contract_files/$row->filename'>".$row->title."</a>";
}

function init_edit(id) {
	//alert(id);
//	window.location.href='/ecns/training/index/edit/'+id;
  window.location.href=base_url+'/contract/settings/edit/'+id;
}

function view_link(cellValue, options, rowObject){
   return "<a target='_blank' href='"+base_url+"/pdf/web/viewer.html?file=../../download/contract_files/"+rowObject.filename+"' >"+cellValue+"</a>"; 
}
function archive_link(cellValue, options, rowObject){
   return "<a target='_blank' href='"+base_url+"/pdf/web/viewer.html?file=../../download/contract_archive_files/"+rowObject.filename+"' >"+cellValue+"</a>"; 
}

function _delete(id) {  
   var data = { id: id }; 
   var rowData = $("#grid").getRowData(id);   
    // ask confirmation before delete 
   if(window.confirm("Та '"+rowData.fullname+"' ИТА-ын бүртгэлийг устгахдаа итгэлтэй байна уу?")){
      $.ajax({
         type:    'POST',
         url:    base_url+'/training/index/delete/',
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                  // close the dialog                         
                 showMessage(json.message, 'success');
                // show the success message
                $("#grid").trigger("reloadGrid"); 
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

function outservice(contract_id){
  if (confirm(" Энэ гэрээг \"Ашиглалтаас хасахдаа\" итгэлтэй байна уу?") == true) {
    
      $.ajax({
         type:    'POST',
         url:    base_url+'/contract/index/outservice/'+contract_id,
         data:   {id:contract_id},
         dataType: 'json', 
         success:  function(json) {
            if(json.success){
              //then remove link
              showMessage(json.message, 'success');
                         // show the success message
              reload();            
              
            }else{

              
              showMessage(json.message, 'error');
              
              // show the success message
              reload();
            }
         }
       });
   
  } else {
     // nothing 
     return 0;
  }

}