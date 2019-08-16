<div id="main_wrap" style="margin: 10px 20px 20px">
	<!-- <button id = 'create_trip' class="button">Нэмэх</button> -->

	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>



<script type="text/javascript">
var spot, edit;
$( function() {

  var currentDate = new Date();


	$(".chosen-select").chosen();


  $( "#out_dt_").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });

  $( "#est_dt_").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });


  $( "#est_dt_edit").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });

  $( "#out_dt_edit").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });


  
   // end jquery here
   //menu generate here
 $.contextMenu({
        selector: '.context-menu', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
            var id = $(this).attr('id');       
            console.log('ID:'+id);
        },
        items: {
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {
                case 'edit':
                ?>
                "edit": {name: "Засах", icon: "edit",
                    callback: function(itemKey){
                        var id = $(this).attr('id');       
                        // Alert the key of the item and the trigger element's id.
                        //call edit dialog here                   
                       // alert("Clicked on " + itemKey + " on element " + id);
                        init_edit(id);

                        // Do not close the menu after clicking an item
                        return true;             
                    }
                  },
                <?php
                  # code...
                break;
               
                case 'delete':

                ?>
                  "delete": {name: "Устгах", icon: "delete", 
                              // zasah function
                               callback: function(itemKey){
                                    var id = $(this).attr('id');                         
                                    ///alert("Clicked on " + itemKey + " on element " + id);
                                    // Do not close the menu after clicking an item
                                    //confirm("Press a button!");
                                    var is_confirm = confirm("Та энэ томилолтыг устгахдаа итгэлтэй байна уу?");
                                    if(is_confirm){
                                        $.ajax({
                                           type:    'POST',
                                           url:    base_url+'/trip/index/delete/',
                                           data:   {trip_id: id},
                                           dataType: 'json', 
                                           success:  function(json) {
                                               if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                                                  // close the dialog                                                
                                                  showMessage(json.message, 'success');
                                                  // amjilttai bolson tohioldold ene heseg uruu shidne
                                                  //load grid here       
                                                  reload();  
                                               }else
                                                  showMessage(json.message, 'error');
                                          }
                                        }).done(function() {
                                            console.log('state: call here dialog');       
                                        });
                                  }
                                    return true;             
                                }
                              },
                <?php 
                  # code...
                  break;
               
              }
            } ?>
            
            "sep1": "---------",
            "help": {name: "Тусламж", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
  });

// contexxt menu 2
 $.contextMenu({
        selector: '.context-menu-sub', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
            var id = $(this).attr('id');       
            console.log('ID:'+id);
        },
        items: {
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {
                case 'spot':
                ?>
                   "out": {name: "Гарсан цаг", icon: "edit",
                          callback: function(itemKey){
                          var id = $(this).attr('id');                          
                          var table_id = $(this).closest('table').attr('id');
                          var parent_id = parseInt(table_id.replace(/[^0-9\.]/g, ''), 10);
                          var status; var out_dt;
                          // console.log('id'+id+ 'parent_id'+parent_id);
                          //get dialog data from jquery grid
                          $.ajax({
                            type:    'POST',
                            url:    base_url+'/trip/index/get_route/',
                            data:   { id: id, parent_id:parent_id},
                            dataType: 'json', 
                            success:  function(json) {                                        
                              //add all values to the html inputs here                            
                              status = json.status;
                              //if(json.json.out_dt) out_dt = json.json.out_dt;                            
                              
                              if(json.status=='true'){                            
                                $('#from_route', '#go-form').val(json.json.from_route);
                                $('#to_route', '#go-form').val(json.json.to_route);
                                $('#distance', '#go-form').val(json.json.distance);
                                $('#out_dt_', '#go-form').val(json.json.out_dt);  
                                out_dt = json.json.out_dt;                            
                              }else
                                out_dt = null;
                                gen_option(json, '#go-form');
                            }
                          }).done(function() {
                             if(status=='true'&&out_dt==null){
                                go_dialog(id, parent_id);   
                             }else if(status=='true'&&out_dt!==null){
                                alert('Энэ чиглэлд аль хэдийн гарсан цагийг өгсөн тул дахин өгөх боломжгүй!');
                             }else{
                                alert('Өмнөх чиглэл явж буй ИТА-г очоогүй байхад дараагийн чиглэлд гарсан цагийг өгөх боломжгүй!');
                             }
                            
                          });
                          return true;             
                      }
                    },
                    "in": {name: "Очсон эсэх", icon: "paste",
                       callback: function(itemKey){
                          var route_id = $(this).attr('id');    
                          var table_id = $(this).closest('table').attr('id');
                          var parent_id = parseInt(table_id.replace(/[^0-9\.]/g, ''), 10);   
                          //init_spot(trip_id);
                          init_spot(route_id, parent_id);
                       }
                     },            

                                           
                      "comment": {name: "Тэмдэглэл", icon: "paste",
                       callback: function(itemKey){
                          var route_id = $(this).attr('id');    
                          var table_id = $(this).closest('table').attr('id');
                          var parent_id = parseInt(table_id.replace(/[^0-9\.]/g, ''), 10);                          
                          //init_spot(route_id, parent_id);
                            // var data = { id: trip_id};     
                          
                          comment_dialog(route_id, parent_id);
                       }
                     },
                <?php
                  # code...
                  break;     

                case 'edit':
                ?>
                      "edit": {name: "Засах", icon: "edit",
                       callback: function(itemKey){
                          var route_id = $(this).attr('id');    
                          var table_id = $(this).closest('table').attr('id');
                          var parent_id = parseInt(table_id.replace(/[^0-9\.]/g, ''), 10);                          
                          //init_spot(route_id, parent_id);
                            // var data = { id: trip_id};                               
                            edit_spot_dialog(route_id, parent_id);
                       }
                     },   
           
                <?php
                  # code...
                  break;            
              }
            } ?>
            
            "sep1": "---------",
            "help": {name: "Тусламж", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
  });


});



	
</script>