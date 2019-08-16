$(document).ready(function(){ 
   var grid =$("#grid");
   grid.jqGrid({
     url:base_url+'/wm_ajax/expense',
     datatype: 'xml',
     mtype: 'GET',
     colNames:['Зарлагын №','Огноо', 'Зориулалт','Хэсэг','Нярав','Хүлээн авсан','Шалгасан Нябо', 'Үйлдэл'],
     colModel :[ 
       {name:'expense_no', index:'expense_no', width:22, searchoptions: { sopt: ['eq', 'ne'] }, search:true, align:'right'}, 
       {name:'expense_date', index:'expense_date', width:25, align:'right'}, 
       {name:'intend', index:'intend', width:120 }, 
       {name:'section', index:'section', width:30},       
       {name:'storeman', index:'storeman', width:30}, 
       {name:'receiveby', index:'receiveby', width:30},           
       {name:'checkby', index:'checkby', width:30},
       {name:'action', width:30, index:"action", align:'right', formatter:inc_action}
     ],
     pager: jQuery('#pager'),
     rowNum:15,
     rowList:[15,20,30],
     sortname: 'expense_no',
     sortorder: 'desc',
     viewrecords: true,
     caption: 'Зарлага бүртгэл',
     autowidth:true,
     height: '500',
     width:'100%',
     subGrid: true,
     subGridRowExpanded: function(subgrid_id, row_id) {
        var subgrid_table_id;
        subgrid_table_id = subgrid_id+"_t";
        jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
        jQuery("#"+subgrid_table_id).jqGrid({
           url:base_url+"/wm_ajax/expensedtl?q=2&id="+row_id,
           datatype: "xml",
           colNames: ['#','Сэлбэг, материал','Хэмжих нэгж','Тоо/ш'],
           colModel: [
              {name:"id",index:"id",width:80,key:true, align:"right"},
             {name:"spare",index:"spare",width:200},
             {name:"measure",index:"measure",width:100,align:"right"},
             {name:"qty",index:"qty",width:80,align:"right"}
//                {name:"action",index:"action", align:"right", formatter:expenseAction}
           ],
           height: '100%',
           width:600,
           rowNum:20,
           sortname: 'expense_no',
           sortorder: "asc"
        });
     }
 }).navGrid("#pager",{edit:false,add:false,del:false,search:true});     
 

 // Хайлтын товч
 $("#searchbtn").click(function(){
     jQuery("#grid").jqGrid('searchGrid',
             {sopt:['eq','ne', 'cn','bw','lt','gt','ew']}
     );
 });

 function expenseAction (cellvalue, options, rowObjcet) {
    if(cellvalue=='delete')
       return '<a href = "'+base_url+'/warehouse/expensedel/' + options.rowId + '">Устгах</a>';
    else
        return '';
 }

 $('#search').click(function (){
     var spare_id=$('#spare_id').val(), spare=$("#spare").val();
     if(spare_id==0&&(spare=!null||spare!="")){
         alert("Ийм сэлбэг байхгүй байна!");
     }else{
        if((spare==null||spare=="")&spare_id!=0){
           spare_id=0;              
        }
        grid.jqGrid('setGridParam', { url: base_url+'/wm_ajax/expense?spare_id='+spare_id, page: 1}).trigger("reloadGrid");
     }
 })   
    /* $('#grid').jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false }); */
}); 
function inc_action (cellvalue, options, rowObject) {        
   return "<button onclick='invoiceDel("+options.rowId+")'>Устгах</button>";
}

 function invoiceDel(invoice_id){    
    var r = confirm("Та, энэ зарлага устгахдаа итгэлтэй байна уу!");
    if (r == true) {
        window.location.assign(base_url+"/warehouse/invoiceDel/"+invoice_id+"/no");
    }    
 }
