var add, edit;

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


    $('#add_date').datepicker({
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




// file upload here

  $('#userfile', add).change(function (){

      upload_file(add);

  });

  $('#userfile', edit).change(function (){

      upload_file(edit);

  });



  // filter here
  $("#section_id", '#create').change(function(){

    filter('#create', $(this).val(), 'equipment');

  });

   // filter here
  $("#section_id", '#edit').change(function(){

    filter('#edit', $(this).val(), 'equipment');

  });

});


function upload_file(selector){

  var uploadfile = new FormData($(selector)[0]);
    $.ajax({
          url:   base_url+'/library/index/upload/',
          type:    'POST',
          data:   uploadfile,
          processData: false,  // tell jQuery not to process the data
          contentType: false,
          success:  function(json){

            console.log('json'+JSON.stringify(json));

            if (json.status == "success") {
            // if ajax return success

               $("input[name=ebook]").val(json.name);
               // hide file

               $("input[type='file", selector).val('').hide();

               if(!$('#_file', selector).lenght)

                 $("#_file", selector).append("<span id='file_link'><a target=_blank href='"+base_url+"/download/library_files/"+json.name+"' style='color:blue'>"+json.name+"</a> (<a href='#' style='color:red' onclick='javascript:del_file(\""+json.name+"\", \""+selector.selector+"\")'>Устгах</a>)</span>");
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
        url:base_url+'/library/index/grid',
        datatype: 'json',
        mtype: 'GET',
        colNames:['№', 'Хэсэг',  'Төхөөрөмж', 'Гарчиг','Зохиогч', 'Хэвлэсэн он', 'ISBN'],
        colModel :[
                    {name:'spare_id', index:'spare_id', width:15, align:'center' },
                    {name:'section', index:'section.section', width:50, align:'center', stype:'select', searchoptions:{value:set_section()}, formatter:view_link},
                    {name:'equipment', index:'equipment.equipment', width:120, formatter:view_link },
                    {name:'title', index:'title', width:80, align:'center', formatter:view_link},
                    {name:'author', index:'author', width:60, align:'center', formatter:view_link},
                    {name:'year_of_pub', index:'year_of_pub', width:50, align:'center'},
                    {name:'isbn', index:'isbn', width:50, align:'center'}
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
      subGrid: false
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
                    url:    base_url+'/library/index/add',
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

function edit_modal(id, book) {

// initialize edit dialog
  $('input[name=id]', edit).val(id);


   //collect all by id all data
    $.ajax({
        type:    'POST',

        url:    base_url+'/library/index/get/',

        data:   { id: id},

        dataType: 'json',

        success:  function(json) {

          //var json = JSON.parse(json);
          // section = json.json.section;
          // route = json.json.route;

           $("#id", edit).val(json.id);
           $("#section_id option[value="+json.section_id+"]", edit).attr("selected", "selected");
           $("#equipment_id option[value="+json.equipment_id+"]", edit).attr("selected", "selected");

           $("#title", edit).val(json.title);
           $("#author", edit).val(json.author);
           $("#year_of_pub", edit).val(json.year_of_pub);
           $("#isbn", edit).val(json.isbn);

            if(json.ebook){
              $("#ebook", edit).val(json.ebook);
               $('#userfile', edit).hide();
               $('#_file', edit).append("<span id='file_link'><a style='color:blue;' href='"+base_url+"/download/library_files/"+json.ebook+"'>"+json.ebook+"</a> (<a href='#' style='color:red' onclick='del_file(\""+json.ebook+"\", \""+edit.selector+"\")'> Устгах </a>)</span>");
            }else{
               if(('#userfile',edit).is(':hidden')) $('#userfile', edit).show();
            }


           // console.log('closeing here');
           // $.each(section, function( key, value ) {
           //    $("#section_id option[value="+key+"]", edit).attr("selected", "selected");
           // });
           //$("#section_id", edit).trigger("chosen:updated");
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
                    url:    base_url+'/library/index/edit',
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
         url:    base_url+'/library/index/del_file/'+file_name,
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

   return "<a target='_blank' href='"+base_url+"/download/library_files/"+rowObject.ebook+"' >"+cellValue+"</a>";

}


function filter(form_name, target_id, target){

    //var c_id = $('#category_id', form_name).val();
    $.post(base_url+'/library/index/filter', {id:target_id}, function(newOption) {
        //{id:target_id, field:target_field, table:target}
        //neelttei haalttai,
        var select = $('#'+target+'_id', form_name);

        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }

        $('option', select).remove();

        $.each(newOption, function(val, text) {

           options[options.length] = new Option(val, text);

        });

    });
}
