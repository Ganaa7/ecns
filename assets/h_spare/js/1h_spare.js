var add, minus, rest;

$(function(){
    $( document ).tooltip();

    $( "#dialog" ).dialog({

       autoOpen: false,

       width: 600,       

       resizable: false,    
       modal: true,

       close: function () {          

          $(this).dialog("close");
      }

    });


    $('#add_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });  

   $('#using_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });    

    $('#need_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });  


   jqgrid(); 

   //add dialog
   add=$('#create');
   add.dialog({
     autoOpen: false,
       width: 860,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');                    
          $(".route", $(this)).remove();          
          $(this).dialog("close");
          
          if($('.last_tr').length){

             $('.last_tr').remove();

          }    
       }
   });


   //rest qty dialog

   use=$('#using');

   use.dialog({
     autoOpen: false,
       width: 560,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');                    
          $(".route", $(this)).remove();          
          $(this).dialog("close");
       }
   });


   need=$('#need');

   need.dialog({
     autoOpen: false,
       width: 560,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');                    
          $(".route", $(this)).remove();          
          $(this).dialog("close");
       }
   });


   //create trip
   $( "#add_spare" ).on( "click", function() {  
       add.dialog({ 
          buttons: { 
            "Хадгалах": function () {  
               //var data = {};
               var data = $('#create' ).serialize();
               // var inputs = $('input[type="text"], input[type="hidden"], select' , add);                
               //    inputs.each(function(){
               //      var el = $(this);
               //      data[el.attr('name')] = el.val();
               //    });
                 // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/wh_spare/spare/index/add',
                    data:   data,
                    dataType: 'json', 
                    async: false,
                    success:  function(json){ 
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         add.dialog("close");
                        // close the dialog                         
                        showMessage(json.message, 'success');
                        // show the success message                      
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', add).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller 
            
            },            
            "Хаах": function () {
                add.dialog("close");
            }
       }
      });   
      add.dialog( "open" );
  });



   // add new row in the table here 
   $( "#add_site" ).on( "click", function() {         
       //#site_wrapper-d append hesgiig end bichne!
       $('#site_wrapper', '#create').append('<tr class="last_tr"><td class="last_row"></td><td><input type="text" name="serial[]"/></td><td><input type="text" name="barcode[]"/></td></tr>'); /*<td>[<a href="#" class="remove_site" title="Энэ мөрийг хасах бол энд дар!">-</a>]</td>*/
       // Mөрүүдийг нэмээд            
        var $options = $('#site option').clone();        
        $newSelect = $('<select name="site_id[]"></select>').append($options);
        $('.last_row').append($newSelect);        
        $( "td" ).removeClass( "last_row" );    
   });

   // herev remove hiivel end bichne
   $(document).on("click", ".remove_site", function() {        
        $('tr:last').remove();
  });

  $( "#qty", "#create" ).keyup(function() {      
      // Тухайн тоо хэд байгааг сайн тооцох хэрэгтэй байна!
      // Хэрэв 1 бйавал нэмж болно!     //Эсрэг тохиолдолд хасна гсн үг!
      var count = $(this).val();
      if($('.last_tr').length){
         $('.last_tr').remove();
      }      
      for (var i = count; i > 1; i--) {                  
          $('#site_wrapper', '#create').append('<tr class="last_tr"><td id="row_'+i+'"></td><td><input type="text" name="serial[]"/></td><td><input type="text" name="barcode[]"/></td></tr>');          /* <td>[<a href="#" class="remove_site" title="Энэ мөрийг хасах бол энд дар!">-</a>]</td>*/
          var $options = $('#site option').clone();        
          $newSelect = $('<select name="site_id[]"></select>').append($options);
          $('#row_'+i).append($newSelect);          
      }      
       
  });

});
function jqgrid(){
    $("#grid").jqGrid({ 
        url:base_url+'/wh_spare/spare/index/grid',
        datatype: 'json', 
        mtype: 'GET', 
        colNames:['№', 'Хэсэг',  'Төхөөрөмж', 'Сэлбэг','Парт №', 'Алслагдсан/обьект үлдэгдэл', 'Ашиглагдаж буй тоо/ш', 'Сэлбэгэнд байх ёстой тоо/ш', 'Агуулахад', 'Хэмжих нэгж'], 
        colModel :[ 
                    {name:'spare_id', index:'spare_id', width:15, align:'center' },                     
                    {name:'section', index:'_wh_vw_spare.section', width:50, align:'center', stype:'select', searchoptions:{value:set_section()}}, 
                    {name:'equipment', index:'_wh_vw_spare.equipment', width:120 },                     
                    {name:'spare', index:'_wh_vw_spare.spare', width:80, align:'center'},                     
                    {name:'part_number', index:'_wh_vw_spare.part_number', width:60, align:'center'},                     
                    {name:'qty', index:'qty', width:50, align:'center'},// belen selbeg 
                    {name:'using_qty', index:'using_qty', width:50, align:'center'}, //required //ашиглалтад байгаа
                    {name:'need_qty', index:'need_qty', width:50, align:'center'}, //Сэлбэгэднд байх ёстой
                    {name:'aqty', index:'aqty', width:50, align:'center'}, 
                    {name:'measure', index:'measure', width:40, align:'center' }                    
                    
                    ], 
        jsonReader : {
            page: "page",
            total: "total",
            records: "records", 
            root:"rows",
            repeatitems: false,
            id: "spare_id"
        },
        pager: '#pager', 
        rowNum:20, 
        rowList:[10,20,30,40, 60,80, 100],                    
        sortname: '_wh_vw_spare.spare_id',
        sortorder: "asc", 
        viewrecords: true, 
        gridview: true, 
        // imgpath: 'themes/basic/images', 
        caption: 'Сэлбэгийн бүртгэл',
        autowidth:true,       
        height:500,
        width:'100%' ,        
        editurl: 'server.php',
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');   

           for (var i=0;i<rowIds.length;i++){               

              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));                
              //console.log('subrows'+rowData.est_dt);  
             trElement.addClass('context-menu1'); 


            // if(!isNaN(rowData.need_qty)){
               
            //    trElement.removeClass('ui-widget-content');
               
            //    trElement.addClass('argent');

            // }else 
            if(parseInt(rowData.need_qty)>parseInt(rowData.aqty)){

               trElement.removeClass('ui-widget-content');

               trElement.addClass('argent');

            }
             
           }         
       },
     
     // book
      subGridRowExpanded: function(subgrid_id, row_id) {
         var subgrid_table_id;
         subgrid_table_id = "_"+subgrid_id;
         jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
         jQuery("#"+subgrid_table_id).jqGrid({
            autoencode: false,
            url: base_url+'/wh_spare/spare/index/sub_grid?id='+row_id, //"/ecns/wm_ajax/incomeDtl?q=2&id="+row_id,
            datatype: "xml",            
            colNames: ['id', '#','Байршил', 'Тоо/ш', 'Баркод','Сериал'],
            colModel: [              
              {name:"id",index:"id",width:0,key:true, hidden:true},              
              {name:"i",index:"i",width:35,key:true, align:"center"},
              {name:"site",index:"site",width:200, align:"center"},
              {name:"qty",index:"qty",width:50,key:true, align:"center"},
              {name:"barcode",index:"barcode",width:150, align:"center"},
              {name:"serial",index:"serial",width:100, align:"center"}              
            ],
            height: '100%',
            width:'100%',
            rowNum:20,
            sortname: 'num',
            sortorder: "asc",
            loadComplete: function (){
              var rowIds = $(this).jqGrid('getDataIDs');          
              for (var i=0;i<rowIds.length;i++){               
                 var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));                                            
                 var rowData=$(this).jqGrid('getRowData', rowIds[i]);
                 var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));  
                trElement.addClass('context-menu');  
                
                 $(this).expandSubGridRow(rowIds[i]); 
             }
          } 

         });
      }      
    }).navGrid("#pager",{edit:false,add:false,del:false, search:true});
    jQuery("#grid").jqGrid('filterToolbar',{searchOperators : true, stringResult: true, defaultSearch:'cn'}); 
}

function set_section(){   
  var str="", cnt=0;
   $("#section_id option").each(function(){
      str =$(this).text()+":"+$(this).text(); cnt++;
   });     
   //if(cnt>1)
   console.log("count"+cnt);

    str = ':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;ЧОУНБХ (NUBIA):ЧОУНБХ (NUBIA)';
   return str;
}


function add_modal(spare_id, spare){
    $('#spare', add).val(spare);
    $('input[name=spare_id]', add).val(spare_id);
         add.dialog({ 
          buttons: { 
            "Хадгалах": function () {  
               //var data = {};
               var data = $('#create' ).serialize();
               // var inputs = $('input[type="text"], input[type="hidden"], select' , add);                
               //    inputs.each(function(){
               //      var el = $(this);
               //      data[el.attr('name')] = el.val();
               //    });
                 // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/wh_spare/spare/index/add',
                    data:   data,
                    dataType: 'json', 
                    async: false,
                    success:  function(json){ 
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         add.dialog("close");
                        // close the dialog                         
                        showMessage(json.message, 'success');
                        // show the success message                      
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', add).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller 
            
            },            
            "Хаах": function () {
                add.dialog("close");
            }
       }
      });   
      add.dialog( "open" );
}


function use_modal(spare_id, spare, using_qty){

    $('#spare', use).val(spare);

    $('#using_qty', use).val(using_qty);

    $('input[name=spare_id]', use).val(spare_id);
         use.dialog({ 
          buttons: { 
            "Хадгалах": function () {  
               //var data = {};
               var data = use.serialize();            
              // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/wh_spare/spare/index/use',
                    data:   data,
                    dataType: 'json', 
                    async: false,
                    success:  function(json){ 
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         use.dialog("close");
                        // close the dialog                         
                        showMessage(json.message, 'success');
                        // show the success message                      
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', use).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller 
            
            },            
            "Хаах": function () {
                use.dialog("close");
            }
       }
      });   
      use.dialog( "open" );
}

function need_modal(spare_id, spare, need_qty){

    $('#spare', need).val(spare);

    $('#need_qty', need).val(need_qty);

    $('input[name=spare_id]', need).val(spare_id);
         need.dialog({ 
          buttons: { 
            "Хадгалах": function () {  
               //var data = {};
               var data = need.serialize();            
              // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/wh_spare/spare/index/need',
                    data:   data,
                    dataType: 'json', 
                    async: false,
                    success:  function(json){ 
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         need.dialog("close");
                        // close the dialog                         
                        showMessage(json.message, 'success');
                        // show the success message                      
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }                  
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', need).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller 
            
            },            
            "Хаах": function () {
                need.dialog("close");
            }
       }
      });   
      need.dialog( "open" );
}


function help_modal(){

   $( "#dialog" ).dialog( {
      buttons: {

          "Хаах": function () {
                $(this).dialog("close");
            }
       }
  });  

   $( "#dialog" ).dialog("open");

}
 
