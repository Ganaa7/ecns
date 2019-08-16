var view_dialog;
var today = new Date().toString('yyyy-MM-dd');
var d = new Date();
var curr_date = d.getDate();
var curr_month = d.getMonth() + 1; //Months are zero based
var curr_year = d.getFullYear();
var hour = d.getHours();
var min=d.getMinutes();

var barcode ;


my_date = $.datepicker.formatDate('yy-mm-dd', new Date());

$(document).ready(function(){ 
    var grid =$("#grid");
    var str_section=get_section();      
    barcode= $("#barcode").val();
     // view page here
    view_dialog = $("#view_dialog");
    view_dialog.dialog({
       autoOpen: false,
       width: 550,       
       resizable: false,    
       modal: true,
       position: ['center',100], 
       close: function () {          
          // clear & hide the feedback msg inside the form
          $('input[type="text"], input[type="hidden"], select, textarea', view_dialog).val('');
          $('#table_spare>tbody', view_dialog).empty();
          // clear the input values on form    
          $(this).dialog("close");
       }
    }); 

    grid.jqGrid({      
      url: base_url+'/wh_spare/index/grid',
      datatype: 'json',
      mtype: 'GET',
      colNames:['Хэсэг','Тасаг', 'Системийн нэр','Сэлбэг','Төрөл', 'Насжилт', 'Тоо/ш','Үнэ/нэгж ', 'Нийт үнэ'],
      colModel :[             
        {name:'section', index:'section', width:30, align:'center', stype:'select',
        searchoptions:{
          dataEvents: [{        //this triggers on change of department combo-box
                    type: 'change',                    
                    fn: function(e){
                        $('#barcode').val('');
                        $('#gs_years_old').val('');
                        $('#gs_spare').val('');
                        $('#gs_sparetype').val('');
                        $('#gs_qty').val('');
                        $('#gs_amt').val('');
                        $('#gs_total').val('');
                      //console.log();                                                
                         $.ajax({
                           data:{name:$(this).val(), flag:'yes'},
                           type:     'POST',
                           url:  base_url+'/wh_spare/index/get_eq_by',
                           async: false,
                         }).done(function(newOption){                           
                              var select = $('#gs_equipment_id');
                              if(select.prop) {
                                 var options = select.prop('options');
                              }else {
                                 var options = select.attr('options');
                              }
                              $('option', select).remove();
                              // equipment option remove
                              //$('option', '#vw_loc_equip_id').remove();
                              $.each(newOption, function(val, text) {
                                  options[options.length] = new Option(text, val);        
                              });
                         });
                       }
                            }],
          value:str_section
        }
      },        
        {name:'sector', index:'sector', width:30,  align:'center', stype:'select', 
        searchoptions:{
           dataEvents: [{        //this triggers on change of department combo-box
                    type: 'change',                    
                    fn: function(e){
                        $('#barcode').val('');
                      //console.log();                                             
                        var section_val = $('#gs_section').val();
                         $.ajax({
                           data:{name:$(this).val(), flag:'no', section:section_val},
                            type:     'POST',
                            async: false,
                           url:  base_url+'/wh_spare/index/get_eq_by'
                         }).done(function(newOption){                           
                              var select = $('#gs_equipment_id');
                              if(select.prop) {
                                 var options = select.prop('options');
                              }else {
                                 var options = select.attr('options');
                              }
                              $('option', select).remove();
                              // equipment option remove
                              //$('option', '#vw_loc_equip_id').remove();
                              $.each(newOption, function(val, text) {
                                  options[options.length] = new Option(text, val);        
                              });
                         });
                       }
                            }],
          value:get_sector()
        }
        },    
        {name:'equipment_id', index:'equipment_id', align:'left', width:80, stype:'select', searchoptions:{value:get_equipment()}}, 
        {name:'spare', index:'spare', width:80, formatter:_link},       
        {name:'sparetype', index:'sparetype', width:30, align:'center'},       
        // {name:'income_date', index:'income_date', width:30, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },         
        {
           name: 'years_old',
           index: 'years_old',
           width: 25,
           align: 'center',
           searchoptions: {
              sopt:['eq']           
           }
        },
        {
              name: 'qty',
              width: 20,
              summaryTpl: "Нийт: {0}", // set the summary template to show the group summary
              summaryType: "sum" // set the formula to calculate the summary type
          } ,
        {name:'amt', index:'amt', width:30, align:'left'},
               //,         group disabled 5-22
         {
               name: 'total',
               width: 30,
               summaryTpl: "Нийт: {0}", // set the summary template to show the group summary
               summaryType: "sum" // set the formula to calculate the summary type
               , 
                  formatter: function (cellval, opts, rwdat, act) {
                     if (opts.rowId === "") {
                        if (cellval > 1000) {
                           return '<span style="color:black">' +
                                 $.fn.fmatter('number', cellval, opts, rwdat, act) +
                                 '</span>';
                        } else {
                           return '<span style="color:red">' +
                                 $.fn.fmatter('number', cellval, opts, rwdat, act) +
                                 '</span>';
                        }
                     } else {
                        return $.fn.fmatter('number', cellval, opts, rwdat, act);
                     }
               }
      }
      ],
      ajaxGridOptions: {cache: false},
      pager: jQuery('#pager'),
      rowNum:20,
      rowList:[20,40,80, 100],
      sortname: 'spare_id',
      sortorder: 'asc',
      viewrecords: true,
      caption: 'Агуулахын үлдэгдэл: ['+my_date+']',
      autowidth:true,
      height: '400',
      footerrow: true, // the footer will be used for Grand Total
      userDataOnFooter: true, // show custom data from JSON response to the footer - the Grand Total
      grouping:false, //false 05-22
      // groupingView: 05-22
      //               {
      //                   groupField: [""],
      //                   groupColumnShow: [true],
      //                   groupText: ["<b>{0}</b>"],
      //                   groupOrder: ["asc"],
      //                   groupSummary: [true], // will use the "summaryTpl" property of the respective column
      //                   groupCollapse: false,
      //                   groupDataSorted: false
      //               },
      width:'100%',
            jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    // id: "spare_id"
        }, 

      loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){               
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));                                         
               trElement.addClass('context-menu');  
           }         
       }  

    }).navGrid("#pager",{edit:false,add:false,del:false,multipleSearch:false});     
    jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true,   beforeSearch: function () {
           $('#barcode').val('');}});
       
    
    $("#searchbtn").click(function(){          
            grid.jqGrid('searchGrid',
               {sopt:['eq','ne', 'cn','bw','lt','gt','ew']}
            );
    });
    
    // multipleSearch : false

    grid.jqGrid('navGrid',{searchOperators :false},{del:false,add:false,edit:false},{},{},{},{multipleSearch:false} );
 
    //filter here
    $('#filterBy').click(function (){
       var section_id=$('#section_id').val(), sector_id=$("#sector_id").val(), equipment_id=$("#equipment_id").val();
       if(section_id!="0"||sector_id!="0"||equipment_id!="0"){          
          grid.jqGrid('setGridParam', { url: base_url+'/wm_ajax/invoice/?section_id='+section_id+'&sector_id='+sector_id+'&equipment_id='+equipment_id, page: 1, search:true }).trigger("reloadGrid");
       }else{
          grid.jqGrid('setGridParam', { url: base_url+'/wm_ajax/invoice/wm_ajax', page: 1, search:false }).trigger("reloadGrid");
       }
    });
    
    $('#barcode_filter').click(function(){        
        if($('#barcode').inputmask('isComplete')){              
    //        console.log(barcode);
               barcode= $('#barcode').val();
            //grid.jqGrid('setGridParam', { url: '/ecns/wh_spare/index/grid?barcode='+barcode, page: 1, search:true }).trigger("reloadGrid");
           jQuery('#grid').jqGrid('setGridParam', { url: base_url+'/wh_spare/index/grid', page: 1, search:false, postData:{'filters': "", 'barcode': barcode}}).trigger("reloadGrid");    
        }else
            alert('Barcode-ын утга хоосон байна!');
//     
    });
    
    //barcode inputmask
    
    //alert("hi htere");
    var selector = document.getElementById("barcode");
    if(selector){
       var im = new Inputmask("099-099-9999999");
       im.mask(selector);              
    }
        
    $("#search_grid").click(function(){
        grid.setGridParam({url:base_url+"/wm_ajax/invoice", datatype:"xml", sortname:"spare_id",sortorder:"asc"}).trigger("reloadGrid");        
        $('#section_id').prop('selectedIndex',0);
        $('#sector_id').prop('selectedIndex',0);
        $('#equipment_id').prop('selectedIndex',0);
    });   

    function _action (cellvalue, options, rowObject) {     
       action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_none' onclick='init_view("+options.rowId+")' class='ui-icon ui-icon-extlink'></span></div></div>";
       var fields = $( "#action" ).serializeArray();
       
       $('input.action').each(function() {
         switch($(this).val()){
            case 'delete':
                action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='del_inv("+options.rowId+")' target ="+options.invoice_id+" class='ui-icon ui-icon-trash'></span></div></div>";    
            break;
            case 'income_edit':
             action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='income_edit("+options.rowId+")' target ="+options.invoice_id+" class='ui-icon ui-icon-pencil'></span></div></div>";
             break;
        }
       });
       return action_str;
    }


    //Орлогийн жагсаалт авах  
var grid_income =$("#grid_income");
    grid_income.jqGrid({      
      //url:'/ecns/wm_ajax/income',
      url:base_url+'/wh_spare/index/jx_income',
      datatype: 'json',
      mtype: 'GET',
      colNames:['#', 'Орлого №','Огноо', 'Гүйлгээнд авсан сэлбэг, материалууд', 'Нийт тоо','Нийт үнэ','Нийлүүлэгч', 'Нярав', 'Үйлдэл'],
      colModel :[                 
        {name:'invoice_id', index:'invoice_id', search:false, hidden:true}, 
        {name:'income_no', index:'income_no', width:20, align:'center'}, 
        {name:'income_date', index:'income_date', width:30, align:'left'},         
        {name:'spare', index:'spare', width:120, formatter:_link},               
        {name:'t_qty', index:'t_qty', width:30,  align:'center'},       
        {name:'t_amt', index:'t_amt', width:30,  align:'right'}, 
        {name:'supplier', index:'supplier', width:60,  align:'right'}, 
        {name:'storeman', index:'storeman', width:40,  align:'right'}, 
        {name:'action', width:30, index:"action", align:'right',formatter: _action }//,formatter:inc_action
      ],
      jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "invoice_id"
        },
      pager: jQuery('#pager'),
      rowNum:15,
      rowList:[20,25,40],
      sortname: 'income_date',
      sortorder: 'desc',
      viewrecords: true,
      caption: 'Орлого жагсаалт',
      autowidth:true,      
      width:'100%',
      height: '500',
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {
            var subgrid_table_id;
            subgrid_table_id = subgrid_id+"_t";
            jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
            jQuery("#"+subgrid_table_id).jqGrid({
               autoencode: false,
               url:base_url+"/wh_spare/index/jx_income_sub_dtl?q=2&id="+row_id,
               datatype: "xml",
               colNames: ['#',  'Сэлбэг','Төхөөрөмж','Тасаг', 'Хэсэг','Тоо/ш','Х/нэгж', 'Н/б Үнэ', 'Нийт'],
               colModel: [
                 {name:"id",index:"id",width:80,key:true, align:"right"},
                 {name:"spare",index:"spare",width:240},
                 {name:"equipment",index:"equipment",width:200},
                 {name:"sector",index:"sector",width:200},
                 {name:"secton",index:"secton",width:200},
                 {name:"qty",index:"qty",width:80,align:"right"},
                 {name:"measure",index:"measure",width:100,align:"center"},
                 {name:"amt",index:"amt",width:80,align:"right"},              
                 {name:"total",index:'',width:80,align:"right"}
               ],
               height: '100%',
               width:860,
               rowNum:20,
               sortname: 'id',
               sortorder: "asc"
            });
         }
    }).navGrid("#pager",{edit:false,add:false,del:false,search:true});     
   jQuery("#grid_income").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});// beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }});
    
    $('#search').click(function (){
       var spare_id=$('#spare_id').val(), spare=$("#spare").val();
       if(spare_id==0&&(spare=!null||spare!="")){
          alert("Ийм сэлбэг байхгүй байна!");
       }else{
          if((spare==null||spare=="")&spare_id!=0){
             spare_id=0;              
          }
          grid_income.jqGrid('setGridParam', { url: base_url+'/wm_ajax/income?spare_id='+spare_id, page: 1}).trigger("reloadGrid");
       }
    });
  
    function _link(cellValue, options){
      return "<a href='#' onclick='view_invoice(" + options.rowId + ")' >"+cellValue+"</a>"; 
    }

    //хэсэг сонгоход давхар тасаг, төхөөрөмжийн утгуудыиг шинэчлэх хэрэгтэй
    $('#gs_section').on("change", function() {
        //alert($(this).val());
         $('#barcode').val('');
        var select = $('#gs_sector').val(0);
        switch($(this).val()){
           case 'Ажиглалт':           //alert(0);             
              select.find('option').remove().end().append('<option value>Бүгд</option><option value="ТАТ">ТАТ</option>');
              // төхөөрөмжийг дуудах фүнкц              
           break;
           case 'Навигаци':
           //alert(0);
              select.find('option').remove().end().append('<option value>Бүгд</option><option value="ТАТ">ТАТ</option>');
           break;
           case 'Холбоо':           
              select.find('option').remove().end().append('<option value>Бүгд</option><option value="РХТ">РХТ</option><option value="МТДХТ">МТДХТ</option><option value="ӨХТ">ӨХТ</option>');
           break;
           case 'Гэрэл суулт, цахилгаан':           
              select.find('option').remove().end().append('<option value>Бүгд</option><option value="ГСТ">ГСТ</option><option value="ХСТ">ХСТ</option><option value="ДТ">ДТ</option>');
           break;
           default:
              select.find('option').remove().end().append('<option value="">Бүгд</option>');
           break;
        }
       // get by ajax and add tehme
       //remove gs_sector 
       //remove gs_equipment//
       // switch($(this).val()){
       // }       
    });

    //expense grid 
    //expense grid here
    var grid_income =$("#grid_expense");
    grid_income.jqGrid({      
      //url:'/ecns/wm_ajax/income',
      url:base_url+'/wh_spare/index/jx_expense_grid',
      datatype: 'json',
      mtype: 'GET',
      colNames:['#', 'Зарлага №','Огноо', 'Зарлагад гарсан сэлбэг, материалууд', 'Нийт тоо','Нийт үнэ','Хэсэг', 'Тасаг', 'Хүлээн авсан', 'Үйлдэл'],
      colModel :[                 
        {name:'invoice_id', index:'invoice_id', search:false, hidden:true}, 
        {name:'expense_no', index:'expense_no', width:20, align:'center'}, 
        {name:'expense_date', index:'expense_date', width:30, align:'left'},         
        {name:'spare', index:'spare', width:120, formatter:_link},               
        {name:'t_qty', index:'t_qty', width:30,  align:'center'},       
        {name:'t_amt', index:'t_amt', width:30,  align:'right'}, 
        {name:'section', index:'section', width:60,  align:'center'}, 
        {name:'sector', index:'sector', width:40,  align:'right'}, 
        {name:'recievedby', index:'recievedby', width:40,  align:'right'}, 
        {name:'action', width:30, index:"action", align:'right',formatter: exp_action }//,formatter:inc_action
      ],
      jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "invoice_id"
        },
      pager: jQuery('#pager'),
      rowNum:15,
      rowList:[20,25,40],
      sortname: 'expense_date',
      sortorder: 'desc',
      viewrecords: true,
      caption: 'Зарлага жагсаалт',
      autowidth:true,      
      width:'100%',
      height: '500',
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {
            var subgrid_table_id;
            subgrid_table_id = subgrid_id+"_t";
            jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
            jQuery("#"+subgrid_table_id).jqGrid({
               autoencode: false,
               url:base_url+"/wh_spare/index/jx_expense_dtl?q=2&id="+row_id,
               datatype: "xml",
               colNames: ['#',  'Сэлбэг','Төхөөрөмж','Тасаг', 'Хэсэг','Тоо/ш','Х/нэгж', 'Н/б Үнэ', 'Нийт'],
               colModel: [
                 {name:"id",index:"id",width:80,key:true, align:"right"},
                 {name:"spare",index:"spare",width:240},
                 {name:"equipment",index:"equipment",width:200},
                 {name:"sector",index:"sector",width:200},
                 {name:"secton",index:"secton",width:200},
                 {name:"qty",index:"qty",width:80,align:"right"},
                 {name:"measure",index:"measure",width:100,align:"center"},
                 {name:"amt",index:"amt",width:80,align:"right"},              
                 {name:"total",index:'',width:80,align:"right"}
               ],
               height: '100%',
               width:860,
               rowNum:20,
               sortname: 'id',
               sortorder: "asc"
            });
         }
    }).navGrid("#pager",{edit:false,add:false,del:false,search:true});     
    jQuery("#grid_expense").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});// beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }});
    
}); 

//expense
function exp_action (cellvalue, options, rowObject) {     
   action_str ="<div title='Дэлгэрэнгүй' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span id='log_none' onclick='init_view("+options.rowId+")' class='ui-icon ui-icon-extlink'></span></div></div>";
   var fields = $( "#action" ).serializeArray();
   
   $('input.action').each(function() {
     switch($(this).val()){
        case 'delete':
            action_str=action_str+"<div title='Устгах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='delete_expense("+options.rowId+")' target ="+options.invoice_id+" class='ui-icon ui-icon-trash'></span></div></div>";    
        break;
        case 'expense_edit':
             action_str=action_str+"<div title='Засах' style='float:left;cursor:pointer;' class='ui-pg-div ui-inline-edit'><span onclick='expense_edit("+options.rowId+")' target ="+options.invoice_id+" class='ui-icon ui-icon-pencil'></span></div></div>";
             break;
    }
   });
   return action_str;
}

//delete expense
function delete_expense(_id){  
     var data = { id: _id };      
     var rowData = $("#grid_income").getRowData(_id);   
      // ask confirmation before delete 
     if(window.confirm("Та орлогийн '"+rowData.income_no+"' дугаартай "+rowData.income_date+" огноотой орлогийг устгахдаа итгэлтэй байна уу?")){
        $.ajax({
           type:    'POST',
           url:    base_url+'/wh_spare/index/jx_expense_del/',
           data:   data,
           dataType: 'json', 
           success:  function(json) {
              if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                    // close the dialog                         
                   showMessage(json.message, 'success');
                  // show the success message
                  $("#grid_expense").trigger("reloadGrid"); 
             }
           }
        });
    }
  }

// тухайн төхөөрөмжийг хэсгээр болон тасгаар шүүж харуулах
  //delete
  function del_inv(_id){  
     var data = { id: _id };      
     var rowData = $("#grid_income").getRowData(_id);   
      // ask confirmation before delete 
     if(window.confirm("Та орлогийн '"+rowData.income_no+"' дугаартай "+rowData.income_date+" огноотой орлогийг устгахдаа итгэлтэй байна уу?")){
          $.ajax({
             type:    'POST',
             url:    base_url+'/wh_spare/index/jx_del/',
             data:   data,
             dataType: 'json', 
             success:  function(json) {
                if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      // close the dialog                         
                     showMessage(json.message, 'success');
                    // show the success message
                    $("#grid_income").trigger("reloadGrid"); 
               }else
                    showMessage(json.message, 'error');
             }
           });
      }
  }
  function init_view(_id){
    alert('id');
  }

//орлогийг засварлах фүнкц
function income_edit(id){    
    // тухайн id-р утгуудыг авч харуулах фүнкцийг дуудна!!!
    window.location.assign(base_url+"/wh_spare/index/income_edit/"+id);

}

//Зарлага засварлах фүнкц
function expense_edit(id){
    //console.log('id'+id);
    // тухайн id-р утгуудыг авч харуулах фүнкцийг дуудна!!!    
    window.location.assign(base_url+"/wh_spare/index/expense_edit/"+id);
}

//хэрэв section байхгүй бол 4 section-г харуулна.
function get_section(){   
  // var sec_code=$("#sec_code").val();
  // var fruits = ["COM", "SUR", "ELC", "NAV"];
  // var a = fruits.indexOf(sec_code), str="";  
  // if(a>-1){ //олдох юм бол
  //    //Холбоо, Ажиглалт
  //    switch(sec_code){
  //        case 'COM': str ='Холбоо:Холбоо';
  //            break;

  //       case 'NAV': str ='Навигаци:Навигаци';
  //            break;

  //       case 'SUR': str ='Ажиглалт:Ажиглалт';
  //            break;
        
  //       default:  str ='Гэрэл суулт цахилгаан:ГСЦ';
  //      }  
  // }else
   str = ':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;ЧОУНБ (NUBIA):ЧОУНБ (NUBIA);Хангамж:Хангамж';
  return str;
}
// get sector
function get_sector(){
  str =':Бүгд;РХТ:РХТ;ӨХТ:ӨХТ;МТДХТ:МТДХТ;ТАТ:ТАТ;ГСТ:ГСТ;ХСТ:ХСТ;ДТ:ДТ;Аж ахуй:Аж ахуй';  
  return str;
}

function get_equipment(){  
  str=':Бүгд';
  return str;
}

function view_invoice(id){   
    var data = { id: id };        
    var warehouse;
    var spare_name ;
    title = 'Сэлбэг';    
      $.ajax({
         type:    'POST',
         url:    base_url+'/wh_spare/index/get_inv/'+id,
         data:   data,
         dataType: 'json', 
         success:  function(json) {
               console.log(json.spare);
               console.log(json.pallet);
             $("#section", view_dialog).val(json.spare.section);
             $("#sector", view_dialog).val(json.spare.sector);
             $('#equipment',view_dialog).val(json.spare.equipment);   
             $('#spare',view_dialog).val(json.spare.spare);   
             spare_name = json.spare.spare;
             $('#sparetype',view_dialog).val(json.spare.sparetype);   
             // $('#income_date', view_dialog).text(json.invoice.income_date);
             // $('#years_old', view_dialog).text(json.invoice.years_old+" жил");
              $('#qty', view_dialog).text(json.spare.qty+" "+json.spare.measure);
              //$('#amt', view_dialog).text(json.pallet.amt+" ₮");
              $('#total', view_dialog).text(json.spare.total+" ₮");
             $.each(json.pallet, function(key, value) {                
                 $('#warehouse', view_dialog).text(value.warehouse);
                $('#table_spare').append("<tr><td>"+value.pallet+"</td><td>"+value.qty+"</td><td>"+value.amt+"</tr>");
            });             
            // $('#validdate', page_open).text(json.validdate); 
            // if(json.cert_file){                                              
            //    $('#_file', page_open).append("<span id='file_link'><a href='#' style='color:blue;' onclick='_file("+cert_id+")'>"+json.cert_file+"</a></span>");
            // }else       
            //    $('#_file', page_open).append("<span id='file_link' style='color:red'>Файл байхгүй!</span>");
                 
         }
      }).done(function() {
         view_dialog.dialog('option', 'title', title+": "+spare_name);      
         view_dialog.dialog({ 
             buttons: {                        
                 "Хаах": function () {                    
                    $('#table_spare>tbody').empty();
                     $(this).dialog("close");
                 }
             }
         });     
         view_dialog.dialog('open');
      });
}
  // function inc_action (cellvalue, options, rowObject) {        
  //     return "<button onclick='invoiceDel("+options.rowId+")'>Устгах</button>";
  //  }

  //  function invoiceDel(invoice_id){    
  //     var r = confirm("Та, энэ орлогийг устгахдаа итгэлтэй байна уу!");
  //     if (r == true) {
  //         window.location.assign("/ecns/warehouse/invoiceDel/"+invoice_id);
  //     }    
  //  }

// WHAREHOUSE JQSCRIPTS HERE
  /* 
 * warehouse js functions
 */
// When Page is Loaded Then Load this 
function getEquipment(id){
   var xmlhttp;
   if (id===""){
      document.getElementById("equipment").innerHTML="";
      return;
   }
   if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function(){
      if(xmlhttp.readyState==4 && xmlhttp.status==200){
         document.getElementById("equipment").innerHTML=xmlhttp.responseText;
      }
    }
   xmlhttp.open("GET",base_url+"/wm_ajax/getEquipment?section_id="+id,true);
   xmlhttp.send();      
}
// spare list
function callSpare(id){      
   var xmlhttp;   
   var equipment_id;
   equipment_id=document.getElementById("equipment_id").value;
    
   if (id==""){
      document.getElementById("spare").innerHTML="";
      return;
   }    
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   
   xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
         document.getElementById("spare").innerHTML=xmlhttp.responseText;
      }
   }
   
   xmlhttp.open("GET",base_url+"/wm_ajax/getSpare?id="+id+"&equipment_id="+equipment_id,true);
     xmlhttp.send();      
  }  
//Зарлага гаргахад ашиглагдах
function expenseDetail(spare_id){     
     if(spare_id!==null){        
        var xmlhttp;         
        if (spare_id===""){
           document.getElementById("spares").innerHTML="";
           return;
        }
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
        }else{// code for IE6, IE5
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
           if (xmlhttp.readyState==4 && xmlhttp.status==200){
              document.getElementById("spares").innerHTML=xmlhttp.responseText;
           }
        }
        xmlhttp.open("GET",base_url+"/wm_ajax/getSpareDetial?spare_id="+spare_id,true);
        xmlhttp.send();      
     }else
        alert("Сэлбэг сонгогдоогүй байна! Сэлбэгийг сонгоно уу?");
  }  
function showDetail(id){
    if($('#exPallet').text()!="")
       $("#exPallet").remove();
  
    $("#"+id).after("<tr id='exPallet'><td colspan ='9'><span id ='detail'></span></td></tr>");
    
    var xmlhttp;    
    if (id===""){
       document.getElementById("detail").innerHTML="";
       return;
    }
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
           xmlhttp=new XMLHttpRequest();
    }else
    {// code for IE6, IE5
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState===4 && xmlhttp.status===200)
      {
         document.getElementById("detail").innerHTML=xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET",base_url+"/wm_ajax/getBalanceDetail?ct_id="+id,true);
    xmlhttp.send();   
       
}   
// main detail
function getDetail(id){   
   if($('#exPallet').text()!="")
      $("#exPallet").remove();
  
   $("#"+id).after("<tr id='exPallet'><td colspan ='9'><span id ='Detail'></span></td></tr>");
  
   var xmlhttp;    
   if (id===""){
      document.getElementById("Detail").innerHTML="";
      return;
   }
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState===4 && xmlhttp.status===200){
         document.getElementById("Detail").innerHTML=xmlhttp.responseText;
      }
   };
   xmlhttp.open("GET",base_url+"/wm_ajax/getDetail?spare_id="+id,true);
   xmlhttp.send();   
}
//warehouse BALANCE AND INCOME USED SCRIPTS  
function callPallet(){          
    var type='text';     
    var count = document.getElementById("count").value;
    var idName="detail"+count++;
  
    //Create an input type dynamically.    
    var labelPallet =document.createElement("label");
    labelPallet.innerHTML ='Тавиур:';
     
    var tagSpan = document.createElement("span");
    tagSpan.id=idName;     
    var label = document.createElement("label");
    label.id="label";
    label.innerHTML ='Тавиур дээрх тоо/ш:';
     
    var counter = document.createElement("input");
    counter.setAttribute("type", "hidden");
    counter.setAttribute("name", "count");
    counter.setAttribute("value", count);
    counter.setAttribute("id", "count");
     
    var element = document.createElement("input");

     //Assign different attributes to the element.
    var palletId = "palletQty"+count;
    var wrapId = "pwrap";
     
    var palletDiv =document.createElement("p");     
    palletDiv.setAttribute("id", wrapId);
     
     element.setAttribute("type", type);     
     element.setAttribute("name", "palletQty[]");
     element.setAttribute("style", "width:30px");
     element.setAttribute("size", 5);
     element.setAttribute("maxlength", 5);
     element.setAttribute("id", palletId);
     
     
     var foo = document.getElementById("pallet");
     // call Pallet here
     getPallet(1, idName, count);
     
    //Append the element in page (in span).
     foo.appendChild(palletDiv);     
     palletDiv.appendChild(labelPallet);
     palletDiv.appendChild(tagSpan);
     palletDiv.appendChild(label);
     palletDiv.appendChild(element);
     palletDiv.appendChild(counter);     
     document.getElementById("count").value=count;        
  }
  
function getPallet(spare_cnt, idName, idcnt){
    var xmlhttp, warehouse_id;
    warehouse_id = document.getElementById("warehouse_id").value;
    if (idName===""){
       document.getElementById(idName).innerHTML="";
       return;
    }   
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
       xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
       if (xmlhttp.readyState===4 && xmlhttp.status===200){
          document.getElementById(idName).innerHTML=xmlhttp.responseText;
       }
    };
    xmlhttp.open("GET",base_url+"/wm_ajax/getPallet?warehouse_id="+warehouse_id+"&idcnt="+idcnt+"&spare_cnt="+spare_cnt,true);
    xmlhttp.send();    
  }  
function copyValue(val){     
   document.getElementById('palletQty1').value=val;  
} 

// Serial nemeh hesgiig haruulah
function showAddSpare(spare_id){
     if($("#isSerial_"+spare_id).attr('checked')){
         $("#isSerial_"+spare_id).val('yes');
         $('#addSerial_'+spare_id).show();
     }else {
         $("#isSerial_"+spare_id).val('no');
         $('#addSerial_'+spare_id).hide();
         $('input[name="addPallet_'+spare_id+'"]').show();
         $('input[name="remPallet_'+spare_id+'"]').show();
         $('#'+spare_id+'_wrapSerial').empty();
     }     
}

//Сериал дуудах хэсэг
function setSerial(formName){
   var setserial=confirm("Сериал + тохиолдолд Тавиур + - болохгүй тул Тавиурт тавих тоо/ш шалгаж дуусаад сериал нэмнэ үү!");
      if(setserial===true){ // serial + тохиолдолд
         var Myform= document.forms[formName];
         var total =parseInt($("#beginQty").val()); //нийт тоо хэмжээ
         // dung ehnii duntei tentsuu bgaa         
         var pallet_id = Myform.elements['pallet_id[]'];           
         var palletQty=document.getElementsByName("palletQty[]");
         var subtotal =0, flag =0, i=0;
         do {   
           subtotal +=parseInt(palletQty[i].value);
           i++;
         }while(i < palletQty.length)
            
         if(total === subtotal){
            document.getElementById('serialCLD').value ='yes';
           for (i = 0 ; i<pallet_id.length ; ++i){           
              //var pallet_qty = document.getElementById('palletQty'+j).value;
              if(palletQty[i].value===0) alert("Тоо ширхэгийг оруулаагүй байна! Тоо ширхэгийг оруулна уу!");
              else{                 
                 callDetail(pallet_id[i].value, palletQty[i].value);
                 flag=1;
              }              
           }
        }else
            alert("Нийт үлдэглийн тоо тавиур дээрх тоотой тэнцэхгүй байна!!!");
        if(flag===1){
              $('input[name="addPallet"]').attr('disabled', true);
              $('input[name="removePallet"]').attr('disabled', true); 
              //$('input[name="removePallet"]').remove(); 
              //$('input[name="addPallet"]').remove(); 
           }         
     }
  }  

// serial-г үүсгэж харуулах 
function callDetail(spare_cnt, pallet_id, val){     
     var spanqty= document.getElementById(spare_cnt+"_wrapSerial");   
     var id_serial = spare_cnt+'_serial_'+pallet_id;
     var div = document.createElement("div");
     div.setAttribute("id", id_serial);
     spanqty.appendChild(div);
     var xmlhttp;
     if (id_serial==""){
        document.getElementById(id_serial).innerHTML="";
        return;
     }
     if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
     }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
              document.getElementById(id_serial).innerHTML=xmlhttp.responseText;
        }
     }     
     xmlhttp.open("GET",base_url+"/wm_ajax/palletSerial?pallet_id="+pallet_id+"&qty="+val+"&spare_cnt="+spare_cnt,true);
     xmlhttp.send();      
  }
// when key up in added palletQty call this function
function subQty(count){    
    var Qty=document.getElementById("palletQty1").value;
    var subCount =document.getElementById("palletQty"+count).value;
    //alert("Qty:"+Qty +"sub"+subCount);
    document.getElementById("palletQty1").value=Qty-subCount;
    return;
}
// ene function Захиалгийн жагсаалтад сэлбэг нэмэхэд дуудагдана
function getSpare(id){      
   var xmlhttp;          
   if (id==""){
      document.getElementById("spare").innerHTML="";
      return;
   }
   if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   
   xmlhttp.onreadystatechange=function(){
     if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("spare").innerHTML=xmlhttp.responseText;
     }
   }  
   xmlhttp.open("GET",base_url+"/wm_ajax/getSpare?equipment_id="+id,true);
   xmlhttp.send();   
}

function getSelect(section_id){
   var sector_id;
   // Sector selection   
   $.post( base_url+'/wm_ajax/getSector', {section_id:section_id}, function(secOption) {
      var select = $('#sector_id');
      if(select.prop) {
         var options = select.prop('options');
      }else {
         var options = select.attr('options');
      }        
      $('option', select).remove();
      $.each(secOption, function(val, text) {         
         options[options.length] = new Option(text, val);                  
      });
      
   }).done(function(){
       sector_id =$('#sector_id :selected').val();
       $('#flag1').text(sector_id);       
       getEquipments(sector_id);
      });
}

// getEquipments from filter
function getEquipments(sector_id){
   $.post( base_url+'/wm_ajax/getEquipments', {sector_id:sector_id}, function(newOption) {   
      var select = $('#equipment_id');  
      
      //var  select =$('#field-equipment_id');      
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
function getEmployee(section_id){
   $.post( base_url+'/wm_ajax/getEmployee', {section_id:section_id}, function(newOption) {   
      var select = $('#employee');
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
//income_dtl дээр тавиур нэмэх фүнкц spare_cnt - bol id
function makePallet(spare_cnt){     
   var type='text';  
   // хэдэн тавиур байгааг заана  
   var palletCnt ="p_cnt_"+spare_cnt;    
   // тавиурын тоо
   var count = document.getElementById(palletCnt).value; 

    //Тавиурыг үүсгэх динамикаар үүсгэх хэсгүүд
   var labelPallet =document.createElement("label");   
   labelPallet.innerHTML ='Тавиур:';
  
    //pallet wrapper span_id
    var idName=spare_cnt+"detail"+count++;
    var total_qty = $('#'+spare_cnt+'_qty').val();    
     
  // pallet-n тоо ниийт сэлбэгийн тооноос их багийг шалгах
  if(total_qty >= count){
     //span tag create
     var tagSpan = document.createElement("span");
     tagSpan.id=idName;
       
     var label = document.createElement("label");
     label.id="label";
     label.innerHTML ='Тавиур дээрх тоо/ш:';

     //counter-g нэмэх     
      $('#'+palletCnt).val(count);
          
       //Assign different attributes to the element.
       var palletId = spare_cnt+"palletQty"+count;
       var wrapId = "pwrap";   
       //pallet-n өөрийн wrapper div      
       var palletDiv =document.createElement("p");     
       palletDiv.setAttribute("id", wrapId);
       //pallet-н тооны input нэр       
       // changed +count+
       var palletQtyName="spare["+spare_cnt+"][pallet_qty][]";
        
       var element = document.createElement("input");
       element.setAttribute("type", type);     
       element.setAttribute("name", palletQtyName);
       element.setAttribute("style", "width:30px");
       element.setAttribute("size", 5);
       element.setAttribute("maxlength", 5);
       element.setAttribute("id", palletId);
       //шинэ pallet үүсгэх хэсгийн wrapper here 
       var dist_id = "pallet_"+spare_cnt;    
       //pallet destination wrapper 
       var plt_dest_wrap = document.getElementById(dist_id);
       //pallet wrapper-t pallet wrapper div-г нэмэх
       plt_dest_wrap.appendChild(palletDiv); 
       // pallet-г үүсгэх хэсэг энд байна.
       getPallet(spare_cnt, idName, count);
       palletDiv.appendChild(labelPallet);
       palletDiv.appendChild(tagSpan);
       palletDiv.appendChild(label);
       palletDiv.appendChild(element);
       
      // chkPalletQty(spare_cnt);
     }else{
         alert('Тухайн тавиурын тоо нийт сэлбэгийн тооноос их байх ёсгүй!');
     }
 }  

// тухайн сэлбэгийн id
function makeSerial(spare_id){      
   //Тухайн тавиурын тавиурын товчуудыг идэвхигүй болгоно.     
   $('input[name="addPallet_'+spare_id+'"]').hide();
   $('input[name="remPallet_'+spare_id+'"]').hide();

   var Myform= document.forms['income'];
   
   // тухайн сэлбэгийн нийт т/ш
   var name_bqty = "#"+spare_id+"_qty";
   
   //нийт тоо хэмжээ
   var total =parseInt($(name_bqty).val()); 
    
  // dung ehnii duntei tentsuu bgaa 
  // нийт тавиурыг сонгоно
   var pallet_id = document.getElementsByName(['spare['+spare_id+'][pallet][]']);      
   //$('#pallet'+spare_id)
   
   console.log('spare:'+spare_id+'-n pallet_id: '+pallet_id[0].value);
  // тавиур дээрх тоонуудыг авна
  //changed spare_id
   var palletQty=document.getElementsByName('spare['+spare_id+"][pallet_qty][]");
   console.log('p_qty '+palletQty[0].value+'d');

   //var pallet_val = $("#"+spare_id_+"pallet_id").chosen().val();

   var subtotal =0, i=0; l=0;
   //niit taviur-n toogoor davtaj niit tootoi tentsuu esehiig olno
   do {   
      subtotal +=parseInt(palletQty[i].value);
      i++;
   }while(i < palletQty.length)            
   if(total === subtotal){
      //tentsen esehiig flag dah
       document.getElementById('serialCLD').value ='yes';
      for (j = 1 ; j<=pallet_id.length ; ++j){                    
         // call detail function-g 
        console.log('J:'+j+'val');
        callDetail(spare_id, j, palletQty[l].value);        
        l++;
      }
   }else
       alert("Нийт үлдэглийн тоо тавиур дээрх тоотой тэнцэхгүй байна!!!");
   // if(flag===1){
   //       $('input[name="addPallet"]').attr('disabled', true);
   //       $('input[name="removePallet"]').attr('disabled', true); 
   //       //$('input[name="removePallet"]').remove(); 
   //       //$('input[name="addPallet"]').remove(); 
   // }     
  }


//this removePallet by spare no
function removePallet(spare_id){
   var removeBtn = "#removePallet_"+spare_id;
   var palletLastChild = "#pallet_"+spare_id+" #pwrap:last-child";
   var palletCnt = "p_cnt_"+spare_id;
   $(palletLastChild).remove();
   var bcount = document.getElementById(palletCnt).value;   
   if(bcount >1)
      document.getElementById(palletCnt).value=bcount-1;
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


//get_barcode here

function print_barcode(id, qty){   

  window.open(base_url+"/wh_spare/print_barcode?spare_id="+id+"&qty="+qty, "_blank"); 

  // var data = { spare_id: id, total: qty};  

  //  $.ajax({

  //    type:    'POST',
  //    url:    base_url+'/wh_spare/index/get_barcode/',     
  //    data:   data,

  //    dataType: 'json', 

  //    success:  function(json) {

  //      console.log(json.barcode);

  //      window.open(base_url+"/wh_spare/print_barcode?barcode="+json.barcode+"&qty="+qty, "_blank"); 

  //    } 

 // });
 
 //update barcode again!!!!


 

}

