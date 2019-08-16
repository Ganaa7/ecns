/* 
 * Created by Ganaa
 * 2017-08-11
 */
$(document).ready(function(){
   jqgrid();
});
    
function jqgrid(){
    $("#grid").jqGrid({ 
        url:'/ecns/flog/index/grid',
        datatype: 'xml', 
        mtype: 'GET', 
        colNames:['Зэрэглэл', 'Гэмтэл №',  'Хэсэг', 'Нээсэн / t', 'Байршил', 'Төхөөрөмж',  'Төрөл', 'Гэмсэн модуль, дэд хэсэг','Хаасан / t','Үргэлж/цаг', 'Гүйцэтгэл', 'Үйлдэл',  'Closed', 'equipment_id'], 
        colModel :[ 
                    {name:'q_level', index:'q_level', width:60, align:'center' }, 
                    {name:'log_num', index:'log_num', width:50, align:'center'}, 
                    {name:'section', index:'section', width:80, stype:'select', searchoptions:{value:sel_str} },                     
                    {name:'created_dt', index:'created_dt', width:80, searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
                    {name:'location', index:'location', width:60, align:'center', formatter:view_link },                     
                    {name:'equipment', index:'equipment', width:90,  formatter:view_link},
                    {name:'log_type', index:'log_type', width:70,  formatter:view_link},
                    {name:'node', index:'node', width:120, align:'left', formatter:view_link, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']} },    
                    {name:'closed_dt', index:'closed_dt', width:80, align:'right', searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } }, 
                    {name:'duration', index:'duration', width:50, align:'center'}, 
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
    jQuery("#grid").jqGrid('filterToolbar',{searchOperators : true, stringResult: true});  
    
}


