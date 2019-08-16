jQuery(document).ready(function(){ 
   var grid =$("#grid");
    grid.jqGrid({      
      url:base_url+'/wm_ajax/income',
      datatype: 'xml',
      mtype: 'GET',
      colNames:['Орлогын №','Огноо', 'Гүйлгээний утга','Нийлүүлэгч','Нягтлан','Нярав','Эхний үлдэгдэл', 'Үйлдэл'],
      colModel :[ 
        {name:'income_no', index:'income_no', width:20, align:'right'}, 
        {name:'income_date', index:'income_date', width:25, align:'right'}, 
        {name:'purpose', index:'purpose', width:120}, 
        {name:'supplier', index:'supplier', width:40},       
        {name:'accountant', index:'accountant', width:30}, 
        {name:'storeman', index:'storeman', width:30},           
        {name:'isbalance', index:'isbeginbalance', width:30, align:'right'},
        {name:'action', width:30, index:"action", align:'right', formatter:inc_action}
      ],
      pager: jQuery('#pager'),
      rowNum:15,
      rowList:[15,20,30],
      sortname: 'invoice_id',
      sortorder: 'desc',
      viewrecords: true,
      caption: 'Орлого бүртгэл',
      autowidth:true,
      height: 400,
      width:'100%',
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {
         var subgrid_table_id;
         subgrid_table_id = subgrid_id+"_t";
         jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
         jQuery("#"+subgrid_table_id).jqGrid({
            autoencode: false,
            url:base_url+"/wm_ajax/incomeDtl?q=2&id="+row_id,
            datatype: "xml",
            colNames: ['#','Сэлбэг, материал','Хэмжих нэгж','Тоо/ш','Үйлдэл'],
            colModel: [
              {name:"id",index:"id",width:80,key:true, align:"right"},
              {name:"spare",index:"spare",width:200},
              {name:"measure",index:"measure",width:100,align:"right"},
              {name:"qty",index:"qty",width:80,align:"right"},
              {name:"action",index:"action", align:"right"}
            ],
            height: '100%',
            width:600,
            rowNum:20,
            sortname: 'num',
            sortorder: "asc"
         });
      }
    }).navGrid("#pager",{edit:false,add:false,del:false,search:true});     
    
  
    $('#search').click(function (){
       var spare_id=$('#spare_id').val(), spare=$("#spare").val();
       if(spare_id==0&&(spare=!null||spare!="")){
          alert("Ийм сэлбэг байхгүй байна!");
       }else{
          if((spare==null||spare=="")&spare_id!=0){
             spare_id=0;              
          }
          grid.jqGrid('setGridParam', { url: base_url+'/wm_ajax/income?spare_id='+spare_id, page: 1}).trigger("reloadGrid");
       }
    })

    
 
    
}); 
 

  function inc_action (cellvalue, options, rowObject) {        
      return "<button onclick='invoiceDel("+options.rowId+")'>Устгах</button>";
   }

   function invoiceDel(invoice_id){    
      var r = confirm("Та, энэ орлогийг устгахдаа итгэлтэй байна уу!");
      if (r == true) {
          window.location.assign(base_url+"/warehouse/invoiceDel/"+invoice_id);
      }    
   }

