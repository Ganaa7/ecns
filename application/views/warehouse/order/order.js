jQuery(document).ready(function(){ 
   jQuery("#order_grid").jqGrid({
        url:'/ecns/wm_ajax/order',
        datatype: 'xml',
        mtype: 'GET',
        colNames:['Захиалга №', 'Зах.Огноо', 'Хэсэг', 'Захиалсан', 'Бүртгэгдсэн','Тэмдэглэл', 'Төлөв', 'Хариуцсан', 'Үйлдэл', 'status_id'],
        colModel :[             
          {name:'order_no', index:'order_no', width:30, align:'right'},           
          {name:'order_date', index:'order_date', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},                     
          {name:'section', index:'section', width:70, stype:'select', searchoptions:{value:':Бүгд;Хүний нөөц захиргаа:Хүний нөөц захиргаа;Инженеринг, сургалт:Инженеринг, сургалт;Хангамж:Хангамж;ААЧУ:ААЧУ;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ'} },       
          {name:'orderby', index:'orderby', width:40},       
          {name:'registed_date', index:'registed_date', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },        
          {name:'comment', index:'comment', width:100},
          {name:'status', index:'status', width:50},
          {name:'steward', index:'steward', width:50},
          {name:'role', index:'role', width:60, formatter:orderAction},
          {name:'status_id', index:'status_id', hidden:true}      
        ],
        pager: jQuery('#order_pager'),
        rowNum:15,
        rowList:[10,20,30],
        sortname: 'order_id',
        sortorder: 'desc',
        viewrecords: true,
        caption: 'Агуулахын бүртгэл',
        autowidth:true,
        height: '400',
        width:'100%',
        subGrid: true,
        subGridRowExpanded: function(subgrid_id, row_id) {        
           var subgrid_table_id;
           subgrid_table_id = subgrid_id+"_t";
           jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
           jQuery("#"+subgrid_table_id).jqGrid({
              //url:"/ecns/wm_ajax/orderDtl?id="+row_id,
               url:
              datatype: "xml",
              colNames: ['#','Сэлбэг, Бараа','Хэмжих нэгж','Тоо ширхэг', 'Шаардах үндэслэл'],
              colModel: [
                {name:"id",index:"id",width:80,key:true},
                {name:"spare",index:"spare",width:130},                
                {name:"measure",index:"measure",width:80},
                {name:"qty",index:"qty",width:80,align:"right"},                
                {name:"reason",index:"reason",width:300}
              ],
              height: '100%',
              width:800,
              rowNum:20,
              sortname: 'id',
              sortorder: "asc"
           });
        }
    }).navGrid("#order_pager",{edit:false,add:false,del:false,multipleSearch:true});

  $('#order_grid').jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
   
   //хуучин Ерөнхий инженер, Хэсгийн даргаар үйлдлүүд ялгаатай байсан 
   function orderAction (cellvalue, options, rowObject) {
       var url_action='';
       console.log(rowObject);
       if(cellvalue==='WENG'){  
          if(rowObject.childNodes[9].textContent)        
              return '<a href ="#" onclick="cancelOrder('+options.rowId+')">Тэмдэглэл</a> | <a href = "#" onclick="deleteOrder('+options.rowId+')">Устгах</a>| <a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Хэвлэх</a>'; 
          // else if(rowObject.childNodes[9].textContent==='approved')        
          //    return '<a href ="#" onclick="orderRegister('+options.rowId+')">Бүртгэх</a> | <a href ="#" onclick="cancelOrder('+options.rowId+')">Цуцлах</a> | <a href = "/ecns/warehouse/orderPrint">Хэвлэх</a>';      
          // else if(rowObject.childNodes[0].textContent)
          //    return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Хэвлэх</a>';        
          // else 
          //    return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Хэвлэх</a>';        
       }
       // else if(cellvalue==='CHIEFENG'){
       //    if(rowObject.childNodes[9].textContent==='new')        
       //       return '<a href ="#" onclick="approveOrder('+options.rowId+')">Зөвшөөрөх</a> | <a href = "#" onclick="cancelOrder('+options.rowId+')">Цуцлах</a>'; 
       //    else 
       //       return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Хэвлэх</a>'; 
       // }
       else 
          return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Хэвлэх</a>';
    } 
    
    $("#searchbtn").click(function(){
    	jQuery("#order_grid").jqGrid('searchGrid',
    		{sopt:['eq','ne', 'cn','bw','lt','gt','ew']}
    	);
    });

    jQuery("#order_grid").jqGrid('navGrid','#order_grid',{del:false,add:false,edit:false},{},{},{},{multipleSearch:true});

    // search button clicked    
    $('#search').click(function (){
        var spare=$("#spare").val();
        if(spare===null||spare===""){
            alert('Сэлбэгийн нэрээ бичиж хайна уу!');
            $('#order_grid').setGridParam({url:"/ecns/wm_ajax/order", datatype:"xml", sortname:"order_no",sortorder:"asc"}).trigger("reloadGrid");
        }else{
            $("#order_grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order?spare='+spare, page: 1}).trigger("reloadGrid");
        }
    });
    
    // call dialogs   
    $( "#regdate" ).datepicker({ dateFormat: "yy-mm-dd" });
    var comment, order_no=$('#orderno'), regdate=$('#regdate');

    // cancel order dialog and actions
    $( "#dialog_c" ).dialog({
       autoOpen: false,
       width: 340,
       modal:true,
       buttons: [
          {
              text: "Хадгалах",
              click: function() {
                //$('#').submit();
                $.post( '/ecns/wm_ajax/orderSet/register', { 
                   order_id:$('#order_id').val(),                      
                   comment:$('#cancel_comment').val(),
                   steward_id:$('#steward_id').val()
                }).done(function(data){                   
                    $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order', page: 1}).trigger("reloadGrid");                      
                });                 
                $('#cancel_comment').val("");
                 $( this ).dialog( "close" );
              }
          },
          {
              text: "Болих",
              click: function() {
                 $( this ).dialog( "close" );
              }
          }
         ]
       });
    
    // Register Dialog
    $( "#dialog_r" ).dialog({
       autoOpen: false,
       width: 340,
       modal:true,
       buttons: [
         {
            text: "Хадгалах",
            click: function() {
               //validate for dialog register
               if( order_no.val()===null||order_no.val()===''||regdate.val()===null||regdate.val()===''){
                   alert('Захиалгийн дугаар эсвэл Огноо хоосон байна. Утгуудыг оруулна уу!');
               }else{
                  //$('#orderstatus').submit();
                  //post value by ajax and reset grid...
                  $.post( '/ecns/wm_ajax/orderSet/register', { 
                     order_id:$('#order_id').val(),                      
                     order_no:order_no.val(),
                     steward_id:$("#steward_id").val(),                      
                     regdate:regdate.val()
                  }).done(function(data){                   
                      $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order', page: 1}).trigger("reloadGrid");
                      $.post( '/ecns/wm_ajax/getorderNo', function(data){
                         $('#orderno').val(data.order_no);
                      });
                  });
                  $( this ).dialog( "close" );
               }
            }
         },
         {
            text: "Болих",
            click: function() {
               $( this ).dialog( "close" );
            }
         }
         ]
       });
     //close dialog    

    // Delete Dialog
    $( "#dialog_d" ).dialog({
        autoOpen: false,
       width: 340,
       modal:true,
       buttons: [
          {
              text: "Хадгалах",
              click: function() {
                //$('#').submit();
                $.post( '/ecns/wm_ajax/orderSet/cancel', { 
                   order_id:$('#order_id').val(),                      
                   comment:$('#cancel_comment').val()
                }).done(function(data){                   
                    $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order', page: 1}).trigger("reloadGrid");                      
                });                 
                $('#cancel_comment').val("");
                 $( this ).dialog( "close" );
              }
          },
          {
              text: "Болих",
              click: function() {
                 $( this ).dialog( "close" );
              }
          }
         ]
       });
     //close dialog    
      
    //get row_id;
    $.post( '/ecns/wm_ajax/getorderNo', function(data){
         $('#orderno').val(data.order_no);
      });
}); 

function orderRegister(row_id){   
   $("#order_id").val(row_id);
   $("#dialog_r").dialog( "open" );
}

function cancelOrder(row_id){   
   $("#order_id").val(row_id);
   $("#dialog_c").dialog( "open" );
}

// Захиалга устгах 
function deleteOrder(row_id){   
   $("#order_id").val(row_id);
   //alert('Устгах захиалга'+row_id);
   if (confirm("Та энэ захиалгийг устгахдаа итгэлтэй байна уу!") == true) {       
	   //call ajax post to send order_id > delete it.	   
	   // refresh grid
	   $.post( '/ecns/wm_ajax/orderDelete', { 
		  order_id:row_id
	   }).done(function(data){  
		   alert('№'+data+' дугаартай Захиалгыг амжилттай устгалаа!');
		   $("#grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order', page: 1}).trigger("reloadGrid");                      
	   }); 
   } else {
       // if pressed Cancel
	   // nothing to do
	   alert('Захиалга устгахад алдаа гарлаа!');
   }   
}

//Баталгаажуулах
function approveOrder(row_id){
   $.post( '/ecns/wm_ajax/orderSet/approve', { 
      order_id:row_id
   }).done(function(data){                   
      $("#order_grid").jqGrid('setGridParam', { url: '/ecns/wm_ajax/order', page: 1}).trigger("reloadGrid");
   }); 
}

