var add, minus, view, my_url, edit;

my_url = base_url+'/training/guidance/index/';

var current_form;

$(function(){

    $( document ).tooltip();


    $('#date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });    




   jqgrid(); 

   //add dialog
  add=$('#create-form');
   add.dialog({
     autoOpen: false,
       width: 600,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          

          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');                    
                 
          $(this).dialog("close");

          current_form = null;
       }
   });  

   // edit view

   edit=$('#edit-form');

   edit.dialog({

     autoOpen: false,

       width: 600,       

       resizable: false,    

       modal: true,

       close: function () {

          $('p.feedback', $(this)).html('').hide();  

          $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');   

          $('#file_link', $(this)).remove();            

          $(this).dialog("close");

          current_form = null;
       }

   });

    $('#date', edit).datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });  

   //comment
 view=$('#view-form');
 
   view.dialog({

       autoOpen: false,

       width: 600,       

       resizable: false,    

       modal: true,

       close: function () {

          $('p.feedback', $(this)).html('').hide();          

          $('input[type="text"], input[type="hidden"], select, textarea', view).val('');                                                  

          $('#file_link a', view).text('').attr("href", '');   

          $(this).dialog("close");

       }
   });


    // file upload here         
   $('#_file', add).change(function (){

      file_upload(add);  
  
   });

   // file upload here         
   $('#_file', edit).change(function (){

      file_upload(edit);  
  
   });



   $("#section_id", add).change(function() {
       // if you change thsi is it will filter by this id and return equipoment

      var filter_id = $(this).val();  

      console.log('this_id'+filter_id);

      // its equipment_id filter by ganaa
      filter_post(add, filter_id, 'equipment_id');      

   });



   //slider here
   var handle = $( "#custom-handle", add );
   
   var handle2  = $( "#custom-handle-2", add );

    $( "#slider", add ).slider({
        range: false,
        min: 0,
        max: 200,
        create: function() {
          handle.text( $( this ).slider( "value" ) );
        },
        slide: function( event, ui ) {
           $( "input[name=hours]", '#create-form' ).val(ui.value+ "цаг");
          handle.text( ui.value + " цаг"  );
        }
    });

     $( "#slider2", add ).slider({
        range: false,
        min: 0,
        max: 60,
        create: function() {
          handle2.text( $( this ).slider( "value" ) );
        },
        slide: function( event, ui ) {
           var value2 = $( "#minute", '#create-form' ).val();
           $( "input[name=minute]", '#create-form' ).val(ui.value+ "минут");         
           handle2.text( ui.value+" мин" );
        }
    });



  

    

});

function jqgrid(){
    $("#grid").jqGrid({ 
        url:base_url+'/training/guidance/index/grid',
        datatype: 'json', 
        mtype: 'GET', 
        colNames:['#', '№', 'Хэсэг',  'Төхөөрөмж', 'Хөтөлбөр','Газар', 'Цаг', 'Батлагдсан огноо'], 
        colModel :[ 
                    {name:'id', index:'id', width:15, align:'center'},                     
                    {name:'number', index:'number', width:20, align:'center'},                     
                    {name:'section', index:'section', width:50, align:'center', stype:'select', searchoptions:{value:set_section()}}, 
                    {name:'equipment', index:'equipment', width:100, formatter:view_link},                     
                    {name:'guidance', index:'guidance', width:80, align:'center', formatter:view_link},                     
                    {name:'location', index:'location', width:60, align:'center', formatter:view_link},                     
                    {name:'hours', index:'hours', width:50, align:'center', formatter:view_link}, 
                    {name:'date', index:'date', width:50, align:'center'}
                    
                    
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
        sortname: 'guidance.id',
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
             
           }         
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

    str =':Бүгд;ТТИХ:ТТИХ;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ';
   return str;
}


function add_modal(){       
 
       add.dialog({ 
          buttons: { 
            "Хадгалах": function () {  
               //var data = {};
               var hours_val= $( "input[name=hours]", add).val();

               var minute = $( "input[name=minute]", add).val();

               // $( "input[name=hours]", '#create-form' ).val(hours_val+ ' '+minute);

               var data = add.serialize();
               // var inputs = $('input[type="text"], input[type="hidden"], select' , add);                
               //    inputs.each(function(){
               //      var el = $(this);
               //      data[el.attr('name')] = el.val();
               //    });
                 // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type:   'POST',
                    url:    base_url+'/training/guidance/index/add', 
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

function view_link(cellValue, options){
   return "<a href='#' onclick='page_view(" + options.rowId + ", \""+cellValue+"\")' >"+cellValue+"</a>"; 
}


// view dialog
function page_view(_id, title){ 

   view.dialog('option', 'title', 'Хөтөлбөр: "' + title +'"');

  var data = { id: _id};   

  var status;
  // get all data here
  $.ajax({
     type:    'POST',
     url:    base_url+'/training/guidance/index/get/',
     async: false,
     data:   data,
     dataType: 'json', 
     success:  function(json) {  
         if(json.status=='false'){
             status = json.status;
         }else{

          set_dropdown(view, 'equipment_id', json.json.equipment);  

          $('#number', '#view-form').val(json.json.number);

          $('#equipment_id', '#view-form').val(json.json.equipment_id);
          
          $("#section_id option[value="+json.json.section_id+"]", view).attr("selected", "selected");

          $('#guidance', '#view-form').val(json.json.guidance);

          $('#location', '#view-form').val(json.json.location);

          $('#hours', '#view-form').val(json.json.hours);          

          $('#date', '#view-form').val(json.json.date);            

          //$('input[name=_file]', '#view-form').val(json.json.section_id);  
          $("#file_link a").text(json.json.Gfile.file_name).attr("href", base_url+"/pdf/web/viewer.html?file=../../download/guidance_file/"+json.json.Gfile.file_name);

        } 
    }
  }).done(function() {
      if(status!=='false'){
         view.dialog({ 
         buttons: { 
            "Хаах": function () {
                view.dialog("close");
            }
         }

        }); 

        view.dialog('open');     
      }else{
        alert('Алдаа гарлаа дахин оролдоно уу!');
      }
    
  });
}


//filter by this equipments
function filter_post(form_name, filter_id, target_field){
    
    //used in edit form change location
    // var section_id = $('#section_id', form_name).val(); 
    $.post( base_url+'/training/guidance/index/filter', {id:filter_id, field:target_field}, function(newOption) {   

           var select = $('#'+target_field, form_name);

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

function feeds(form, css_class, msg){
  // if($('p.feedback', add).hasClass('error')) $('p.feedback', add).removeClass('error');  
  // if($('p.feedback', add).hasClass('success')) $('p.feedback', add).removeClass('success');
  $('p.feedback',form).removeClass('error', 'success');

  $('p.feedback', form).addClass(css_class).html(msg).show();                        

  $('p.feedback', form).stop().fadeIn('fast', function(){

       $('p.feedback', form).delay(7000).fadeOut();
      // fade out again after 3 seconds  
  });
}


//file-g ustgah 

function del_file(id, file){

  // console.log('fnam'+whichIsVisible());
  var form = current_form;

  if (confirm("["+file+"] энэ файлыг устгахдаа итгэлтэй байна уу?") == true) {

      $.ajax({

         type:    'POST',

         url:    my_url+'delete_file/'+id,

         data:   {id:id, file_name:file},

         dataType: 'json', 

         success:  function(json) {

            if(json.success){
              
              $('input[name=file_id]', form).val('');
              //then remove link
              feeds(form, 'success', json.message)

              $('#file_link', form).remove();

              $("#_file", form).show();

            }else{

              feeds(form, 'error', json.message)

              $('#file_link', form).remove();

              $("#_file", form).show();

              $('input[name=file_id]', form).val('');
            }
         }
       });
   
  } else {
     // nothing 
     return 0;
  }
}

//delete function here

function _delete(id) {   

  var ask=confirm("ТА энэ хөтөлбөрийг устгахдаа итгэлтэй байна уу?");

  if(ask){       

     var post = { id: id };

     $.ajax({

         type:     'POST',

         url:    my_url+'delete/',

         data:   post,

         dataType: 'json', 

         success:  function(json){ 

            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
                             
               showMessage(json.message, 'success');

               $("#grid").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
            }                  
            else{ 

              showMessage(json.message, 'error');
            
            }
        }
     });
  }
}


function edit_modal(id, title){    


    current_form = edit;

    $('input[name=id]', edit).val(id);

    edit.dialog('option', 'title', 'Засах: ' + title );

    var json;

    //get data here 
    if( json = get_data(id)){

        //set_data to the views inputs data
          // $('#equipment_id', edit).val(json.json.equipment_id);          

          set_dropdown(edit, 'equipment_id', json.json.equipment);

          $('#number', edit).val(json.json.number);

          $("#section_id option[value="+json.json.section_id+"]", edit).attr("selected", "selected");

          $('#guidance', edit).val(json.json.guidance);

          $('#location', edit).val(json.json.location);

          $('input[name=hours]', edit).val(json.json.hours);          

          $('input[name=minute]', edit).val(json.json.minute);          

          $('#date', edit).val(json.json.date);     

          $("input[type='file", edit).val('').hide();  

          if(!$('#file_wrap', edit).lenght)

             $("#file_wrap", edit).append("<span id='file_link'><a href='"+base_url+"/pdf/web/viewer.html?file=../../download/guidance_file/"+json.json.Gfile.file_name+"' target='_blank' style='color:blue'>"+json.json.Gfile.file_name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.json.Gfile.file_id+", \""+json.json.Gfile.file_name+"\")'>Устгах</a>)</span>");                      


           $('input[name=file_id]', edit).val(json.json.Gfile.file_id);


          var edit_handle = $( "#custom-handle", edit );

          var edit_handle_2 = $( "#custom-handle-2", edit );

          $( "#slider", edit ).slider({
            range: false,
            min: 0,
            max: 200,
            value: json.json.hours,
            create: function() {
              edit_handle.text( json.json.hours+'цаг');
            },
            slide: function( event, ui ) {
              
              $( "input[name=hours]", edit).val(ui.value);
              
              edit_handle.text( ui.value + " цаг"  );
            }
          });

           $( "#slider2", edit ).slider({
            range: false,
            min: 0,
            max: 60,
            value: json.json.minue,
            create: function() {
              edit_handle_2.text( $( this ).slider( "value" ) );
            },
            slide: function( event, ui ) {                      
                $( "input[name=minute]", edit).val(ui.value);                               
               edit_handle_2.text( ui.value+" мин" );
            }
          });

    }
     

     edit.dialog({ 
        buttons: { 
          "Хадгалах": function () {  
             //var data = {};
             var hours_val= $( "input[name=hours]", edit).val();

             var minute = $( "input[name=minute]", edit).val();

             $( "input[name=hours]", '#create-form' ).val(hours_val+ ' '+minute);

             var data = edit.serialize();
   
              $.ajax({

                  type:   'POST',

                  url:    base_url+'/training/guidance/index/edit', 

                  data:   data,

                  dataType: 'json', 

                  async: false,

                  success:  function(json){ 

                    if (json.status == "success") {    

                      edit.dialog("close");
                      
                      // close the dialog                         
                      showMessage(json.message, 'success');

                      // show the success message                      
                      jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');

                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд

                      $('p.feedback', edit).removeClass('success, notify').addClass('error').html(json.message).show();

                    }
                  }
              });
          
          },            
          "Хаах": function () {
              edit.dialog("close");
          }
      }     
    });   
     edit.dialog( "open" );

}


function get_data(id){

    var data = { id: id};   

    var result ; 

    $.ajax({

     type:    'POST',

     url:    my_url+'/get',

     async: false,

     data:   data,

     dataType: 'json', 

     success:  function(json) {         

        result = json;
    }

  });


    return result;
}

function set_dropdown(form, select_tag_id, json){

   var select = $('#'+select_tag_id, form);

   if(select.prop) {

      var options = select.prop('options');

   }else {

      var options = select.attr('options');

   }

   $('option', select).remove();

   $.each(json, function(val, text) {

      options[options.length] = new Option(text, val);        

   });    
}


function file_upload(form){

  current_form = form;
  
  var uploadfile = new FormData(form[0]); 

  $.ajax({

        url:   my_url+'upload' ,

        type:    'POST',                               

        data:   uploadfile,                                              

        processData: false,  

        contentType: false,

        success:  function(json){                   

          if (json.status == "success") {      

            feeds(form, 'success', json.name+' нэртэй файлыг амжилттай байршууллаа!');                
             
            if(json.name){

               $("input[type='file", form).val('').hide();                    

                if(!$('#file_wrap', form).lenght)

                   $("#file_wrap", form)
                        .append("<span id='file_link'><a href='"+base_url+"/pdf/web/viewer.html?file=../../download/guidance_file/"+json.name+"' target='_blank' style='color:blue'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.file_id+", \""+json.name+"\")'>Устгах</a>)</span>");                      
                        // append('<span id="file_link"><a href="'+base_url+'/pdf/web/viewer.html?file=../../download/guidance_file/'+json.name+'" target="_blank" style="color:blue">'+json.name+'</a> (<a href="#" style="color:red" onclick="del_file('+json.file_id+', '+json.name+')">Устгах</a>)</span>');                      

                $('input[name=file_id]', form).val(json.file_id);
             }                                              
          }       
          else{  // ямар нэг юм нэмээгүй тохиолдолд                                

            feeds(form, 'error', json.message);

            $("input[type='file", form).val('');
          }
        }
    });

    return true;
}


    // $.ajax({

        //     url:   my_url+'upload' ,

        //     type:    'POST',                               

        //     data:   uploadfile,                                              

        //     processData: false,  // tell jQuery not to process the data

        //     contentType: false,

        //     success:  function(json){                   

        //       console.log(json.status);

        //       //хэрэв json.tatus = success bol qgsly нэрийг засаад

        //       if (json.status == "success") {      

        //         feeds('success', json.name+' нэртэй файлыг амжилттай байршууллаа!');                
                 
        //         if(json.name){

        //            $("input[type='file", add).val('').hide();                    

        //             if(!$('#_file', add).lenght)

        //                $("#_file", add).append("<span id='file_link'><a href='"+base_url+"/pdf/web/viewer.html?file=../../download/guidance_file/"+json.name+"' target='_blank' style='color:blue'>"+json.name+"</a> (<a href='#' style='color:red' onclick='del_file("+json.file_id+", \""+json.name+"\")'>Устгах</a>)</span>");                      

        //             $('input[name=file_id]', add).val(json.file_id);
        //          }                                              
        //       }       
        //       else{  // ямар нэг юм нэмээгүй тохиолдолд                                

        //         feeds('error', json.message);

        //         $("input[type='file", add).val('');
        //       }
        //     }
        // }); 