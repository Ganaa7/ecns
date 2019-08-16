jQuery(document).ready(function(){ 
   jQuery("#order_grid").jqGrid({         // url changed
        url:base_url+'/wh_spare/index/jx_order',
        datatype: 'json',
        mtype: 'GET',
        colNames:['№', 'Зах.Огноо', 'Хэсэг', 'Төрөл', 'Захиалсан', 'Бүртгэгдсэн','Өдөр', 'Тэмдэглэл', 'Төлөв', 'Хариуцсан', 'Үйлдэл', 'status_id'],
        colModel :[             
          {name:'order_no', index:'order_no', width:20, align:'center'},           
          {name:'order_date', index:'order_date', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},                     
          {name:'section', index:'section', width:50, stype:'select', searchoptions:{value:':Бүгд;Хүний нөөц захиргаа:Хүний нөөц захиргаа;Инженеринг, сургалт:Инженеринг, сургалт;Хангамж:Хангамж;ААЧУ:ААЧУ;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ'} },       
          {name:'ordertype', index:'ordertype', width:40, stype:'select', searchoptions:{value:':Бүгд;Яаралтай:яаралтай;Хэвийн:хэвийн'}},
          {name:'orderby', index:'orderby', width:40},       
          {name:'registed_date', index:'registed_date', width:40, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },        
          {name:'diff_days', index:'diff_days', width:20, align:'center'},
          {name:'comment', index:'comment', width:200},
          {name:'status', index:'status', width:40,  stype:'select', searchoptions:{value:':Бүгд;Бүртгэгдсэн:Бүртгэгдсэн;Цуцлагдсан:Цуцлагдсан;Биелэсэн:Биелэсэн'}},
          {name:'steward', index:'steward', width:40},
          {name:'role', index:'role', width:60, formatter:orderAction},
          {name:'status_id', index:'status_id', hidden:true}      
        ],
         jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "id"
        },

        pager: jQuery('#order_pager'),
        rowNum:15,
        rowList:[10,20,30],
        sortname: 'order_id',
        sortorder: 'desc',
        viewrecords: true,
        caption: ' Захиалгийн жагсаалт',
        autowidth:true,
        height: '400',
        width:'100%',
        subGrid: true,
        subGridRowExpanded: function(subgrid_id, row_id) {        
           var subgrid_table_id;
           subgrid_table_id = subgrid_id+"_t";
           jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
           jQuery("#"+subgrid_table_id).jqGrid({
              url:base_url+'/wh_spare/index/jx_order_dtl/'+row_id,
              //url:"/ecns/wm_ajax/orderDtl?id="+row_id,
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
        },

        loadComplete: function (){

           var rowIds = $(this).jqGrid('getDataIDs'); 

             for (var i=0;i<rowIds.length;i++){ 

              var rowData=$('#order_grid').jqGrid('getRowData', rowIds[i]);

              var trElement = jQuery("#"+ rowIds[i],jQuery('#order_grid')); 

              // console.log(JSON.stringify(rowData));
              console.log(rowData.ordertype);

              if(rowData.ordertype =='яаралтай'){

                 trElement.removeClass('ui-widget-content');
                 trElement.addClass('argent');

              }

              switch(rowData.status){
                //create
                 case "Биелэсэн": 
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('lime-green');
                  break;                

                  case "Цуцлагдсан": 
                   trElement.removeClass('ui-widget-content');
                   trElement.addClass('warning');
                  break;
                                             
              }            
           }  

        }



    }).navGrid("#order_pager",{edit:false,add:false,del:false,multipleSearch:true});

  $('#order_grid').jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
   
   //хуучин Ерөнхий инженер, Хэсгийн даргаар үйлдлүүд ялгаатай байсан 
   function orderAction (cellvalue, options, rowObject) {
       var url_action='';
        console.log(rowObject.status_id);
       if(cellvalue==='WENG'){  
          // if(rowObject.childNodes[9].textContent)        
              // return '<a href ="#" onclick="cancelOrder('+options.rowId+')">Тэмдэглэл</a> | <a href = "#" onclick="deleteOrder('+options.rowId+')">Устгах</a>| <a href = "'+base_url+'/wh_spare/print_order/'+options.rowId+'">Харах</a>'; 

              // return "<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;'><span class='ui-icon ui-icon-extlink' onclick='cancelOrder("+options.rowId+")'>Тэмдэглэл</span></div><div title='Устгах' style='float:left;cursor:pointer;'><span class='ui-icon ui-icon-trash' onclick='deleteOrder("+options.rowId+")'>Устгах</span></div><div style='float:left;cursor:pointer;' title='Гүйцсэн'><span class='ui-icon ui-icon-star' onclick='setStatus("+options.rowId+")'></span></div><a title='Харах' href = '"+base_url+"/wh_spare/print_order/"+options.rowId+"'><span class='ui-icon ui-icon-print'></span></a>"; 

         // хэрэв 3-4 байвал цуцлагдсан гэж үзнэ! Үүнээс бусад дээр цуцлах үйлдлийг харуул
         if(rowObject.status_id!==3||rowObject.status_id!==4)        
            
            // return '<a href ="#" onclick="orderRegister('+options.rowId+')">Бүртгэх</a> | <a href ="#" onclick="cancelOrder('+options.rowId+')">Цуцлах</a> | <a href = "/ecns/warehouse/orderPrint">Харах</a>';      
             return "<div title='Тэмдэглэл хийх| Цуцлах' style='float:left;cursor:pointer;'><span class='ui-icon  ui-icon-comment' onclick='cancelOrder("+options.rowId+")'>Цуцлах</span></div><div title='Устгах' style='float:left;cursor:pointer;'><span class='ui-icon ui-icon-trash' onclick='deleteOrder("+options.rowId+")'>Устгах</span></div><div style='float:left;cursor:pointer;' title='Гүйцсэн'><span class='ui-icon ui-icon-star' onclick='setStatus("+options.rowId+")'></span></div><div title='Засах' style='float:left;cursor:pointer;'><a title='Засах' href = '"+base_url+"/wh_spare/edit_order/"+options.rowId+"'><span class='ui-icon ui-icon-pencil'></span></a></div><div><a title='Харах' href = '"+base_url+"/wh_spare/print_order/"+options.rowId+"'><span class='ui-icon ui-icon-print'></span></a></div>";

          // else if(rowObject.childNodes[9].textContent==='approved')        
          //    return '<a href ="#" onclick="orderRegister('+options.rowId+')">Бүртгэх</a> | <a href ="#" onclick="cancelOrder('+options.rowId+')">Цуцлах</a> | <a href = "/ecns/warehouse/orderPrint">Харах</a>';      
          // else if(rowObject.childNodes[0].textContent)
          //    return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Харах</a>';        
          // else 
          //    return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Харах</a>';        
       }
       // else if(cellvalue==='CHIEFENG'){
       //    if(rowObject.childNodes[9].textContent==='new')        
       //       return '<a href ="#" onclick="approveOrder('+options.rowId+')">Зөвшөөрөх</a> | <a href = "#" onclick="cancelOrder('+options.rowId+')">Цуцлах</a>'; 
       //    else 
       //       return '<a href = "/ecns/wm_report/printOrder/'+options.rowId+'">Харах</a>'; 
       // }
       else 
          //return '<a href = "'+base_url+'/wh_spare/print_order/'+options.rowId+'">Харах</a>';
          return "<a  title='Харах' href = '"+base_url+"/wh_spare/print_order/"+options.rowId+"'><span class='ui-icon ui-icon-print'></span></a>";
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
            $('#order_grid').setGridParam({url:base_url+"/wh_spare/index/jx_order", datatype:"xml", sortname:"order_no",sortorder:"asc"}).trigger("reloadGrid");
        }else{
            $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order/?spare='+spare, page: 1}).trigger("reloadGrid");
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
              text: "Бүртгэх",
              click: function() {
                //$('#').submit();
                $.post( base_url+'/wm_ajax/orderSet/register', { 
                   order_id:$('#order_id').val(),                      
                   comment:$('#cancel_comment').val(),
                   steward_id:$('#steward_id').val()
                }).done(function(data){                   
                    $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
                });                 
                $('#cancel_comment').val("");
                 $( this ).dialog( "close" );
              }
          },
          {
              text: "Цуцлах",
              click: function() {
                //$('#').submit();
                $.post( base_url+'/wm_ajax/orderSet/cancel', { 
                   order_id:$('#order_id').val(),                      
                   comment:$('#cancel_comment').val(),
                   steward_id:$('#steward_id').val()
                }).done(function(data){                   
                    $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
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
                  $.post( base_url+'/wm_ajax/orderSet/register', { 
                     order_id:$('#order_id').val(),                      
                     order_no:order_no.val(),
                     steward_id:$("#steward_id").val(),                      
                     regdate:regdate.val()
                  }).done(function(data){                   
                      $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
                      $.post( base_url+'/wm_ajax/getorderNo', function(data){
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
                $.post( base_url+'/wm_ajax/orderSet/cancel', { 
                   order_id:$('#order_id').val(),                      
                   comment:$('#cancel_comment').val()
                }).done(function(data){                   
                    $("#grid").jqGrid('setGridParam', { url: base_url+'/wm_ajax/order', page: 1}).trigger("reloadGrid");                      
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
    $.post( base_url+'/wm_ajax/getorderNo', function(data){
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
	   $.post( base_url+'/wm_ajax/orderDelete', { 
		  order_id:row_id
	   }).done(function(data){  
		   alert('№'+data+' дугаартай Захиалгыг амжилттай устгалаа!');
		   $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
	   }); 
   } else {
       // if pressed Cancel
	   // nothing to do
	   alert('Захиалга устгахад алдаа гарлаа!');
   }   
}

//Баталгаажуулах
function approveOrder(row_id){
   $.post( base_url+'/wm_ajax/orderSet/approve', { 
      order_id:row_id
   }).done(function(data){                   
      $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
   }); 
}

function setStatus(row_id){
  if (confirm("Энэ захиалгийн биелэлт гүйцсэн үү?") == true) {      
     $.post( base_url+'/wm_ajax/orderSet/finish', { 
        order_id:row_id
     }).done(function(data){                   
        $("#order_grid").jqGrid('setGridParam', { url: base_url+'/wh_spare/index/jx_order', page: 1}).trigger("reloadGrid");
     });    
  }  
}

function editOrder() {

  alert('hi edit Order');
  // body...
}

