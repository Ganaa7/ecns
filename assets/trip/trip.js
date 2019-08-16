/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var edit; var go; var come; var edit_spot;

$(document).ready(function(){
   var sampleTags = ['Улаанбаатар'];  
   $(".chosen-select").chosen();

   $('#trip').tagit({
        availableTags: ['Улаанбаатар']
   });


   //location_id onclick
   $( "#location", "#create-form").change( function() {
      var option = $(this).find("option:selected");     

       var route =[];
       $("input[class=route]", "#create-form").each(function() {
            route.push($(this).val());
        });
      
       var count  = count_array(route, '21');
       
       if(count>1){ // ehnii utga route-d nemegdsen bol !!! dahij route nemehgui 
          //marshrut-n urt >2-с ихб айх ёстой1
          alert('Маршрутыг зөв оруулсан тул дахин эцсийн цэгийг нэмэх шаардлаггүй!, Эцсийн цэгийг устгаад нэмэх боломжтой!');
       }else{
          //allow append
          $('#wrapper-tag', "#create-form").append("<span class ='btn-tag' id='"+option.val()+"'>"+$(this).find("option:selected").text()+" <a class='close-tag'><span class='text-icon'>×</span></a></span>");                    
           //ЭХЛЭХ ЦЭГ НЬ УБ бол төгсгөлийн цэгийг тодорхойлох хэрэгтэй!
          $("#create-form").append("<input type='hidden' name='route_id[]' class='route' value='"+option.val()+"'>");

          if($(this).find("option:selected").text()!='Улаанбаатар'){
              $('#wrapper-tag', "#create-form").append('<span>-></span>');   
          }
       }
            
      //console.log('option'+option.val());

   });

   $( "#location", "#edit-form").change( function() {
       var option = $(this).find("option:selected");     
       var route =[];

       $("input[class=route]", "#edit-form").each(function() {
            route.push($(this).val());
        });
        
        var count  = count_array(route, '21');

       if(count>1){ // ehnii utga route-d nemegdsen bol !!! dahij route nemehgui 
          //marshrut-n urt >2-с ихб айх ёстой1
          alert('Эхний цэгийг дахин оруулсан тул маршрутыг зөв оруулсан тул дахин байршил нэмэх шаардлаггүй!');
       }else{
          //allow append
          $('#wrapper-tag', "#edit-form").append("<span class ='btn-tag' id='"+option.val()+"'>"+$(this).find("option:selected").text()+" <a class='close-tag'><span class='text-icon'>×</span></a></span>");                    
           //ЭХЛЭХ ЦЭГ НЬ УБ бол төгсгөлийн цэгийг тодорхойлох хэрэгтэй!
          $("#edit-form").append("<input type='hidden' name='route_id[]' class='route' value='"+option.val()+"'>");

          if($(this).find("option:selected").text()!='Улаанбаатар'){
              $('#wrapper-tag', "#edit-form").append('<span>-></span>');   
          }
       }
   });
   // herev tuhain tag ni round trip bval yahuu

   jqgrid();

    $('#start_dt').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:   false
   });  

   $('#start_dt_edit', '#edit-form' ).datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

   $('#end_dt').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   }); 

    $('#end_dt_edit').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });  

   $( "#action_dt").datetimepicker({
     opened:false,
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
         
   });


 $('.available').css('width', '240px');
 $('.selected').css('width', '200px');

//comment
 comment=$('#comment-form');
   comment.dialog({
     autoOpen: false,
       width: 570,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', comment).val('');                                                  
          $(this).dialog("close");
       }
   });


edit=$('#edit-form');
   edit.dialog({
     autoOpen: false,
       width: 570,       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', edit).val('');                    
          $(".multiselect", edit).multiselect('destroy');
          $("#wrapper-tag", edit).empty();          
          $(".route", edit).remove();          
          $(this).dialog("close");
       }
   });

spot=$('#in-form');
   spot.dialog({
     autoOpen: false,
       width: 570,      
       height: 340, 
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', spot).val('');                    
          $(this).dialog("close");
       }
   });

   go=$('#go-form');
   go.dialog({
     autoOpen: false,
       width: 520,      
       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', go).val('');          
          $(".multiselect", go).multiselect('destroy');
          $(this).dialog("close");
       }
   });


edit_spot=$('#edit-spot');
   edit_spot.dialog({
     autoOpen: false,
       width: 570,      
       
       resizable: false,    
       modal: true,
       close: function () {
          $('p.feedback', $(this)).html('').hide();          
          $('input[type="text"], input[type="hidden"], select, textarea', edit_spot).val('');                    
          $(this).dialog("close");
       }
   });

//change section_id then return the section-s employee as ajax
$("#section_id", '#create-form').change(function(){
  //select section_id 
  var sec_id = $(this).val();
  console.log('section_id'+sec_id);    
  //var token = $('input["name=csrf_token_name"]', '#create-form').val();
  var csrf = $('input[name="csrf_hash_name"]', '#create-form').val(); 
  
  //send to the ajax retrieve result to add the select as value
   $.post( base_url+'/trip/index/filter', {id:sec_id, csrf_hash_name: csrf}, function(newOption){   
        var select = $('#employee_id', '#create-form');
        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }
        $('option', select).remove();
        $.each(newOption, function(val, text) {
           options[options.length] = new Option(text, val);        
        });
    }).done(function() {
      // alert( "second success" );
      $(".multiselect", '#create-form').multiselect('destroy');
    $(".multiselect", '#create-form').multiselect({
      minWidth:'400px'
    });
    $('.available').css('width', '240px');
    $('.selected').css('width', '200px');
    });
  
});


//change section_id then return the section-s employee as ajax
$("#section_id", edit).change(function(){
  //select section_id 
  var sec_id = $(this).val();
 // console.log('section_id'+sec_id);
  //send to the ajax retrieve result to add the select as value
  var csrf = $('input[name="csrf_hash_name"]', edit).val(); 
   $.post( base_url+'/trip/index/filter', {id:sec_id, csrf_hash_name: csrf}, function(newOption){   
        var select = $('#employee_id_edit', edit);
        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }
        $('option', select).remove();
        $.each(newOption, function(val, text) {
           options[options.length] = new Option(text, val);        
        });
    }).done(function() {
      // alert( "second success" );
      $(".multiselect", edit).multiselect('destroy');
    $(".multiselect", edit).multiselect();
    $('.available').css('width', '240px');
    $('.selected').css('width', '200px');
    });
  
});

var dialog;
 dialog = $( "#create-form" ).dialog({
  autoOpen: false,
  height: 'auto',
  width: 580,
  modal: true,
  buttons: {        
    "Хадгалах": function (){
       var data = {};           
       var inputs = $('input[type="text"], input[type="hidden"], select, textarea', '#create-form'); 
       
      inputs.each(function(){
          var el = $(this);          
             data[el.attr('name')] = el.val();             
      });
      
      data['routes']=set_route('create');
      //data['csrf_hash_name']= csrf;
      //call ajax here
       $.ajax({
          type:     'POST',
          url:    base_url+'/trip/index/add/',
          data:   data,
          dataType: 'json', 
          async: false,
          success:  function(json){ 
             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                // close the dialog                                                
                dialog.dialog( "close" );
                showMessage(json.message, 'success');
                // amjilttai bolson tohioldold ene heseg uruu shidne
               reload();
               // show the success message               
             }else{  // ямар нэг юм нэмээгүй тохиолдолд
                // jump to the top                
                //$("#containerDiv").animate({ scrollTop: 0 }, "fast");
                $('p.feedback', '#create-form').removeClass('success, notify').addClass('error').html(json.message).show();
             }
          }
      });// send the data via AJAX to our controller    
    },
    "Хаах": function() {
      dialog.dialog( "close" );
    }
  },
  close: function() {    
    $(".multiselect", dialog).multiselect('destroy');
    $(".route", dialog).remove();    
    $(this).dialog("close");
  }
});


$( "#create_trip" ).on( "click", function() {
  //append UB
    $('#wrapper-tag', '#create-form').append("<span class='btn-tag' id='tag_21'>Улаанбаатар </span><span>-></span>");
    $('#create-form').append("<input type='hidden' name='route_id[]' id='route_id' class='route' value='21'> ");
    dialog.dialog( "open" );
});

// close tag edit
  $( ".close-tag", edit).live('click', function() {
    //alert('hi its called delete'+);
    var route_id = $(this).parent().attr('id')
    $(this).parent().next().remove();
    $(this).parent().remove();
    // remove the input 
    if(route_id==21){
       $( "input[value="+route_id+"][class='route']", edit).last().remove();     
    }else
       $( "input[value="+route_id+"][class='route']", edit).remove();   
});

 $( ".close-tag", "#create-form").live('click', function() {
    //alert('hi its called delete'+);
    var route_id = $(this).parent().attr('id')
    $(this).parent().next().remove();
    $(this).parent().remove();
    // remove the input 
    $( "input[value="+route_id+"]", "#create-form").remove();   
});

});
  
function jqgrid(){
    $("#grid").jqGrid({ 
        url:base_url+'/trip/index/grid',
        datatype: 'json', 
        mtype: 'GET', 
        colNames:['№', 'Хэсэг',  'ИТА', 'Чиглэл', 'Зорилго', 'Т/хэрэгсэл', 'Нийт зай', 'Эхлэх/t', 'Дуусах/t', 'is_come'], 
        colModel :[ 
                    {name:'trip_no', index:'trip_no', width:15, align:'center' },                     
                    {name:'section', index:'trip_section.section', width:50, align:'center', stype:'select', searchoptions:{value:set_section()}}, 
                    {name:'employee', index:'employee', width:120 },                     
                    {name:'location', index:'location', width:80, align:'center', searchoptions:{sopt:['cn']}},                   
                    {name:'purpose', index:'purpose', width:50, align:'center'}, 
                    {name:'transport', index:'transport', width:40, align:'center' },
                    {name:'distance', index:'distance', width:40, align:'center' },
                    {name:'start_dt', index:'start_dt', width:60, align:'center', searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },                     
                    {name:'end_dt', index:'end_dt', width:60, align:'center', searchoptions:{sopt:['eq','ne','le','lt','gt','ge'], dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} } },
                    {name:'est_dt', index:'est_dt', hidden:true, viewable:true}
                    
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
        rowList:[10,20,30,40],                    
        sortname: 'id', 
        sortorder: "asc", 
        viewrecords: true, 
        gridview: true, 
        // imgpath: 'themes/basic/images', 
        caption: 'Албан томилолтын бүртгэл',
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
              if(check_hours(rowData.est_dt)>0){
                  trElement.removeClass('ui-widget-content');
                  trElement.addClass('argent');               
               }             
             trElement.addClass('context-menu');  
             
           }         
       },
      subGrid: true,
      subGridRowExpanded: function(subgrid_id, row_id) {
         var subgrid_table_id;
         subgrid_table_id = "_"+subgrid_id;
         jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
         jQuery("#"+subgrid_table_id).jqGrid({
            autoencode: false,
            url: base_url+'/trip/index/sub_grid?id='+row_id, //"/ecns/wm_ajax/incomeDtl?q=2&id="+row_id,
            datatype: "xml",            
            colNames: ['#', 'Чиглэл','Гарах', 'Очих','Замын урт','Гарах цаг','Очих цаг', 'Is_come', 'Мэдээ өгсөн','Тэмдэглэл'],
            colModel: [
              {name:"route_id",index:"route_id",width:80,key:true,hidden:true, viewable:true},
              {name:"num",index:"num",width:80,key:true, align:"center"},
              {name:"from_route",index:"from_route",width:150, align:"center"},
              {name:"to_route",index:"to_route",width:150, align:"center"},
              {name:"distance",index:"distance",width:100, align:"center"},              
              {name:"out_dt",index:"out_dt",width:100,align:"left"},
              {name:"est_dt",index:"est_dt", width:100,align:"left"},
              {name:"is_come",index:"is_come", hidden:true, viewable:true},
              {name:"infoby",index:"infoby", width:120, align:"center"},
              {name:"comment",index:"comment", width:200}
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
                 //console.log(trElement);
                 trElement.addClass('context-menu-sub');   
                // console.log(JSON.stringify(rowData));
                // console.log('data'+rowData.is_come);
                 // switch(rowData.is_come){
                 //  //create
                 //   case "N": 
                 //     trElement.removeClass('ui-widget-content');
                 //     trElement.addClass('argent');
                 //    break;                       
                 // }
                 if(check_hours(rowData.est_dt)>0&&rowData.is_come!=='Y'){
                    trElement.removeClass('ui-widget-content');
                    trElement.addClass('argent');               
                 }
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

function init_edit(trip_id){ 
  // ajax call data here
  var data = { id: trip_id}; 
  //$('.multiselect', edit).multiselect({ remoteUrl:  base_url+'/trip/index/get/', remoteParams: { publickey: trip_id } });
  var section_id, dtl, section, route;
  $('#wrapper-tag', edit).append("<span class='btn-tag' id='tag_21'>Улаанбаатар </span><span>-></span>");
  $(edit).append("<input type='hidden' name='route_id[]' id='route_id' class='route' value='21'> ");
  $('.route', edit).first().val(21);

   $.ajax({
       type:    'POST',
       url:    base_url+'/trip/index/get/',
       data:   { id: trip_id},
       dataType: 'json', 
       success:  function(json) {
        //var data = JSON.parse(json);
         //var json = JSON.parse(json);                    
         dtl = json.json.dtl;         
         section = json.json.section;         
         route = json.json.route;            
          $("#trip_id", edit).val(json.json.id);          
          $("#trip_no", edit).val(json.json.trip_no);          
          $("#section_id option[value="+json.json.section_id+"]", edit).attr("selected", "selected");
          //$("#section_id", edit).val(json.json.section_id);       
          //section_id =json.json.section_id;   
          // $("#employee_id", edit).val(json.employee_id);          
           $("#location_id", edit).val(json.json.location_id);          
           $("#purpose", edit).val(json.json.purpose);          
           $("#transport", edit).val(json.json.transport);          
           $("#start_dt_edit", edit).val(json.json.start_dt);          
           $("#end_dt_edit", edit).val(json.json.end_dt);          
          // console.log('closeing here');
          $.each(section, function( key, value ) {                      
             $("#section_id option[value="+key+"]", edit).attr("selected", "selected");
          });
          $("#section_id", edit).trigger("chosen:updated");
      }
    }).done(function() {
       $.post( base_url+'/trip/index/filter', {id:0}, function(newOption){   
            var select = $('#employee_id_edit', edit);
            if(select.prop) {
             var options = select.prop('options');
            }else {
             var options = select.attr('options');
            }
            $('option', select).remove();
            $.each(newOption, function(val, text) {
                options[options.length] = new Option(text, val);        
            });            
        }).done(function() {
        
          $.each(dtl, function( key, value ) {            
            $("#employee_id_edit option[value="+key+"]", edit).attr("selected", "selected");
          });

          $("#employee_id_edit").multiselect();
          
          $('.available').css('width', '240px');
          $('.selected').css('width', '200px');
        });

         $.each(route, function( key, value ) {                    
            // console.log('location'+$("#location option[value="+key+"]", edit).text());            
            $('#wrapper-tag', "#edit-form").append("<span class ='btn-tag' id='"+value+"'>"+$("#location option[value="+value+"]", edit).text()+" <a class='close-tag'><span class='text-icon'>×</span></a></span>");                    
           //ЭХЛЭХ ЦЭГ НЬ УБ бол төгсгөлийн цэгийг тодорхойлох хэрэгтэй!
            $("#edit-form").append("<input type='hidden' name='route_id[]' class='route' value='"+value+"'>");
             if($("#location option[value="+value+"]", edit).text()!=='Улаанбаатар'){
                $('#wrapper-tag', "#edit-form").append("<span>-></span>");   
             }

          });

        edit_dialog();
    });
      // $('#employee_id option[value=4]', edit).attr('selected','selected');
      // $('#employee_id option[value=24]', edit).attr('selected','selected');
     // $("#employee_id", edit).multiselect();
        //   edit_dialog();
} 

function edit_dialog(){ 
    edit.dialog({ 
       buttons: { 
          "Хадгалах": function () {
            //confirm message here!
           if (confirm("Хэрэв энэ томилолтын маршрутыг өөрчилсөн тохиолдолд хасагдсан маршрут дээрх ирсэн, очсон мэдээлэл устгагдах болно гэдгийг анхаарна уу! Засахдаа итгэлтэй байна уу?") == true){
             $('p.feedback', edit).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
             var data = {};
             var inputs = $('input[type="text"], input[type="hidden"], select' , edit);
              
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });

              data['routes']=set_route('edit');
               // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:   'POST',
                  url:    base_url+'/trip/index/edit/',
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
                      reload();

                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', edit).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller 
           } // end confirmation
          },            
          "Хаах": function () {
              edit.dialog("close");
          }
       }
      }); 
    edit.dialog('open'); 
}

function init_spot(trip_id, parent_id){
  var data = { id: trip_id, parent_id:parent_id};   
  var status, is_come, out_dt;
    $.ajax({
       type:    'POST',
       url:    base_url+'/trip/index/get_route/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
           status =json.status;
           
           // console.log('come'+is_come);

          if(json.status=='true'){                            
            $('#from_route', '#in-form').val(json.json.from_route);
            $('#to_route', '#in-form').val(json.json.to_route);
            $('#distance', '#in-form').val(json.json.distance);
            $('#out_dt', '#in-form').val(json.json.out_dt);  
            $('#est_dt_', '#in-form').val(json.json.est_dt);  
            out_dt = json.json.out_dt;
            is_come = json.json.is_come;
          }else{
            out_dt = null;
            is_come = 'N';
          } 

          gen_option(json, '#in-form');
      }
    }).done(function() {
       if(status=='true'&&out_dt&&is_come=='N'){
          spot_dialog(trip_id, parent_id);   
       }else if(status=='false'){
          alert('Өмнөх чиглэл явж буй ИТА-г очоогүй байхад дараагийн чиглэлд гарсан цагийг өгөх боломжгүй!');  
       }else if(out_dt==null){
          alert('"Гарсан" эсэхийг тэмдэглээгүй тохиолдолд "Очсон" тэмдэглэх боломжгүй');        
       }else if(status=='true'&&is_come=='Y')
         alert('Энэ чиглэлд аль хэдийн очсон тул дахин тэмдэглэх шаардлаггүй!');     

    });
}

function spot_dialog(id, parent_id){ 
  spot.dialog({ 
       buttons: { 
          "Хадгалах": function () {
             $('p.feedback', spot).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
                var data = { route_id:id, parent_id:parent_id};
                var inputs = $('input[type="text"], input[type="hidden"], select' , spot);
              
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });
               // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:   'POST',
                  url:    base_url+'/trip/index/update_route/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      spot.dialog("close");
                      // close the dialog                         
                      showMessage(json.message, 'success');
                      // show the success message
                      reload();
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', spot).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller 
          },            
          "Хаах": function () {
              spot.dialog("close");
          }
       }
      }); 
    spot.dialog('open'); 
}

// out dialog
function go_dialog(id, parent_id){ 
  go.dialog({ 
       buttons: { 
          "Хадгалах": function () {
             $('p.feedback', go).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
                var data = {};
                var inputs = $('input[type="text"], input[type="hidden"], select' , go);
              
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });
                data['route_id']=id;                
                data['parent_id']=parent_id;

               // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:   'POST',
                  url:    base_url+'/trip/index/update_route/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      go.dialog("close");
                      // close the dialog                         
                      showMessage(json.message, 'success');
                      // show the success message
                      reload();
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', go).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller 
          },            
          "Хаах": function () {
              go.dialog("close");
          }
       }
      }); 
    go.dialog('open'); 
}

// comment dialog
function comment_dialog(trip_id, parent_id){ 
  var data = { id: trip_id, parent_id:parent_id};   
  var status;
  // get all data here
  $.ajax({
     type:    'POST',
     url:    base_url+'/trip/index/get_route/',
     async: false,
     data:   data,
     dataType: 'json', 
     success:  function(json) {  
         if(json.status=='false'){
             status = json.status;
         }else{
          $('#from_route', '#comment-form').val(json.json.from_route);
          $('#to_route', '#comment-form').val(json.json.to_route);
          $('#distance', '#comment-form').val(json.json.distance);        
          $('#out_dt', '#comment-form').val(json.json.out_dt);  
          $('#est_dt', '#comment-form').val(json.json.est_dt);            
        } 
    }
}).done(function() {
    if(status!=='false'){
       comment.dialog({ 
       buttons: { 
          "Хадгалах": function () {
             $('p.feedback', comment).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
                var data = {};
                var inputs = $('textarea' , comment);
              
                inputs.each(function(){
                  var el = $(this);
                  data[el.attr('name')] = el.val();
                });
                data['route_id']=trip_id;                

               // collect the form data form inputs and select, store in an object 'data'
              $.ajax({
                  type:   'POST',
                  url:    base_url+'/trip/index/comment/',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      comment.dialog("close");
                      // close the dialog                         
                      showMessage(json.message, 'success');
                      // show the success message
                      reload();
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', comment).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });// send the data via AJAX to our controller 
          },            
          "Хаах": function () {
              comment.dialog("close");
          }
       }
      }); 
      comment.dialog('open');     
    }else{
      alert('Өмнөх чиглэлд эхлээд тэмдэглэл хийнэ үү!');
    }
  
  });
}


// out dialog
function edit_spot_dialog(id, parent_id){ 
    var data = { id: id, parent_id: parent_id};   

  // get all data here
  $.ajax({
       type:    'POST',
       url:    base_url+'/trip/index/get_route/',
       data:   data,
       dataType: 'json', 
       success:  function(json) {
          status =json.status;
          if(status=='true'){
            is_come = json.json.is_come;           
            out_dt = json.json.out_dt;
            $('#from_route', edit_spot).val(json.json.from_route);
            $('#to_route', edit_spot).val(json.json.to_route);
            $('#distance', edit_spot).val(json.json.distance);
            $('#out_dt_edit', edit_spot).val(json.json.out_dt);              
            if(is_come!=='Y'){            $
              $('#wrap_est_dt').hide();
            }else{             
               $('#est_dt_edit', edit_spot).val(json.json.est_dt);               
            } 
            gen_option(json, '#edit-spot');
            $('#comment', edit_spot).val(json.json.comment);
            $("#employee_id", edit_spot).val(json.json.infoby_id);  
          }
          
        }
  }).done(function() {
        edit_spot.dialog({ 
           buttons: { 
              "Хадгалах": function () {
                 $('p.feedback', edit_spot).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
                  // logical function here
                    var data = {};
                    var inputs = $('input[type="text"], input[type="hidden"], select, textarea' , edit_spot);
                  
                    inputs.each(function(){
                      var el = $(this);
                      data[el.attr('name')] = el.val();
                    });
                    data['route_id']=id;                
                    data['parent_id']=parent_id;                

                   // collect the form data form inputs and select, store in an object 'data'
                  $.ajax({
                      type:   'POST',
                      url:    base_url+'/trip/index/update_route_by/',
                      data:   data,
                      dataType: 'json', 
                      success:  function(json){ 
                        if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                          //энд үндсэн утгуудыг нэмэх болно.
                          edit_spot.dialog("close");
                          // close the dialog                         
                          showMessage(json.message, 'success');
                          // show the success message
                          reload();
                        }                  
                        else{  // ямар нэг юм нэмээгүй тохиолдолд
                          $('p.feedback', edit_spot).removeClass('success, notify').addClass('error').html(json.message).show();
                        }
                      }
                  });// send the data via AJAX to our controller 
              },            
              "Хаах": function () {                  
                  $('#wrap_est_dt').show();
                  edit_spot.dialog("close");
              }
           }
          }); 
        if(out_dt){
           edit_spot.dialog('open'); 
         }else{
          alert('Энэ чиглэлд явсан хугацаа оруулаагүй тул засах боломжгүй!');
        } 
          
      }); 
}


function reload(){
  //$("#grid").trigger("reloadGrid"); 
    ///$("#grid").setGridParam({datatype:'json', page:1}).trigger('reloadGrid');
    jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');

}

function set_route(is_flag){
  var route =[];
      if(is_flag =='create'){
         $("input[class='route']","#create-form").each(function() {
            if($(this).val() ) {
              route.push({                
                  route_id: $(this).val()
              });
            }
        }); 
      }else
       $("input[class='route']","#edit-form").each(function() {
          if($(this).val() ) {
            route.push({                
                route_id: $(this).val()
            });
          }
        }); 

       console.log('routes'+route);
        // then to get the JSON string
      var jsonString = JSON.stringify(route);
      //console.log('json'+jsonString);
      return jsonString;
}

function count_array(array, value) {
  var counter = 0;
  for(var i=0;i<array.length;i++) {
    if (array[i] === value) counter++;
  }
  return counter;
}

function current_date(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  var h = addZero(today.getHours());
  var m = addZero(today.getMinutes());
  var s = addZero(today.getSeconds());

  if(dd<10) {
   dd='0'+dd;
  } 

  if(mm<10) {
    mm='0'+mm;
  }
  return  yyyy+'-'+mm+'-'+dd+' '+h + ":" + m + ":" + s;
}

function addZero(i) {
   if (i < 10) {
      i = "0" + i;
   }
   return i;
}

function check_hours(vdate){   
   var cdate = new Date(current_date());
   var vdate = new Date (vdate);
   
   var diff_seconds =Math.abs(cdate-vdate);   // Math.abs
   
  var x = diff_seconds / 1000;
  //var seconds = x % 60;
  var x = x/60; //minute
  //var minutes = x % 60;
  x = x/60; // hours
  //var hours = x % 24
    return x;
}


function gen_option(json, form){
  // songogdson ИТА-г харуулах
    var select = $('#employee_id', form);
    if(select.prop) {
       var options = select.prop('options');
    }else {
       var options = select.attr('options');
    }
    $('option', select).remove();
    select.append(new Option("ИТА-с сонго!", 0));
    $.each(json.dtl, function(val, text) {                                 
       options[options.length] = new Option(text.employee, text.employee_id);   
    });
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