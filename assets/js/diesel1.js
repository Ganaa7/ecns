	
      $(function () {
            fill= $("#fill");
            fill.dialog({
               autoOpen: false,
               width: 550,       
               resizable: false,    
               modal: true,
               position: ['center',100],               
               close: function () {          
                  // clear & hide the feedback msg inside the form
                  $('input[type="text"], input[type="hidden"], select, textarea', fill).val('');
                  // clear the input values on form    
                  $(this).dialog("close");
               }
            });     

            fuel = $("#fuel");
            fuel.dialog({
               autoOpen: false,
               width: 550,       
               resizable: false,    
               modal: true,
               position: ['center',100],               
               close: function () {          
                  // clear & hide the feedback msg inside the form
                  $('input[type="text"], input[type="hidden"], select, textarea', fuel).val('');
                  // clear the input values on form    
                  $(this).dialog("close");
               }
            });               
            var arrtSetting = function (rowId, val, rawObject, cm) {
                var attr = rawObject.attr[cm.name], result;                    
                //alert(attr.rowspan);
                if (attr.rowspan) {
                    result = ' rowspan=' + '"' + attr.rowspan + '"';
                } else if (attr.display){
                    result = ' style="display:' + attr.display + '"';
                }
                return result;
            };
            //ахлах инж болон бусад инж байвал         
                console.log('base_url'+base_url);
                  $("#grid").jqGrid({

                   url:base_url+'/diesel/index/grid',
                    datatype: "json",
                    mtype: 'GET',                                
                    colNames: ['#', 'Байршил','Байгууламж', 'main_equipment_id', 'Дизель генератор', 'Чадал', 'Зарцуулалт', 'Банк/лтр/', 'bank_id', 'Нөөц ёнкость', 'Нийт багтаамж', 'Үлдэгдэл/түлш', 'Ажиллах цаг','Шинэчлэсэн/t', 'Шинэчлэсэн/ИТА'],
                    colModel: [
                        { name: 'id', width: 20, align: 'center'},
                        { name: 'location', width: 50, align: 'center', cellattr: arrtSetting},
                        { name: 'main_equipment', width: 50, align:'center', cellattr: arrtSetting },
                        { name: 'main_equipment_id', hidden:true},
                        { name: 'equipment', width: 40 },
                        { name: 'power', index: 'power', width: 25, align: 'left' },
                        { name: 'consumption', index: 'consumption', width: 32, align: 'right' },
                        { name: 'bank', index: 'bank', width: 30, align: 'right' },
                        { name: 'bank_id', hidden:true },
                        { name: 'capacity', index: 'capacity', width: 35, align: 'center', cellattr: arrtSetting },
                        { name: 'total', index: 'total', width: 40, align: 'center', cellattr: arrtSetting },
                        { name: 'fuel', index: 'fuel', width: 40, align: 'center', cellattr: arrtSetting, formatter:view_link },
                        { name: 'workhour', index: 'workhour', width: 35, align: 'center', cellattr: arrtSetting },
                        { name: 'uptime', index: 'uptime', width: 45, align: 'center', cellattr: arrtSetting},
                        { name: 'checkedby', index: 'checkedby', width: 40, align: 'center', cellattr: arrtSetting}
                        
                        ],
                        jsonReader : {
                        page: "page",
                        total: "total",
                        records: "records",
                        root:"rows",
                        repeatitems: false,
                        id: "id"
                    },
                    cmTemplate: {sortable: false},
                    hoverrows: false,
                    width:'1260',
                    rowNum: 100,
                    gridview: true,                
                    height: '100%',
                    caption: '.: Дизель генераторуудын түлшний хэмжээ :.',
                    beforeSelectRow: function () {
                        return false;
                  }, 
                  loadComplete: function (){
                     var rowIds = $(this).jqGrid('getDataIDs');          
                     for (var i=0;i<rowIds.length;i++){ 
                        var rowData=$('#grid').jqGrid('getRowData', rowIds[i]);
                        var trElement = jQuery("#"+ rowIds[i],jQuery('#grid'));
                        if(rowData.main_equipment_id=='208'&&rowData.workhour<49&&rowData.capacity =='3000 л'){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent'); 
                            console.log('hi its '+rowData.main_equipment_id);
                        }else if(rowData.workhour<9&&rowData.bank_id==5){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent'); 
                        }else if(rowData.workhour<17&&rowData.bank_id==13){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent');
                        }else if(rowData.workhour<9&&rowData.bank_id==15){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent');
                        }else if(rowData.workhour<9&&rowData.bank_id==20){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent');
                        }else if(rowData.workhour<25&&rowData.bank_id!=20&&rowData.bank_id!=15&&rowData.bank_id!==13&&rowData.bank_id!=5){
                            trElement.removeClass('ui-widget-content');
                            trElement.addClass('argent');
                        }
                        //console.log('id '+rowData.id+'bank id :'+rowData.bank_id+'work_hour: '+rowData.workhour);
                         // else if(rowData.workhour<24&&rowData.bank_id!='20'&&rowData.bank_id!='9'){
                         //     trElement.removeClass('ui-widget-content');
                         //     trElement.addClass('argent');
                         // }
                        // if(rowData.workhour>48||168<=rowData.workhour){
                        //    trElement.removeClass('ui-widget-content');
                        //    trElement.addClass('warning');                  
                        // }
                     }         
                 }
            });
           

            function view_link(cellValue, options){
               if($('#action').val()=='add')
                  return "<a href='#' class='fuel_edit_fn' onclick='fuel_dialog(" + options.rowId + ")' >"+cellValue+"</a>"; 
               else return cellValue;
            }
            function filled_link(cellValue, options){
               if($('#action').val()=='add')
                  return "<a href='#' class='fuel_edit_fn' onclick='fill_dialog(" + options.rowId + ")' >"+cellValue+"</a>"; 
               else return cellValue;
            }
        });
    //]]>  

function fill_dialog(id){   
    var data = { pk_id: id };        
      title = 'Банк цэнэглэсэн түлшний хэмжээ оруулах';
      //ajax-с утгуудыг авч inputed- харуулах
      $.ajax({
         type:    'POST',
         url:    base_url+'/diesel/index/page/'+id,
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            //herev closed baival utguudiig haruulna   
              $("#id", fill).val(json.id);
              $("#location", fill).text(json.location);
              $("#main_equipment", fill).text(json.main_equipment);
              $("#equipment", fill).text(json.equipment);              
              $('#power', fill).text(json.power);   
              $('#consumption', fill).text(json.consumption);   
              $('#bank', fill).text(json.bank);              
              $('#capacity', fill).text(json.capacity);
              $('#bank_id', fill).val(json.bank_id);
         }
      }).done(function() {
         fill.dialog('option', 'title', title);
         fill.dialog({ 

            buttons: {  
              "Хадгалах": function(){
                  var data = {pk_id:id};
                  var inputs = $('input[type="text"], input[type="hidden"], select, textarea', fill);

                  inputs.each(function(){
                    var el = $(this);
                    data[el.attr('name')] = el.val();
                  });

                  $.ajax({
                      type:     'POST',
                      url:    base_url+'/diesel/index/insert/',
                      data:   data,
                      dataType: 'json', 
                      success:  function(json){ 
                        if (json.status == "success"){
                           fill.dialog('close');                                             
                           showMessage(json.message, 'success');                           
                           reload();
                        }                  
                        else{  // ямар нэг юм нэмээгүй тохиолдолд
                          $('p.feedback', fill).removeClass('success, notify').addClass('error').html(json.message).show();
                        }
                      }
                  });                           
               },           
               "Хаах": function () {
                   fill.dialog("close");
               }
            }
         }); 
         fill.dialog('open');
      });
}

function fuel_dialog(id){   
    var data = { pk_id: id };        
      title = 'Байгаа түлшний хэмжээ оруулах';
      //ajax-с утгуудыг авч inputed- харуулах
      $.ajax({
         type:    'POST',
         url:    base_url+'/diesel/index/page/'+id,
         data:   data,
         dataType: 'json', 
         success:  function(json) {
            //herev closed baival utguudiig haruulna   
              $("#id", fuel).val(json.id);
              $("#location", fuel).text(json.location);
              $("#main_equipment", fuel).text(json.main_equipment);
              $("#equipment", fuel).text(json.equipment);              
              $('#power', fuel).text(json.power);   
              $('#consumption', fuel).text(json.consumption);   
              $('#bank', fuel).text(json.bank);              
              $('#capacity', fuel).text(json.capacity);
              $('#bank_id', fuel).val(json.bank_id);
         }
      }).done(function() {
         fuel.dialog('option', 'title', title);
         fuel.dialog({ 

            buttons: {  
              "Хадгалах": function(){
                  var data = {pk_id:id};
                  var inputs = $('input[type="text"], input[type="hidden"], select, textarea', fuel);

                  inputs.each(function(){
                    var el = $(this);
                    data[el.attr('name')] = el.val();
                  });

                  $.ajax({
                      type:     'POST',
                      url:    base_url+'/diesel/index/insert/',
                      data:   data,
                      dataType: 'json', 
                      success:  function(json){ 
                        if (json.status == "success"){
                           fuel.dialog('close');                                             
                           showMessage(json.message, 'success');                           
                           reload();
                        }                  
                        else{  // ямар нэг юм нэмээгүй тохиолдолд
                          $('p.feedback', fuel).removeClass('success, notify').addClass('error').html(json.message).show();
                        }
                      }
                  });                           
               },           
               "Хаах": function () {
                   fuel.dialog("close");
               }
            }
         }); 
         fuel.dialog('open');
      });
}


function reload(){
   //$("#grid").trigger("reloadGrid"); 
   $("#grid").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");   
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
      paragraph.delay(5000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}