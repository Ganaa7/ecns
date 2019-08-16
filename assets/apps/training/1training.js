
$(function() {
// var pageWidth = $(window).width();  
//     var width = $('#main_wrap').width();   
 
    $( "#tabs" ).tabs();

    sel_str=set_type();

    jQuery("#grid").jqGrid({        
        url:base_url+'/training/index/grid',
        datatype: "json",
        mtype: 'GET',
        height: '500',  
        width:'1260',
        colNames:['#','Үнэмлэх #','Байршил', 'Нэр.Овог', 'Төрөл', 'Мэргэжил', 'Олгосон /t','Хүчинтэй /t', 'Ажиллах төхөөрөмж', 'Утас', 'Имэйл'],
        colModel:[
          {name:'trainer_id',index:'trainer_id',search:false, width:30},
          {name:'license_no',index:'license_no', width:60,align:"center", searchoptions:{sopt:['cn']}},
          {name:'location',index:'location', width:80, align:"right", stype:'select', searchoptions:{value:sel_str}},    
          {name:'fullname',index:'fullname', width:90, align:"right", searchoptions:{sopt:['cn']}},
          {name:'license_type',index:'license_type', width:80,align:"right", searchoptions:{sopt:['cn']}},
          {name:'occupation',index:'occupation', width:80, align:"right", searchoptions:{sopt:['cn']}},
          {name:'issued_date',index:'issued_date', width:60, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});}, sopt:['cn'] } },   
          {name:'valid_date',index:'valid_date', width:60, align:'center', searchoptions:{dataInit:function(el){$(el).datepicker({ dateFormat:"yy-mm-dd" }).change(function(){$("#grid")[0].triggerToolbar();});} }},   
          {name:'license_equipment',index:'license_equipment', width:80, searchoptions:{sopt:['cn']}},  
          {name:'phone',index:'phone', width:80, searchoptions:{sopt:['cn']}},  
          {name:'email',index:'email', width:80, searchoptions:{sopt:['cn']}}
        ],
         jsonReader : {
                    page: "page",
                    total: "total",
                    records: "records",
                    root:"rows",
                    repeatitems: false,
                    id: "trainer_id"
        },
        rowNum:20,
        rowList:[10,20,30],
        pager: '#pager',
        sortname: 'trainer_id',
        viewrecords: true,
        sortorder: "asc",
        caption:"ИТА мэдээлэл",
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');          
           for (var i=0;i<rowIds.length;i++){ 
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
              if((check_date(rowData.valid_date)<=30)||(!rowData.valid_date.length)||(rowData.valid_date=='0000-00-00')){
                 trElement.removeClass('ui-widget-content');
                 trElement.addClass('argent');                
              }              
              if(check_date(rowData.valid_date)>30&&check_date(rowData.valid_date)<=60&&rowData.valid_date.length>0){
                 trElement.removeClass('ui-widget-content');
                 trElement.addClass('warning');                  
              }

               trElement.addClass('context-menu');
           }         
        }  
    });
    
    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
    jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true});

    // outgrid
    jQuery("#outgrid").jqGrid({
      url: base_url + '/training/index/out_grid',
      datatype: "json",
      mtype: 'GET',
      height: '500',
      width: '1260',
      colNames: ['#', 'Үнэмлэх #', 'Байршил', 'Нэр.Овог', 'Төрөл', 'Мэргэжил', 'Олгосон /t', 'Хүчинтэй /t', 'Ажиллах төхөөрөмж', 'Утас', 'Имэйл'],
      colModel: [{
          name: 'trainer_id',
          index: 'trainer_id',
          search: false,
          width: 30
        },
        {
          name: 'license_no',
          index: 'license_no',
          width: 60,
          align: "center",
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'location',
          index: 'location',
          width: 80,
          align: "right",
          stype: 'select',
          searchoptions: {
            value: sel_str
          }
        },
        {
          name: 'fullname',
          index: 'fullname',
          width: 90,
          align: "right",
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'license_type',
          index: 'license_type',
          width: 80,
          align: "right",
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'occupation',
          index: 'occupation',
          width: 80,
          align: "right",
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'issued_date',
          index: 'issued_date',
          width: 60,
          align: 'center',
          searchoptions: {
            dataInit: function (el) {
              $(el).datepicker({
                dateFormat: "yy-mm-dd"
              }).change(function () {
                $("#grid")[0].triggerToolbar();
              });
            },
            sopt: ['cn']
          }
        },
        {
          name: 'valid_date',
          index: 'valid_date',
          width: 60,
          align: 'center',
          searchoptions: {
            dataInit: function (el) {
              $(el).datepicker({
                dateFormat: "yy-mm-dd"
              }).change(function () {
                $("#grid")[0].triggerToolbar();
              });
            }
          }
        },
        {
          name: 'license_equipment',
          index: 'license_equipment',
          width: 80,
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'phone',
          index: 'phone',
          width: 80,
          searchoptions: {
            sopt: ['cn']
          }
        },
        {
          name: 'email',
          index: 'email',
          width: 80,
          searchoptions: {
            sopt: ['cn']
          }
        }
      ],
      jsonReader: {
        page: "page",
        total: "total",
        records: "records",
        root: "rows",
        repeatitems: false,
        id: "trainer_id"
      },
      rowNum: 20,
      rowList: [10, 20, 30],
      pager: '#pager',
      sortname: 'trainer_id',
      viewrecords: true,
      sortorder: "asc",
      caption: "ИТА мэдээлэл"
      
    });

    // beforeSearch:function(){$('#section_id').val(''); $('#sector_id').val(''); $('#equipment_id').val(''); $('#date_option').val(''); $('#start_dt').val(''); $('#end_dt').val(''); }}
    jQuery("#outgrid").jqGrid('filterToolbar', {
      stringResult: true,
      searchOnEnter: true
    });



     //edit page 
      var appent_txt;
     
      // Education Button Clicked!        
      $( "#edu_button_add" ).click(function(){        
        var appent_txt="<tr><td><input type='datetime' name='school[]'></td><td><input type='datetime' name='enter_dt[]' class='date_class'></td><td><input type='text' name='grade_dt[]' class='date_class'></td><td><textarea name='detail[]' cols='25'></textarea></td></tr>";
           $("#education").append(appent_txt).find('.date_class').datepicker();      
      });

      //Мөр хасан товч дарагдахад
      $('#edu_button_sub').click(function(){
        var rowCount = $('#education tr').length;
        if(rowCount>2) //Хасах боломжтой
             $('#education tr:last').remove();
          else
             alert("Сүүлчийн мөрийг хасах боломжгүй!");
      });
      // Study Button Clicked!    

      $("#back").click(function(){
         document.location=base_url+"/training/";    
      });

      $('#valid_date').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
      }); 
      
      $('#issued_date').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     }); 

     $('.enter_dt').datepicker({
         dateFormat: 'yy-mm-dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     }); 
     $('.year_dt').datepicker({
         dateFormat: 'yy,mm,dd',      
         changeMonth: true,
         showOtherMonths: true,
         showWeek: true,
         opened:   false
     }); 

     $( "#submit" ).click(function() {  
        var trainer_form = $('#trainer_form'), data = {};             
        var inputs = $('input[type="text"], input[type="hidden"], select, textarea', trainer_form);         
        var data = $( trainer_form ).serialize();
        
        //console.log(data);
        // collect the form data form inputs and select, store in an object 'data'
        $.ajax({
           type:     'POST',
           url:    base_url+'/training/index/update/',
           data:   data,
           dataType: 'json', 
           success:  function(json){ 
              if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
                 // close the dialog                         
                 $('p.feedback', trainer_form).removeClass('error').hide();
                 showMessage(json.message, 'success');
                 // show the success message      
                 document.location=base_url+"/training/";
              }                  
              else{  // ямар нэг юм нэмээгүй тохиолдолд
                $('p.feedback', trainer_form).removeClass('success, notify').addClass('error').html(json.message).show();                        
              }
            }
          });
      });

  $(".btnDelete").on('click', function(event){

      event.stopPropagation();

      event.stopImmediatePropagation();

      var history_id = $( this).attr( "id" );

       $.ajax({
           type:   'POST',
           
           url:    base_url+'/training/index/del_pos_his',
           
           data:   {id:history_id},

           dataType: 'json',

           async: false,

           success:  function(json){
           
             if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
               //энд үндсэн утгуудыг нэмэх болно.
                create.dialog("close");
               // close the dialog
               showMessage(json.message, 'success');
               // show the success message

              setTimeout(function() {                  

              location.reload();                          

              }, 1000);

             }
             else{  // ямар нэг юм нэмээгүй тохиолдолд
               $('p.feedback', create).removeClass('success, notify').addClass('error').html(json.message).show();
             }
           }
       });// send the data via AJAX to our controller
      //(... rest of your JS code)
  });

  // exam history add here

   $('.exam_history_add').click(function(){

    form_exam.dialog({
          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data = $('#exam' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/training/index/add_exam_his',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){

                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд

                        //энд үндсэн утгуудыг нэмэх болно.
                        form_exam.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message

                        setTimeout(function() {                  
                          
                          location.reload();                          

                        }, 1000);

                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', form_exam).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller

            },

            "Хаах": function () {

                form_exam.dialog("close");
            }
        }
      });

      form_exam.dialog( "open" );

   });


   // .Delete 

  $(".deleteExam").on('click', function(event){

      event.stopPropagation();

      event.stopImmediatePropagation();

      var history_id = $( this).attr( "id" );

       $.ajax({
           type:   'POST',
           url:    base_url+'/training/index/del_exam_his',
          
           data:   {id:history_id},

           dataType: 'json',

           async: false,

           success:  function(json){
             if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
               //энд үндсэн утгуудыг нэмэх болно.
                form_exam.dialog("close");
               // close the dialog
               showMessage(json.message, 'success');
               // show the success message

              setTimeout(function() {   //calls click event after a certain time
                
                location.reload();

              }, 1000);
              
               
             }
             else{  // ямар нэг юм нэмээгүй тохиолдолд
               $('p.feedback', create).removeClass('success, notify').addClass('error').html(json.message).show();
             }
           }
       });// send the data via AJAX to our controller
      //(... rest of your JS code)
  });
   // .Delete 

  $(".deleteRemark").on('click', function(event){

      event.stopPropagation();

      event.stopImmediatePropagation();

      var remark_id = $( this).attr( "id" );

       $.ajax({
           type:   'POST',
           url:    base_url+'/training/index/del_remark',
          
           data: {
             id: remark_id
           },

           dataType: 'json',

           async: false,

           success:  function(json){

             if (json.status == "success") {   // амжилттай нэмсэн тохиолдолд

               //энд үндсэн утгуудыг нэмэх болно.

                form_exam.dialog("close");

               // close the dialog
               showMessage(json.message, 'success');

               // show the success message
              setTimeout(function() {   //calls click event after a certain time
                
                location.reload();

              }, 1000);
              
               
             }
             else{  // ямар нэг юм нэмээгүй тохиолдолд
               $('p.feedback', create).removeClass('success, notify').addClass('error').html(json.message).show();
             }
           }
       });// send the data via AJAX to our controller
      //(... rest of your JS code)
  });


  // remark add here

  $('#special_mark').click(function () {

    remark_dialog.dialog({
      buttons: {
        "Хадгалах": function () {
          // var data = {};
          var data = $('#remark').serialize();

          $.ajax({
            type: 'POST',
            url: base_url + '/training/index/add_remark',
            data: data,
            dataType: 'json',
            async: false,
            success: function (json) {

              if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд

                //энд үндсэн утгуудыг нэмэх болно.
                form_exam.dialog("close");
                // close the dialog
                showMessage(json.message, 'success');
                // show the success message

                setTimeout(function () {

                  location.reload();

                }, 1000);

              }
              else {  // ямар нэг юм нэмээгүй тохиолдолд
                $('p.feedback', remark_dialog).removeClass('success, notify').addClass('error').html(json.message).show();
              }
            }
          });// send the data via AJAX to our controller

        },

        "Хаах": function () {

          remark_dialog.dialog("close");
        }
      }
    });

    remark_dialog.dialog("open");

  });



});


// here is check date
function check_date(vdate){   
   var cdate = new Date(current_date());
   var vdate = new Date (vdate);
   
   var diff =vdate-cdate;   // Math.abs
   var diffDays = Math.ceil(diff / (1000 * 3600 * 24));

   return diffDays;
}

function current_date(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();
  
  if(dd<10) {
     dd='0'+dd;
  } 

  if(mm<10) {
      mm='0'+mm;
  }
   return  yyyy+'-'+mm+'-'+dd;
}

function set_type(){   
  var str="";
  str =':Бүгд;Алтай:Алтай;Арвайхээр:Арвайхээр;Баруун-Туруун:Баруун-Туруун;Баруун-Урт:Баруун-Урт;Баянхонгор:Баянхонгор;Булган - Булган:Булган-Булган;Булган - Ховд:Булган-Ховд;Далан:Далан;Даланзадгад:Даланзадгад;Өлгий:Өлгий;Өндөрхаан:Өндөрхаан;Мандалговь:Мандалговь;Мөрөн:Мөрөн;Тосонцэнгэл:Тосонцэнгэл;Улаанбаатар:Улаанбаатар;Улаангом:Улаангом;Улиастай:Улиастай;Ургамал:Ургамал;Хархорин:Хархорин;Ховд:Ховд;Чойбалсан:Чойбалсан';  
  return str;
}


function init_view(id) {
  //alert(id);
  window.location.href=base_url+'/training/index/page/'+id;
}

function init_edit(id) {
  //window.location.href=base_url+'/training/trainer/index/edit/'+id;

  window.open(
      base_url+'/training/trainer/index/edit/'+id,
      '_blank'
    );
}

function _delete(id) {  
   var data = { id: id }; 
   var rowData = $("#grid").getRowData(id);   
    // ask confirmation before delete 
   if(window.confirm("Та '"+rowData.fullname+"' ИТА-ын бүртгэлийг устгахдаа итгэлтэй байна уу?")){
      $.ajax({
         type:    'POST',
         url:    base_url+'/training/index/delete/',
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                  // close the dialog                         
                 showMessage(json.message, 'success');
                // show the success message
                $("#grid").trigger("reloadGrid"); 
           }
         }
       });
    }
}

function _print(id) {  
   
   var data = { id: id }; 

   var rowData = $("#grid").getRowData(id);   

   // window.location.assign(base_url+"/training/index/print_lc?id="+id);
   window.open(
     base_url+"/training/index/print_lc?id="+id,
      '_blank'
   );

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

function add_history(){

   create.dialog({
          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data = $('#create' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/training/index/add_pos_his',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         create.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message
                        location.reload();

                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', create).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller

            },

            "Хаах": function () {
                create.dialog("close");
            }
       }
      });

  create.dialog( "open" );

}


function add_info(){

   info.dialog({
          buttons: {
            "Хадгалах": function () {
              // var data = {};
               var data = $('#info' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/training/index/add_info',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         info.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message
                        location.reload();

                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', info).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller

            },

            "Хаах": function () {
                info.dialog("close");
            }
       }
      });

  info.dialog( "open" );

}


function outservice(e) {

  if (1 != confirm(' Энэ мэдээллийг "Архивлахдаа" итгэлтэй байна уу?')) return 0;

  $.ajax({

    type: "POST",
    url: base_url + "/training/index/outservice/" + e,
    data: {
      id: e
    },
    dataType: "json",
    success: function (e) {
      e.success ? showMessage(e.message, "success") : showMessage(e.message, "error"), reload()
    }
  });
}
