var add, edit, add_detail, edit_dtl;

$(function(){
    $( "#dialog" ).dialog({

       autoOpen: false,

       width: 600,

       resizable: false,
       modal: true,

       close: function () {

          $(this).dialog("close");
      }

    });

    $(".multiselect", '#add_detail').multiselect({
       minWidth:'400px'
    });
    $('.available').css('width', '240px');
    $('.selected').css('width', '200px');


    $('#date').datepicker({
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
       width: 600,
       resizable: false,
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();
          $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val('');

          $(this).dialog("close");


       }
   });

   //add dialog
   add_detail=$('#add_detail');
   add_detail.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();
          $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val('');

          $(this).dialog("close");


       }
   });

   //add dialog
   completion=$('#completion');

   completion.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val('');

          $(this).dialog("close");
       }
   });


   edit=$('#edit');
   edit.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val('');

          $('#file_link', $(this)).remove();

          $(this).dialog("close");

       }
   });

   
   edit_dtl=$('#edit_dtl');
   edit_dtl.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val('');

          $('#file_link', $(this)).remove();

          $(this).dialog("close");

       }
   });


});


function upload_file(selector){

  var uploadfile = new FormData($(selector)[0]);
    $.ajax({
          url:   base_url+'/plan/index/upload/',
          type:    'POST',
          data:   uploadfile,
          processData: false,  // tell jQuery not to process the data
          contentType: false,
          success:  function(json){

            console.log('json'+JSON.stringify(json));

            if (json.status == "success") {
            // if ajax return success
             console.log('selector none');

               $("input[name=ebook]").val(json.name);
               // hide file

               $("input[type='file", selector).val('').hide();

               if(!$('#_file', selector).lenght)

                 $("#_file", selector).append("<span id='file_link'><a target=_blank href='"+base_url+"/download/plan_files/"+json.name+"' style='color:blue'>"+json.name+"</a> (<a href='#' style='color:red' onclick='javascript:del_file(\""+json.name+"\", \""+selector.selector+"\")'>Устгах</a>)</span>");
                    //onclick='_file("+json.log_id+")'
               feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!');
            }
            else{  // ямар нэг юм нэмээгүй тохиолдолд
              feeds('error', json.message);
              $("input[type='file", selector, edit).val('');
            }
          }
      });
}

function jqgrid(){

    $("#grid").jqGrid({
        url:base_url+'/plan/index/grid',
        datatype: 'json',
        mtype: 'GET',
        colNames:['№', 'Хэсэг',  'Төлөвлөсөн ажил', 'Хэрэгжүүлэх ажил', 'Гүйцэтгэх хугацаа', 'Гүйцэтгэл', 'Биелэлт/%'],
        colModel :[
                    {name:'id', index:'id', width:15, align:'center' },
                    {name:'section', index:'section.section', width:50, align:'center', stype:'select', searchoptions:{value:set_section()}, formatter:view_link},
                    {name:'work', index:'plan.work', width:120, formatter:view_link },
                    {name:'detail', index:'plan_detail.detail', width:100},
                    {name:'date', index:'date', width:80, align:'date', formatter:view_link},
                    {name:'completion', index:'plan_detail.completion', width:60, align:'center'},
                    {name:'percent', index:'percent', width:50, align:'center'},                    
                    ],
        jsonReader : {
            page: "page",
            total: "total",
            records: "records",
            root:"rows",
            repeatitems: false,
            id: "id"
        },
        pager: '#pager',
        rowNum:20,
        rowList:[10,20,30,40, 60,80, 100],
        sortname: 'id',
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        caption: 'Номын сангийн бүртгэл',
        autowidth:true,
        height:500,
        width:'100%' ,
        // editurl: 'server.php',
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');
           for (var i=0;i<rowIds.length;i++){
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
             trElement.addClass('context-menu');
           }
       },
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {
            var subgrid_table_id;
            subgrid_table_id = subgrid_id+"_t";
            jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
            jQuery("#"+subgrid_table_id).jqGrid({
               autoencode: false,
               url:base_url+"/plan/index/detail?id="+row_id,
               datatype: "xml",
               colNames: ['#',  'Хэрэгжүүлэх ажил','Хариуцах А/Т', 'Гүйцэтгэл','Биелэлт/%'],
               colModel: [
                 {name:"number",index:"number",width:80,key:true, align:"right"},
                 {name:"detail",index:"detail",width:240},
                 {name:"reponsible",index:"reponsible",width:200},
                 {name:"completion",index:"completion",width:200},
                 {name:"percent",index:"percent",width:200}                 
               ],
               height: '100%',
               width:860,
               rowNum:20,
               sortname: 'id',
               sortorder: "asc", 

               loadComplete: function (){
	               
	               var rowIds = $(this).jqGrid('getDataIDs');          
	               
	               for (var i=0;i<rowIds.length;i++){               
	                  
	                  var trElement = jQuery("#"+ rowIds[i],jQuery("#"+subgrid_id));                                            
	                  
	                  var rowData=$(this).jqGrid('getRowData', rowIds[i]);
	                  
	                  var trElement = jQuery("#"+ rowIds[i],jQuery("#"+subgrid_id));  
	                  
	                  trElement.addClass('context-menu-sub');  
	                
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

    str =':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ';
   return str;
}

//add detail model here

function add_detail_modal(plan_id){
    //$('#spare', add).val(spare);
    // $('input[name=id]', add).val(id);
    console.log('id here'+plan_id);

         add_detail.dialog({
          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data ;

               data = $('#add_detail').serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/plan/index/add_detail',
                    data:   data+'&plan_id=' + plan_id,	
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         add_detail.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', add_detail).removeClass('success, notify').addClass('error').html(json.message).show();
                      	}
                    }
                });// send the data via AJAX to our controller

            },
            "Хаах": function () {
                add_detail.dialog("close");
            }
       }
      });
      add_detail.dialog( "open" );
}

//add modal here
function add_modal(){
    //$('#spare', add).val(spare);
    // $('input[name=id]', add).val(id);
         add.dialog({
          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data = $('#create' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/plan/index/add',
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


function completion_modal(id){
    // $('input[name=id]', add).val(id);
         completion.dialog({

          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data = $('#completion' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/plan/index/completion',
                    data:   data+'&id=' + id, 
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         completion.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', completion).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller

            },
            "Хаах": function () {
                completion.dialog("close");
            }
       }
      });
      completion.dialog( "open" );
}


function edit_modal(id, book) {

// initialize edit dialog
  $('input[name=id]', edit).val(id);

   //collect all by id all data
    $.ajax({
        type:    'POST',

        url:    base_url+'/plan/index/get/',

        data:   { id: id},

        dataType: 'json',

        success:  function(json) {

          //var json = JSON.parse(json);
          // section = json.json.section;
          // route = json.json.route;

           $("#id", edit).val(json.id);
           $("#section_id option[value="+json.section_id+"]", edit).attr("selected", "selected");

           $("#work", edit).val(json.work);

           $("#date", edit).val(json.date);
           
           
       }
     }).done(function() {
        edit.dialog({
           title: "Засах: "+book,
           buttons: {
              "Хадгалах": function () {
               //var data = {};
               var data = $('#edit' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/plan/index/edit',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         edit.dialog("close");
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
                edit.dialog("close");
            }
           }
        });
        edit.dialog( "open" );

     });
}

// edit detail

function edit_dtl_modal(id, title){

  var dtl;

   $.ajax({

       type:    'POST',

       url:    base_url+'/plan/index/get_dtl/',

       data:   { id: id},

       dataType: 'json', 

       success:  function(json) {

        //var data = JSON.parse(json);
         //var json = JSON.parse(json);                    
         dtl = json.employee;         

          $("input[name=id]", edit_dtl).val(json.id);           
          
          $("#number", edit_dtl).val(json.number);           
          
          $("#detail", edit_dtl).val(json.detail);           
          
          $("#completion", edit_dtl).val(json.completion);           

          $("#percent", edit_dtl).val(json.percent);           

          // $("#section_id option[value="+json.section_id+"]", edit_dtl).attr("selected", "selected");

          //$("#section_id", edit).val(json.json.section_id);       
          //section_id =json.json.section_id;   
          // $("#employee_id", edit).val(json.employee_id);          
        
          
      }
    }).done(function() {

        $.each(dtl, function( key, value ) {            
        
            $("#employee_edit_id option[value="+key+"]").attr("selected", "selected");
        
        });

        $("#employee_edit_id").multiselect();
          
        $('.available').css('width', '240px');
          
        $('.selected').css('width', '200px');

        edit_dtl.dialog({

           title: "Засах: "+title,

           buttons: {

              "Хадгалах": function () {

               var data = $('#edit_dtl' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/plan/index/edit_dtl',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         edit_dtl.dialog("close");
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
                edit_dtl.dialog("close");
            }
           }
        });

        edit_dtl.dialog( "open" );    

    });

}



function feeds(css_class, msg){
  if($('p.feedback').hasClass('error')) $('p.feedback').removeClass('error');
  if($('p.feedback').hasClass('success')) $('p.feedback').removeClass('success');

  $('p.feedback').addClass(css_class).html(msg).fadeIn('slow').delay(5000).fadeOut();
       // fade out again after 3 seconds
}


function del_file(file_name, selector){

  console.log("selector"+selector);
  var data;

  if(selector =='#edit'){
     data = {
      file_name: file_name, id: $('input[name=id]', selector).val(), form:selector
    }
  }else
     data = { file_name: file_name, form:selector }

  if (confirm("["+file_name+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {
      $.ajax({
         type:    'POST',
         url:    base_url+'/plan/index/del_file/'+file_name,
         data:  data,
         dataType: 'json',
         success:  function(json) {
            if(json.success){
              //then remove link
//              if(selector=='#edit')
              feeds('success', json.message)
              $("#userfile", selector).show();
              $('#file_link', selector).remove();
            }else{
              feeds('error', json.message)
              $("#userfile", selector).show();
              $('#file_link', selector).remove();
            }
         }
       });

  } else {
     // nothing
     return 0;
  }

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


function view_link(cellValue, options, rowObject){

   return "<a target='_blank' href='"+base_url+"/download/plan_files/"+rowObject.ebook+"' >"+cellValue+"</a>";

}


