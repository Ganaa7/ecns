
var add, pass, view, material, parameter, repair, edit_parameter, edit_repair, maintenance, edit_maintenance, passbook, maintenance_load_id, repair_load;

var spare, new_spare, add_spare_edit_dialog;

var counter = 0;

$(document).on('click', '#spare_row_delete', function () {

   var spare_id = $(this).attr("data-id");

   $('#spare_'+spare_id).remove();
   
});

$(document).ready(function(){

     
    $( "#tabs" ).tabs();

    $('#no_spare').on("click", function(){

        if($("#btn_spare").is(":disabled"))  $('#btn_spare').removeAttr("disabled");
        
        else $('#btn_spare').attr("disabled", true);

    });

    $('#edit_no_spare').on("click", function(){

        if($("#edit_btn_spare").is(":disabled"))  $('#edit_btn_spare').removeAttr("disabled");
        
        else $('#edit_btn_spare').attr("disabled", true);

    });

    if($('#repairedby_id').length )

       $("#repairedby_id").chosen({no_results_text: "Oops, nothing found!"}); 

    if( $('#edit_repairedby_id').length )

       $("#edit_repairedby_id").chosen({no_results_text: "Oops, nothing found!"}); 

    if($('#doneby_id').length )

       $("#doneby_id").chosen({no_results_text: "Oops, nothing found!"}); 

     if($('#edit_location_id').length){
      
       $('#edit_location_id').chosen({no_results_text: "Утга олдсонгүй!"});

     }


   if ($('#spare_id_add_list').lenght){

      $('#spare_id_add_list').chosen({no_results_text: "Утга олдсонгүй!"});

   }
   
   if ($('#spare_id').lenght){

      $('#spare_id').chosen({no_results_text: "Утга олдсонгүй!"});

   }
   
    $("#no_cert_no").hide();
    
    $("#certificate_id").show();

    $('#year_init').datepicker({        
        
        dateFormat: 'yy',

        // changeMonth: true,

        changeYear: true,

        // showOtherMonths: true,

        showWeek: true,

        opened:   false
        
     });

     $('#year_init', '#edit_pass').datepicker({        
        
        dateFormat: 'yy',

        // changeMonth: true,

        changeYear: true,

        // showOtherMonths: true,

        showWeek: true,

        opened:   false
        
     });

    $('#factory_date').datepicker({        
        
        dateFormat: 'yy-mm-dd',

        changeMonth: true,

        showOtherMonths: true,

        changeYear: true,

        yearRange: '1990:2020',

        showWeek: true,

        opened:   false
        
     }); 

     $('#order_date').datepicker({        
        
        dateFormat: 'yy-mm-dd',

        changeMonth: true,

        changeYear: true,

        yearRange: '1990:2020',

        showOtherMonths: true,

        changeYear: true,

        showWeek: true,

        opened:   false
        
     });


    $('#startdate').datetimepicker({

       dateFormat: 'yy-mm-dd',
       
       changeMonth: true,
       
       showOtherMonths: true,
       
       showWeek: true,
       
       changeYear: true,

       yearRange: '1990:2020'
    });

    $('#startdate_edit').datetimepicker({

       dateFormat: 'yy-mm-dd',
       
       changeMonth: true,
       
       showOtherMonths: true,
       
       showWeek: true,
       
       changeYear: true,

       yearRange: '1990:2020'
   });

   $('#enddate').datetimepicker({
       dateFormat: 'yy-mm-dd',
       
       changeMonth: true,
       
       showOtherMonths: true,
       
       showWeek: true,
       
       changeYear: true,
       
       yearRange: '1990:2020'
   });

    $('#repair_date', '#repair').datepicker({        
        
        dateFormat: 'yy-mm-dd',

        changeYear: true,

        showWeek: true,

        opened:   false,

        changeYear: true,

        yearRange: '1990:2020'
        
    });

    $( "#duration").timepicker({
        hourMin: 0,
        hourMax: 96,
        // timeFormat: "hh:mm ц/м"
    });

    $('#edit_repair_date', '#edit_repair').datepicker({        
        
        dateFormat: 'yy-mm-dd',

        changeYear: true,

        showWeek: true,

        opened:   false,

        yearRange: '1990:2020'
        
    });
    
    $( "#duration", '#edit_repair').timepicker({
        hourMin: 0,
        hourMax: 72,
        // timeFormat: "hh:mm ц/м"
    });


    $('#eventtype_id').change(function() {
      
       $('#event').text($('#eventtype_id option:selected').text());
    
    });

   jqgrid();

   maintenance_load_id = $('#maintenance_load_id');

   maintenance_load_id.dialog({

      autoOpen: false,
      
      width: 800,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea', $(this)).val('');          

         $(this).dialog("close");
       }

   });


   passbook = $('#passbook');

   passbook.dialog({

      autoOpen: false,
      
      width: 600,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $("#node_id option:selected").removeAttr("selected"); 

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea', $(this)).val('');          

         $(this).dialog("close");

         $("#node_id").multiselect('destroy');

       }

   }); 

   material=$('#material');
   
   material.dialog({

      autoOpen: false,
      
      width: 600,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea', $(this)).val('');          

         $(this).dialog("close");
       }

   });   

   maintenance=$('#maintenance');
   
   maintenance.dialog({

      autoOpen: false,
      
      width: 600,
      
      resizable: false,
      
      modal: true,

      buttons: {
      
        "Хаах": function () {

              $('#doneby_id').val('').trigger('chosen:updated');

              maintenance.dialog("close");

          }
      },

       close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"], select, textarea', $(this)).val('');          

         $(this).dialog("close");
       }
      
   });  

   edit_maintenance = $('#edit_maintenance');

   edit_maintenance.dialog({

      autoOpen: false,
      
      width: 600,

      resizable: false,
      
      modal: true,

      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');    
         
         $('#passbook_all_edit').attr('checked', false);

         // $("#doneby_ita_id").multiselect('destroy');

         // $('#doneby_ita_id > option').removeAttr("selected");

         // $("#doneby_ita_id option:selected").removeAttr("selected");
         // $("#ddlMultiselect").multiSelect( 'refresh' );

         $(this).dialog("close");

       }
   });  


   parameter=$('#parameter');
   
   parameter.dialog({

      autoOpen: false,
      
      width: 600,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');          

         $(this).dialog("close");
       }

   });   

   repair=$('#repair');
   
   repair.dialog({

      autoOpen: false,
      
      width: 800,

      height: 500,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');          

         $('#file_link', $(this)).remove();

         $('.remove_tr', '#repair').remove();

         $(this).dialog("close");
       }

   });  

   pass=$('#pass');
   
   pass.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');          

          $('#file_link', $(this)).remove();

          $(this).dialog("close");

       }
   });

   view=$('#view');

   view.dialog({
     autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');          

          $('#file_link', $(this)).remove();

          $(this).dialog("close");

       }
   });

   edit_parameter = $('#edit_parameter');

   edit_parameter.dialog({

      autoOpen: false,
      
      width: 600,

      resizable: false,
      
      modal: true,

      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea, input[type="file"]', $(this)).val('');          

         // $('#doneby_ita_id').

         $(this).dialog("close");

       }
   }); 

   edit_material = $('#edit_material');

   edit_material.dialog({

      autoOpen: false,
      
      width: 600,

      resizable: false,
      
      modal: true,

      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"], select, textarea, input[type="file"]', $(this)).val('');          

         $(this).dialog("close");

       }
   });  

   edit_repair = $('#edit_repair');

   edit_repair.dialog({

      autoOpen: false,
      
      width: 800,

      resizable: false,
      
      modal: true,

      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea, input[type="file"]', $(this)).val('');          

         
         $('#edit_no_spare').prop('checked', false);
         
         $('.remove_tr').empty();
        
         $(this).dialog("close");

       }
   });

   // Spare dialog here

   spare = $('#spare');

   spare.dialog({

      autoOpen: false,
      
      width: 740,
      
      resizable: false,
      
      modal: true,

      buttons: {

          "Нэмэх": function(){

              var valid = true;

              var spare_id  = $('#spare_id option:selected', spare).val();
                                             
              var spare  = $('#spare_id option:selected', spare).text();

              var qty  = $('#qty_spare').val();

              var part_number  = $('#part_number_spare').val();

              counter++;

              // console.log('sid'+spare_id+'spare'+spare+'qty'+qty+'part_number'+part_number);

              if(spare_id && $.isNumeric(qty) && part_number){

                 var string = "<tr class='remove_tr' id='spare_"+spare_id+"'><td>"+counter+"</td><td><input type='hidden' name='spare_id[]' value='"+spare_id+"' />"+spare;

                 string = string+"</td><td><input type='hidden' name='qty[]' value='"+qty+"' />"+qty;

                 string = string+"</td><td><input type='hidden' name='part_number[]' value='"+part_number+"' />"+part_number+"</td>";

                 string = string+"<td><a href='#' onclick='spare_row_delete("+counter+")'>Устгах</a></td>";

                 $('#spare_table').append(string+"</tr>");

                 $('p.feedback', repair).addClass('success').html('Материалын жагсаалтад амжилттай нэмэгдлээ').show();

                 $(this).dialog("close");

                 $('#spare')[0].reset();

              }else{

                alert('Сэлбэгийн утгуудыг [Сэлбэг, Тоо/ш, үйлдвэрийн дугаар] бүгдийг сонгосон байх ёстой! Мөн тоо/ш зөвхөн тоо байх ёстойг анхаарна уу!');


              }
              return valid;

          },

          "Болих": function () {

              $('#spare')[0].reset();

              $('p.feedback', $(this)).html('').hide();

              $('input[type="text"],  select, textarea', $(this)).val('');      

              $(this).dialog("close");
        }
      }
    
   }); 

   add_spare_edit_dialog = $('#spare_edit_list');

   add_spare_edit_dialog.dialog({

      autoOpen: false,

      width: 740,

      resizable: false,

      modal: true,

      buttons: {

         "Нэмэх": function () {

            var valid = true;

            var spare_id = $('#spare_id_add_list option:selected', add_spare_edit).val();

            var spare = $('#spare_id_add_list option:selected', add_spare_edit).text();

            var qty = $('#qty_spare_add_list').val();

            var part_number = $('#part_number_spare_add_list').val();

            counter++;

            // console.log('sid'+spare_id+'spare'+spare+'qty'+qty+'part_number'+part_number);

            if (spare_id && $.isNumeric(qty) && part_number) {

               var string = "<tr class='remove_tr' id='spare_" + spare_id + "'><td>" + counter + "</td><td><input type='hidden' name='spare_id[]' value='" + spare_id + "' />" + spare;

               string = string + "</td><td><input type='hidden' name='qty[]' value='" + qty + "' />" + qty;

               string = string + "</td><td><input type='hidden' name='part_number[]' value='" + part_number + "' />" + part_number + "</td>";

               string = string + "<td><a href='#' id='spare_row_delete' data-id='"+spare_id+"'>Устгах</a></td>";

               $('#edit_spare_table').append(string + "</tr>");

               $('p.feedback', repair).addClass('success').html('Материалын жагсаалтад амжилттай нэмэгдлээ').show();

               $(this).dialog("close");

               $('#spare')[0].reset();

            } else {

               alert('Сэлбэгийн утгуудыг [Сэлбэг, Тоо/ш, үйлдвэрийн дугаар] бүгдийг сонгосон байх ёстой! Мөн тоо/ш зөвхөн тоо байх ёстойг анхаарна уу!');

            }

            return valid;

         },

         "Болих": function () {

            $('#spare')[0].reset();

            $('p.feedback', $(this)).html('').hide();

            $('input[type="text"],  select, textarea', $(this)).val('');

            $(this).dialog("close");
         }
      }

   }); 
   

   // Repair

   repair_load = $('#repair_load');

   repair_load.dialog({

      autoOpen: false,
      
      width: 800,
      
      resizable: false,
      
      modal: true,
      
      close: function () {

         $('p.feedback', $(this)).html('').hide();

         $('input[type="text"],  select, textarea', $(this)).val('');          

         $(this).dialog("close");
       }

   });

   new_spare = $("#new-form");

   new_spare.dialog({

        autoOpen: false,

        width: 570,

        resizable: false,

        modal: true,

        close: function() {

            $("p.feedback", $(this)).html("").hide();

            $('input[type="text"], select, textarea', new_spare).val("");

            $(this).dialog("close");
        },

        buttons: {

          "Хадгалах": function(){

              var data = $('#new-form' ).serialize();

              var spare = $('#new_spare', new_spare).val();

              $.ajax({
                  type:   'POST',
                  url: base_url + "/wh_settings/spare/add/",
                  data:   data,
                  dataType: 'json',

                  async: false,

                  success:  function(json){

                    if (json.status == "success") {      

                      $('#spare_id').append($('<option>', {value:json.spare_id, text:spare}));

                      alert('Сэлбэгийн жагсаалтад амжжилттай нэмэгдлээ');

                      new_spare.dialog("close");

                      
                    }else{ 

                      $('p.feedback', new_spare).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });
             
          },

          "Болих": function () {

              // $('#spare')[0].reset();

              $('p.feedback', $(this)).html('').hide();

              $('input[type="text"],  select, textarea', $(this)).val('');      

              $(this).dialog("close");
          }

        }

    });


   // filter here
  $("#section_id", '#create_pass').change(function(){

    // filter('#create_pass', $(this).val(), 'equipment');

    // enable location, equipment
     $( "#location_id" ).prop( "disabled", false );

     $( "#equipment_id" ).prop( "disabled", false );

    // тухай утгийг авч 

     $('#label_section').text($("#section_id option:selected").text()+"-н хэсэг");

  });


  // edit filter here

  $("#section_id", '#edit_pass').change(function(){

    filter('#edit_pass', $(this).val(), 'equipment');

    // тухай утгийг авч 

    $('#label_section').text($("#section_id option:selected").text()+"-н хэсэг");

  });

  // filter here
  $("#location_id", '#create_pass').change(function(){

    filter('#create_pass', $(this).val(), 'equipment');

  });


  $("#equipment_id", "#create_pass, #edit_pass").change(function(){

     // certificate_no equipment_id, location_id -r avah
     var data = {location_id: $('#location_id option:selected').val(), equipment_id:$(this).val()};

     $.post(base_url+'/equipment/index/get_cert', data , function(json) {   
 
        // avsan utgaa end hevlene
        $("#certificate", "#create_pass, #edit_pass").val(json.certificate.cert_no);

        console.log("certificate"+JSON.stringify(json.certificate));
        
        console.log("count"+json.certificate.length);

        if(json.certificate.length){

          console.log('certificate_wrap'+json.certificate);

          $("#no_cert_no").hide();

          $('#certificate_id').find('option').remove();

          // $('#certificate_wrap').append("<select id='certificate'></select>");

          var select = $('#certificate_id');

           if(select.prop) {

              var options = select.prop('options');

           }else {

              var options = select.attr('options');
              
           }
           // $('option', select).remove();
           $.each(json.certificate, function(key, val) {

              // console.log('inseder'+val.id);
                options[options.length] = new Option(val.cert_no, val.id);        
           });

          $("#certificate_id").show();

        }

        else {

           $('#certificate_id').empty();

           $("#certificate", "#create_pass, #edit_pass").val('Гэрчилгээ байхгүй байна!');

           $("#certificate", "#create_pass, #edit_pass").prop('disabled', false);

           $("#no_cert_no").show(); 

           // $("#certificate_id").hide();
           $('option[value="0"]', "#certificate_id").remove();
       
        }

        $("#serial_number", '#create_pass, #edit_pass').val(json.certificate.serial_no_year);
        
        $("input[name='device']", '#create_pass, #edit_pass').val(json.equipment.equipment);

        $('#intend', '#create_pass, #edit_pass').val(json.equipment.intend);

     });

  });


  $("#cert_null", "#create_pass, #edit_pass").on("click", function(){

        var exists = 0 != $("#certificate_id option[value='0']").length;

        if(exists ==0)
           $('#certificate_id').append('<option value="0">Гэрчилгээгүй</option>');

        // $("input[name='certificate_id']", "#create_pass, #edit_pass").val(0);

        $("#certificate", "#create_pass, #edit_pass").val('0');

  });


  // form create btn clicked
  $( "#save", "#create_pass" ).on( "click", function() {

     var data = $('#create_pass' ).serialize();

     $.ajax({
          type:   'POST',
          url:    base_url+'/equipment/index/add',
          data:   data,
          dataType: 'json',
          async: false,
          success:  function(json){

            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд

              //энд үндсэн утгуудыг нэмэх болно.
              
              showMessage(json.message, 'success');

              setTimeout(function(){

                  window.location.assign(base_url+"/equipment");

               },3000); 

            }else{  // ямар нэг юм нэмээгүй тохиолдолд

              $('p.feedback', '#create_pass').removeClass('success, notify').addClass('error').html(json.message).show();
              
            }
          }
      });

  }); 

   // form edit
  $( "#update", "#edit_pass" ).on( "click", function() {

     var data = $('#edit_pass' ).serialize();

     $.ajax({
          type:   'POST',
          
          url:    base_url+'/equipment/index/update_passport',
          
          data:   data,

          dataType: 'json',

          async: false,

          success:  function(json){

            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд

               
               showMessage(json.message, 'success');

               setTimeout(function(){

                  window.location.assign(base_url+"/equipment");

               },2000); 


            }else{  // ямар нэг юм нэмээгүй тохиолдолд

              $("#container").animate({   scrollTop: 0  }, 300);                 

              $('p.feedback', '#edit_pass').removeClass('success, notify').addClass('error').html(json.message).show();
              
            }
          }
      });

  });


});


function jqgrid(){

    $("#grid").jqGrid({
        
        url:base_url+'/equipment/index/grid',

        datatype: 'json',

        mtype: 'GET',

        colNames:['#','Хэсэг', 'Пассорт №', 'Төхөөрөмж', 'Гэрчилгээ №', 'Байршил', 'Марк, төрөл', 'Парт №', 'Ашиглалтад орсон он'],

        colModel :[
          {name:'id', index:'device.id', width:15, align:'center' },
          {name:'section', index:'section.name', width:60 , align:'center', formatter:view_link, stype:'select',searchoptions:{value:set_section()} },          
          {name:'passport_no', index:'passport_no', width:60, align:'center' },
          {name:'device', index:'device', width:120 , formatter:view_link },
          {name:'cert_no', index:'cert_no', width:60, align:'center' },
          {name:'location', index:'location.location', width:50, align:'center'},       
          {name:'mark', index:'mark', width:60, align:'center' ,formatter:view_link  },          
          {name:'part_number', index:'part_number', width:60, align:'center' ,formatter:view_link  },          
          {name:'year_init', index:'year_init', width:60, align:'center' }
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
        sortname: 'device.id',
        sortorder: "asc",
        viewrecords: true,
        gridview: true,

        caption: 'Тоног төхөөрөмжийн пасспорт',

        autowidth:true,

        height:500,

        width:'100%' ,
        
        editurl: 'server.php', 

        subGrid: false,
        
        loadComplete: function (){
           var rowIds = $(this).jqGrid('getDataIDs');
           for (var i=0;i<rowIds.length;i++){
              var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
              var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
             trElement.addClass('context-menu');
           }
        },
      
    });

     jQuery("#grid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

}

function set_section(){

  var str="", cnt=0;

   $("#section_id option").each(function(){

      str =$(this).text()+":"+$(this).text(); cnt++;
  
   });
 
    str = ':Бүгд;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт:Ажиглалт;Гэрэл суулт, цахилгаан:ГСЦ;ЧОУНБХ (NUBIA):ЧОУНБХ (NUBIA)';
   return str;
}


function pass_modal(id, title) {

  var title ;

  $('input[name=equipment_id]', pass).val(id);

   //collect all by id all data
    $.ajax({
        type:    'POST',

        url:    base_url+'/equipment/index/get/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           $("input[name=device_id]", pass).val(json.id);

           $("#location_id option[value="+json.location.location_id+"]", pass).attr("selected", "selected");

           $("#section_id option[value="+json.section_id+"]", pass).attr("selected", "selected");

           $("#equipment_id option[value="+json.equipment.equipment_id+"]", pass).attr("selected", "selected");

           if(json.passport_no){

              var r = confirm("Пасспортын дугаар аль хэдийн олгосон байна! Шинээр олгох уу?");

              // alert('passport already added'+json.passport_no);
              
              if (r == true) {
              
                 $("#passport_no", pass).val(json.pass_no);
              
              } else {
              
                $("#passport_no", pass).val(json.passport_no);
              
              }

           }else{

              $("#passport_no", pass).val(json.pass_no);

           }     
          
           
       }
     }).done(function() {
        pass.dialog({
           title: "Төхөөрөмжийн пасспортын дугаар олгох : ",
           buttons: {
              "Хадгалах": function () {
               //var data = {};
               var data = $('#pass' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/equipment/index/set_pass',
                    data:   data,
                    dataType: 'json',
                    async: false,
                    success:  function(json){
                      if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        //энд үндсэн утгуудыг нэмэх болно.
                         pass.dialog("close");
                        // close the dialog
                        showMessage(json.message, 'success');
                        // show the success message
                        jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                      }
                      else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', pass).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });// send the data via AJAX to our controller

            },
            "Хаах": function () {
                pass.dialog("close");
            }
           }
        });
        pass.dialog( "open" );

     });
}

//add modal here
function view_modal(id){
    //$('#spare', add).val(spare);
    // $('input[name=id]', add).val(id);
    var title;

     $.ajax({
          type:    'POST',

          url:    base_url+'/equipment/index/get/',

          data:   { id: id},
          
          dataType: 'json',

          success:  function(json) {
            
             $("#id", view).val(json.id);

             $("#section_id option[value="+json.section_id+"]", view).attr("selected", "selected");

             $("#sector_id option[value="+json.sector_id+"]", view).attr("selected", "selected");

             $("#equipment", view).val(json.equipment);

             $("#code", view).val(json.code);

             $("#intend", view).val(json.intend);

             $("#spec", view).val(json.spec);
             
             $("#year_init", view).val(json.year_init);

             $("input[name=sp_id]", view).val(json.sp_id);

             title = json.equipment;
         }
       }).done(function() {

           view.dialog({

              title: "Төхөөрөмж: ["+title+"]",

              buttons: {

                "Хаах": function () {
                    view.dialog("close");
                }
              }
          });

          view.dialog( "open" );                  
       });      
       
}

function filter(form_name, target_id, target){

    var data ={};

    if(target == 'equipment'){

       data = {id:target_id, target:target, section:$('#section_id').val()};

    }else{

       data = {id:target_id, target:target};

    }

    // console.log(data); 
    $.post(base_url+'/equipment/index/filterby', data , function(newOption) {   

        //neelttei haalttai,        
        var select = $('#'+target+'_id', form_name);

        if(select.prop) {

           var options = select.prop('options');

        }else {

           var options = select.attr('options');

        }

        $('option', select).remove();

        $.each(newOption, function(text, val) {

           options[options.length] = new Option(val, text);        

        });

    });

   
}
// TODO: #material_function

function maintenance_modal(title, passbook_id) {

   var title ;

   $.ajax({
      
      type: 'POST',

      url: base_url + '/equipment/index/get_passbook/',

      data: { id: passbook_id },

      dataType: 'json',

      success: function (json) {
         
         var keys =  Object.keys(json.detail);

         if( keys > 1 ){

            $('#add_passbook_all').hide();
         }

      }

   }).done(function () {

      maintenance.dialog({
         
         title: "Техник үйлчилгээ, үзлэгийн бүртгэл:  "+title,

         buttons: {

            "Хадгалах": function () {
               
               //var data = {};
               var data = $('#maintenance' ).serialize();

               $.ajax({
                     type:   'POST',
                     url:    base_url+'/equipment/index/add_maintenance',
                     data:   data +'&passbook_id='+passbook_id,
                     dataType: 'json',

                     async: false,

                     success:  function(json){

                     if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                        
                        //энд үндсэн утгуудыг нэмэх болно.
                        maintenance.dialog("close");
                        
                        // close the dialog
                        showMessage(json.message, 'success');
                        
                        // show the success message                    
                        setTimeout(function() { location.reload();  }, 1000);
                     
                     }else{  // ямар нэг юм нэмээгүй тохиолдолд
                        $('p.feedback', maintenance).removeClass('success, notify').addClass('error').html(json.message).show();
                     }
                     }
               });
            },

            "Хаах": function () {

               maintenance.dialog("close");

            }

         }

      });

      maintenance.dialog( "open" );

   });

}

function view_link(cellValue, options, rowObject){

   return "<a  href='"+base_url+"/equipment/view/"+rowObject.id+"'>"+cellValue+"</a>"; 
   // return "<a target='_blank' onclick='view_modal("+rowObject.equipment_id+")'>"+cellValue+"</a>"; 

}

function material_modal(title, passbook_id) {

    var title ;

    // $('input[name=device_id]', material).val(id);

    material.dialog({
       
       title: "Шинэ:  "+title,

       buttons: {

          "Хадгалах": function () {
           //var data = {};
           var data = $('#material' ).serialize();

            $.ajax({
                type:   'POST',
                url:    base_url+'/equipment/index/add_material',
                data:   data+'&passbook_id='+passbook_id,
                dataType: 'json',

                async: false,

                success:  function(json){

                  if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                     
                     //энд үндсэн утгуудыг нэмэх болно.
                     material.dialog("close");
                     
                     // close the dialog
                     showMessage(json.message, 'success');
                     
                     // show the success message                    
                     setTimeout(function() { location.reload();  }, 10000);
                  
                  }else{  // ямар нэг юм нэмээгүй тохиолдолд
                    $('p.feedback', material).removeClass('success, notify').addClass('error').html(json.message).show();
                  }
                }
            });
        },

        "Хаах": function () {

            material.dialog("close");
        }

       }

    });

    material.dialog( "open" );

}

function edit_material_(id) {

    var title ;

     $('input[name=id]', edit_material).val(id);

      $.ajax({
        type:    'POST',

        url:    base_url+'/equipment/index/get_material/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           $("#device", edit_material).val(json.device.device);
        
           $("#materials", edit_material).val(json.materials);

           $("#qty", edit_material).val(json.qty);

           $("#part_number", edit_material).val(json.part_number);

       }

     }).done(function() {

        edit_material.dialog({
           
           title: 'Эд болгогч зүйлсийг засварлах',

           buttons: {

              "Хадгалах": function () {
               
               var data = $('#edit_material' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/equipment/index/edit_material',
                    data:   data,
                    dataType: 'json',

                    async: false,

                    success:  function(json){

                      if (json.status == "success") {      

                        edit_material.dialog("close");

                        showMessage(json.message, 'success');

                        setTimeout(function() { location.reload();  }, 1000);


                      }else{ 

                        $('p.feedback', edit_material).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });
            },

            "Хаах": function () {
                edit_material.dialog("close");
            }

           }
        });

       edit_material.dialog( "open" );

     });
}

function delete_material(id){

  confirm = window.confirm("Энэ иж болгогч зүйлсийг устгахдаа итгэлтэй байна уу?");

  if(confirm){

      $.ajax({
          
          type:    'POST',

          url:    base_url+'/equipment/index/del_material/',

          data:   { id: id},
          
          dataType: 'json',

          success:  function(json) {

            showMessage(json.message, 'success');

            setTimeout(function() { location.reload();  }, 1000);
          }

      });
      
  }

}

function delete_parameter(id){

  confirm = window.confirm("Энэ үзүүлэлтийн устгахдаа итгэлтэй байна уу?");

  if(confirm){

      $.ajax({
          
          type:    'POST',

          url:    base_url+'/equipment/index/del_parameter/',

          data:   { id: id},
          
          dataType: 'json',

          success:  function(json) {

            showMessage(json.message, 'success');

            setTimeout(function() { location.reload();  }, 1000);
          }

      });

  }

}

function parameter_modal(title, passbook_id) {

    var title ;

    // $('input[name=device_id]', parameter).val(id);

    parameter.dialog({
       
       title: title,

       buttons: {
          "Хадгалах": function () {
           //var data = {};
           var data = $('#parameter' ).serialize();

            $.ajax({
                type:   'POST',
                url:    base_url+'/equipment/index/add_parameter',
                
                data:   data +'&passbook_id='+passbook_id,
                
                dataType: 'json',

                async: false,

                success:  function(json){

                  if (json.status == "success") {      

                    parameter.dialog("close");

                    showMessage(json.message, 'success');

                    setTimeout(function() { location.reload();  }, 1000);

                  }else{ 

                    $('p.feedback', parameter).removeClass('success, notify').addClass('error').html(json.message).show();
                  }
                }
            });
        },

        "Хаах": function () {
            parameter.dialog("close");
        }

       }

    });

    parameter.dialog( "open" );

}

// Edit parameter

function edit_parameter_(id) {

    var title ;

    $('input[name=id]', edit_parameter).val(id);

     $.ajax({

        type:    'POST',

        url:    base_url+'/equipment/index/get_parameter/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           $("#device", edit_parameter).val(json.device.device);
        
           $("#parameters", edit_parameter).val(json.parameters);

           $("#measure", edit_parameter).val(json.measure);

           $("#value", edit_parameter).val(json.value);

       }

     }).done(function() {

        edit_parameter.dialog({
           
           title: 'Үзүүлэлтийг  засварлах',

           buttons: {

              "Хадгалах": function () {
               

               var data = $('#edit_parameter' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/equipment/index/edit_parameter',
                    data:   data,
                    dataType: 'json',

                    async: false,

                    success:  function(json){

                      if (json.status == "success") {      

                        edit_parameter.dialog("close");

                        showMessage(json.message, 'success');

                        setTimeout(function() { location.reload();  }, 1000);

                      }else{ 

                        $('p.feedback', edit_parameter).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });
            },

            "Хаах": function () {
                edit_parameter.dialog("close");
            }

           }
        });

       edit_parameter.dialog( "open" );

     });
}


function repair_modal(title, passbook_id, device_id) {

   // $('#spare_id_chosen').css("width", "250px");

   // $('#spare_id').chosen({ no_results_text: "Утга олдсонгүй!" });

    var title ;

    $('#device_id', repair).val(device_id);

    repair.dialog({
       
       title: title,

       buttons: {

          "Хадгалах": function () {

           var data = $('#repair' ).serialize();

            $.ajax({

                type:   'POST',
                url:    base_url+'/equipment/index/add_repair',
                data:   data +'&passbook_id='+passbook_id,
                dataType: 'json',

                async: false,

                success:  function(json){

                  if (json.status == "success") {      

                    repair.dialog("close");

                    showMessage(json.message, 'success');

                     setTimeout(function() { location.reload();  }, 1000);

                  }else{ 
                    
                    $('p.feedback', repair).removeClass('success, notify').addClass('error').html(json.message).show();
                  }
                }
            });
        },

        "Хаах": function () {

            // $("#repairedby_id").chosen("destroy");
            $('#repairedby_id').val('').trigger('chosen:updated');

            repair.dialog("close");
        }

       }

    });

    repair.dialog( "open" );

}

function edit_repair_(id, passbook_id){

    var title ;
    
    var itas;

     $('#repair_id', edit_repair).val(id);

      $.ajax({
        type:    'POST',

        url:    base_url+'/equipment/index/get_repair/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           $("#edit_repair_date", edit_repair).val(json.repair_date);

           $('#spare_id option[value="'+json.spare_id+'"]', edit_repair).attr('selected', true);
        
           $("#reason", edit_repair).val(json.reason);

           $("#repair", edit_repair).val(json.repair);

           $("#qty", edit_repair).val(json.qty);

           $("#part_number", edit_repair).val(json.part_number);
           
           $("#duration", edit_repair).val(json.new_duration);
           
           $('#edit_repairedby_id option[value="'+json.repairedby_id+'"]', edit_repair).attr('selected', true);

           itas = json.itas;

           if (json.isdone) {

              $.each(itas, function (key, value) {

                 $("#edit_repairedby_id option[value=" + key + "]", edit_repair).attr("selected", "selected");

              });
              
           }

           console.log("test"+json.no_spare);

           if(json.no_spare!=="0"||json.no_spare ===null){

               $('#edit_no_spare').prop('checked', true);

               $('#edit_btn_spare').attr("disabled", true);

           }else{

               

               $.each(json.spare, function(idx, data){

                  $('#edit_spare_table').append("<tr class='remove_tr' id='spare_" + data.id + "'><td>" + data.id + "</td><td><input type='hidden' name='spare_id[]' value='" + data.spare_id + "'> " + data.spare + "</td><td><input type='hidden' name='qty[]' value='" + data.qty + "'> " + data.qty + "</td><td><input type='hidden' name='part_number[]' value='" + data.part_number +"'>" + data.part_number + "</td><td>" + "<a href='#' data-id='"+data.id+"' id='spare_row_delete'>Устгах</a></td></tr>");

               });
              
           }
           
           $('#edit_repairedby_id').trigger('chosen:updated');

        }

     }).done(function() {

        edit_repair.dialog({
           
           title: 'Эд болгогч зүйлсийг засварлах',

           buttons: {

              "Хадгалах": function () {
               
               var data = $('#edit_repair' ).serialize();

                $.ajax({
                    type:   'POST',
                    url:    base_url+'/equipment/index/edit_repair',
                    data:   data+'&passbook_id='+passbook_id,
                    dataType: 'json',

                    async: false,

                    success:  function(json){

                      if (json.status == "success") {      

                        edit_repair.dialog("close");

                        showMessage(json.message, 'success');

                        setTimeout(function() { location.reload();  }, 1000);

                      }else{ 

                        $('p.feedback', edit_repair).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }

                });

            },

            "Хаах": function () {
               
                // $('#edit_repairedby_id').trigger('chosen:updated');
               $('.remove_tr').remove();

                edit_repair.dialog("close");
            }

           }
        });

       edit_repair.dialog( "open" );

     });

}

function delete_repair(id){

  confirm = window.confirm("Энэ засварыг устгахдаа итгэлтэй байна уу?");

  if(confirm){

      $.ajax({
          
          type:    'POST',

          url:    base_url+'/equipment/index/del_repair/',

          data:   { id: id},
          
          dataType: 'json',

          success:  function(json) {

            showMessage(json.message, 'success');

            setTimeout(function() { location.reload();  }, 1000);
          }

      });

  }

}

//edit maintenance
function _edit_maintenance(id, passbook_id) {

    var title ;

    var itas;

   $.ajax({

      type: 'POST',

      url: base_url + '/equipment/index/get_passbook/',

      data: { id: passbook_id },

      dataType: 'json',

      success: function (json) {

         var keys = Object.keys(json.detail);

         if (keys > 1) {

            $('#edit_passbook_all').hide();
         }

      }

   });

     $('input[name=id]', edit_maintenance).val(id);

      $.ajax({

        type:    'POST',

        url:    base_url+'/maintenance/get_event_dtl/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           itas = json.itas;
           
           if(json.passbook_id ===null){

              $('#passbook_all_edit').attr('checked', true);

           }
           
           $("#equipment_id", edit_maintenance).val(json.equipment_id);
           
           $("#location_id", edit_maintenance).val(json.location_id);
           
           $("#eventtype_id", edit_maintenance).val(json.eventtype_id);
        
           $("#event", edit_maintenance).val(json.title);

           $("#is_interrupt", edit_maintenance).val(json.is_interrupt);

           $("#startdate_edit", edit_maintenance).val(json.start.substring(json.start.lenght,16));
           
           $("#enddate", edit_maintenance).val(json.end.substring(json.end.lenght,16));
           
           $("#done", edit_maintenance).val(json.done);

           $("#doneby_id", edit_maintenance).val(json.doneby_id);

           $("#createdby", edit_maintenance).val(json.createdby);

           if(json.isdone){

             $.each(itas, function( key, value ) {  

                $("#doneby_ita_id option[value="+key+"]", edit_maintenance).attr("selected", "selected");
             });
           }

           $("#doneby_ita_id").chosen({no_results_text: "Oops, nothing found!"}); 

           $('.chosen-container ').css("width","250px");

           $('#doneby_ita_id').trigger('chosen:updated');

       }

     }).done(function() {

        edit_maintenance.dialog({
           
           title: 'Эд болгогч зүйлсийг засварлах',

           buttons: {

              "Хадгалах": function () {
               
               var data = $('#edit_maintenance' ).serialize();

                $.ajax({
                    type:   'POST',
                    url: base_url + '/equipment/index/edit_maintenance',
                    data: data + '&passbook_id=' + passbook_id,
                    dataType: 'json',

                    async: false,

                    success:  function(json){

                      if (json.status == "success") {      

                        edit_maintenance.dialog("close");

                        showMessage(json.message, 'success');

                        setTimeout(function() { location.reload();  }, 5000);


                      }else{ 

                        $('p.feedback', edit_maintenance).removeClass('success, notify').addClass('error').html(json.message).show();
                      }
                    }
                });
            },

            "Хаах": function () {
                edit_maintenance.dialog("close");
            }

           }
        });

       edit_maintenance.dialog( "open" );

     });
}


function delete_maintenance(id){

  confirm = window.confirm("Энэ техник үйлчилгээг устгахадаа итгэлтэй байна уу? Энэ мэдээлэлийг устгавал Техник үйлчилгээ модулиас давхар устгагдах болно?");

  if(confirm){

      $.ajax({
          
          type:    'POST',

          url:    base_url+'/equipment/index/del_maintenance/',

          data:   { id: id},
          
          dataType: 'json',

          success:  function(json) {

            showMessage(json.message, 'success');

            setTimeout(function() { location.reload();  }, 1000);
          }

      });

  }

}

function add_passbook(device_id, equipment_id){

    // current device_id 
    // get equipment_id

    $('#passbook')[0].reset(); 

    var title;

    passbook.dialog({
       
       title: "Техникийн пасспортын дэд хэсэг:",

       buttons: {

          "Хадгалах": function () {
            
             //var data = {};
             var data = $('#passbook' ).serialize();

              $.ajax({
                  type:   'POST',
                  url:    base_url+'/equipment/index/add_passbook',
                  data:   data,
                  dataType: 'json',

                  async: false,

                  success:  function(json){

                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                       
                       //энд үндсэн утгуудыг нэмэх болно.
                       passbook.dialog("close");
                       
                       // close the dialog
                       showMessage(json.message, 'success');
                       
                       // show the success message                    
                       setTimeout(function() { location.reload();  }, 1000);
                    
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', passbook).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });
          },

          "Хаах": function () {

              passbook.dialog("close");

          }

       }

    });

    $("#node_id").multiselect();

    passbook.dialog( "open" );

}

function edit_passbook(id){

    $('#passbook')[0].reset(); 

    var detail = [];

     $.ajax({
        type:    'POST',

        url:    base_url+'/equipment/index/get_passbook/',

        data:   { id: id},
        
        dataType: 'json',

        success:  function(json) {

           detail = json.detail;

           $("#passbook_no", passbook).val(json.passbook_no);

           $.each(detail, function( key, value ) {  

              $("#node_id option[value="+key+"]", passbook).attr("selected", "selected");
           
           });

          $("#node_id").multiselect();
           
        }

     }).done(function() {

     passbook.dialog({
       
       title: "Техникийн пасспортын дэд хэсэг:",

       buttons: {

          "Хадгалах": function () {
            
             //var data = {};
             var data = $('#passbook' ).serialize();

              $.ajax({
                  type:   'POST',
                  url:    base_url+'/equipment/index/edit_passbook/',
                  data:   data +'&passbook_id='+id,
                  dataType: 'json',

                  async: false,

                  success:  function(json){

                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                       
                       //энд үндсэн утгуудыг нэмэх болно.
                       passbook.dialog("close");
                       
                       // close the dialog
                       showMessage(json.message, 'success');
                       
                       // show the success message                    
                       setTimeout(function() { location.reload();  }, 2000);
                    
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', passbook).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });
          },

          "Хаах": function () {

              
              // $('#node_id').multiselect('deselectAll', false);

              passbook.dialog("close");


          }

       }

     });

     passbook.dialog( "open" );

     });

}

function delete_passbook(id){

   var r = confirm("Энэ пасспортын хэсгийн мэдээллийг устгах уу? Хэрэв устгагдвал тухайн төхөөрөмжийн техник үйлчилгээ харагдахгүй болохыг анхаарна уу!");

   if(r){

      $.ajax({
        type:   'POST',
        url:    base_url+'/equipment/index/delete_passbook',
        
        data:   {id:id},
        
        dataType: 'json',

        async: false,

        success:  function(json){

            if(json.status=='success'){

               showMessage(json.message, 'success');
               
               setTimeout(function() { location.reload();  }, 5000);
              
            }else

               showMessage(json.message, 'error');
        }

    });



   }

  
}

function maintenance_load (equipment_id, location_id, passbook_id){

    maintenance_load_id.dialog({
       
       title: "Техник үйлчилгээний дэвтэр:",

       buttons: {

          "Хадгалах": function () {
            
             //var data = {};
             var data = $('#maintenance_load_id' ).serialize();

              $.ajax({
                  type:   'POST',
                  url:    base_url+'/equipment/index/add_passbook_events',
                  
                  data:   data+'&passbook_id='+passbook_id,

                  dataType: 'json',

                  async: false,

                  success:  function(json){

                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                       
                       //энд үндсэн утгуудыг нэмэх болно.
                       maintenance_load_id.dialog("close");
                       
                       // close the dialog
                       showMessage(json.message, 'success');
                       
                       // show the success message                    
                       setTimeout(function() { location.reload();  }, 1000);
                    
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', passbook).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });
          },

          "Хаах": function () {

              maintenance_load_id.dialog("close");

          }

       }

    });

    maintenance_load_id.dialog( "open" );

}

function add_spare(){

    spare.dialog( "open" );

}

function add_spare_edit(){

    add_spare_edit_dialog.dialog( "open" );

}


function spare_row_delete(tr_id){

    $('#tr_id_'+tr_id, '#spare_table').remove();

    counter--;

}


function add_repair_model(equipment_id, location_id, passbook_id){

    repair_load.dialog({
       
       title: "Гэмтэл дутагдлын бүртгэл:Засварласан гэмтлүүд",

       buttons: {

          "Хадгалах": function () {
            
             //var data = {};
             var data = $('#repair_load' ).serialize();

              $.ajax({
                
                  type:   'POST',

                  url:    base_url+'/equipment/index/add_repair_load',
                  
                  data:   data+'&passbook_id='+passbook_id,

                  dataType: 'json',

                  async: false,

                  success:  function(json){

                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                       
                       //энд үндсэн утгуудыг нэмэх болно.
                       repair_load.dialog("close");
                       
                       // close the dialog
                       showMessage(json.message, 'success');
                       
                       // show the success message                    
                       setTimeout(function() { location.reload();  }, 1000);
                    
                    }else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', passbook).removeClass('success, notify').addClass('error').html(json.message).show();
                    }
                  }
              });
          },

          "Хаах": function () {

              repair_load.dialog("close");

          }

       }

    });

    repair_load.dialog( "open" );


}

  function add_new_spare (device_id){

     if($('#sp_id').val() != null)

        new_spare.dialog("open");
      
     else alert('Энэ төхөөрөмж дээр сэлбэгийн дугаарыг өгөөгүй байгаа тул шинэ сэлбэг нэмэх боломжгүй байна!');


  }