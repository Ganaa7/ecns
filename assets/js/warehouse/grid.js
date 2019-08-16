$(document).ready(function(){ 
    var grid =$("#grid");
    grid.jqGrid({
      url:base_url+'/wm_ajax/invoice',
      datatype: 'xml',
      mtype: 'GET',
      colNames:['Сэлбэг №', 'Огноо', 'Сэлбэг', 'Төрөл', 'Парт №','Хэмжих нэгж','Үлдэгдэл','Төхөөрөмж','Тасаг','Хэсэг'],
      colModel :[             
        {'name':'spare_id', index:'spare_id', width:30, align:'right'},           
        {'name':'enddate', index:'enddate', width:40, align:'center'},                     
        {'name':'spare', index:'spare', width:100},       
        {'name':'sparetype', index:'sparetype', width:50},       
        {'name':'part_number', index:'part_number', width:40},          
        {'name':'measure', index:'measure', width:20, align:'center'},          
        {'name':'endQty', index:'endQty', width:50, align:'center'},
        {'name':'equipment', index:'equipment', width:70},           
        {'name':'sector', index:'sector', width:50},  
        {'name':'section', index:'section', width:50} 
      ],
      pager: jQuery('#pager'),
      rowNum:15,
      rowList:[15,30,50, 100],
      sortname: 'spare_id',
      sortorder: 'desc',
      viewrecords: true,
      caption: 'Агуулахын бүртгэл',
      autowidth:true,
      height: '400',
      width:'100%',
      loadComplete: function (){
         var rowIds = $(this).jqGrid('getDataIDs');          
         for (var i=0;i<rowIds.length;i++){ 
            var rowData=grid.jqGrid('getRowData', rowIds[i]);            
            var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
//            console.log(rowData);
          if(rowData.endQty == 0) { 
            trElement.removeClass('ui-widget-content');
            trElement.addClass('tr_error');
          }else{ 
              //1 их 3 аас бага тохиолдолд
             if (rowData.endQty >= 1 &&rowData.endQty <= 3 ){
                trElement.removeClass('ui-widget-content');
                trElement.addClass('tr-warning');
            }
          }
        }         
      },
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {    
         var subgrid_table_id;
         subgrid_table_id = subgrid_id+"_t";
         jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
         jQuery("#"+subgrid_table_id).jqGrid({
            url:base_url+"/wm_ajax/invDetail?q=2&id="+row_id,
            datatype: "xml",
            colNames: ['#','Агуулах','Тавиур','Тоо хэмжээ'],
            colModel: [
              {name:"id",index:"id",width:80,key:true},
              {name:"warehouse",index:"warehouse",width:130},
              {name:"pallet",index:"pallet",width:80,align:"right"},
              {name:"qty",index:"qty",width:80,align:"right"}
            ],
            height: '100%',
            width:600,
            rowNum:20,
            sortname: 'id',
            sortorder: "asc"
         });
      }
    }).navGrid("#pager",{edit:false,add:false,del:false,multipleSearch:true});     
    
    $("#searchbtn").click(function(){          
	grid.jqGrid('searchGrid',
		{sopt:['eq','ne', 'cn','bw','lt','gt','ew']}
	);
    });
    grid.jqGrid('navGrid',{searchOperators :true},{del:false,add:false,edit:false},{},{},{},{multipleSearch:true} );

    
    //filter here
    $('#filterBy').click(function (){
       var section_id=$('#section_id').val(), sector_id=$("#sector_id").val(), equipment_id=$("#equipment_id").val();
       if(section_id!="0"||sector_id!="0"||equipment_id!="0"){          
          grid.jqGrid('setGridParam', { url:base_url+'/wm_ajax/invoice/?section_id='+section_id+'&sector_id='+sector_id+'&equipment_id='+equipment_id, page: 1, search:true }).trigger("reloadGrid");
       }else{
          grid.jqGrid('setGridParam', { url:base_url+'/wm_ajax/invoice/wm_ajax', page: 1, search:false }).trigger("reloadGrid");
       }
    });
    
    $("#search_grid").click(function(){
        grid.setGridParam({url:base_url+"/wm_ajax/invoice", datatype:"xml", sortname:"spare_id",sortorder:"asc"}).trigger("reloadGrid");        
        $('#section_id').prop('selectedIndex',0);
        $('#sector_id').prop('selectedIndex',0);
        $('#equipment_id').prop('selectedIndex',0);
    });   
}); 

