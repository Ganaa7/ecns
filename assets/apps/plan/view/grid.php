<div id="main_wrap" style="margin: 10px 20px 20px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>


<script type="text/javascript">
var edit;
$( function() {

  var currentDate = new Date();

  $( "#out_dt_").datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false
   });


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

                case 'add':

                ?>
                "add": {name: "Шинэ", icon: "add",
                      // zasah function
                       callback: function(itemKey){
                            var id = $(this).attr('id');
                            console.log('calling add');
                            add_modal(id);
                        }
                    },

                <?php
                  # code...
                  break;

               case 'edit':

              ?>
              "edit": {name: "Засах", icon: "copy",
                    // zasah function
                     callback: function(itemKey){
                          var id = $(this).attr('id');
                         
                          var title = $(this).find( "td:eq(3)" ).text();
                         
                        
                          edit_modal(id, title);
                      }
                  },
              <?php
                break;

              case 'add_detail':

                ?>
                "add_detail": {name: "Хэрэжүүлэлт нэмэх", icon: "add",
                      // zasah function
                       callback: function(itemKey){
                            
                            var id = $(this).attr('id');

                             console.log('plan id'+id);
                            
                            add_detail_modal(id);
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
                            //confirm("Press a button!");
                            var is_confirm = confirm("Энэ сэлбэгийн бичлэгийг устгахдаа итгэлтэй байна уу?");
                            if(is_confirm){
                                $.ajax({
                                   type:    'POST',
                                   url:    base_url+'/plan/index/delete',
                                   data:   {id: id},
                                   dataType: 'json',
                                   success:  function(json) {
                                       if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                                          // close the dialog
                                          showMessage(json.message, 'success');
                                          // amjilttai bolson tohioldold ene heseg uruu shidne                                                  
                                          jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
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


  //sumb context menu created
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

              case 'dtl_edit':

                ?>
                "edit": {name: "Засах", icon: "edit",
                      // zasah function
                       callback: function(itemKey){

                            var id = $(this).attr('id');
                           
                            var title = $(this).find( "td:eq(3)" ).text();
                          
                            edit_dtl_modal(id, title);
                        }
                    },
                <?php
                  break;
             
              case 'dtl_delete':

              ?>
                "delete": {name: "Устгах", icon: "delete", 
                            // zasah function
                             callback: function(itemKey){
                                  var id = $(this).attr('id');                         
                                  ///alert("Clicked on " + itemKey + " on element " + id);
                                  // Do not close the menu after clicking an item
                                  //confirm("Press a button!");
                                  var is_confirm = confirm("Энэ ажлыг устгахадаа итгэлтэй байна уу?");
                                  if(is_confirm){
                                      $.ajax({
                                         type:    'POST',
                                         url:    base_url+'/plan/index/dtl_delete',
                                         data:   {id: id},
                                         dataType: 'json', 
                                         success:  function(json) {
                                             if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                                                // close the dialog                                                
                                                showMessage(json.message, 'success');
                                                // amjilttai bolson tohioldold ene heseg uruu shidne
                                                //load grid here       
                                                jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
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

              case 'completion':

              ?>
                "completion": {name: "Гүйцэтгэл", icon: "edit", 

                    // zasah function
                     callback: function(itemKey){

                          var id = $(this).attr('id');                         

                          completion_modal(id);

                        
                                            
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
  //end subcontext menu created


});




</script>
