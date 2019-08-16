<?=$library_src; ?>

<?php var_dump($out->action);?>

  
<div id="main_wrap" style="margin: 0 20px 20px">
	
		<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
		</table>
		<div id="pager" class="scroll" style="text-align: center;"></div>
	<input type="hidden" name='sec_code' id="sec_code"
		value="<?=$out->sec_code;?>"> <input type="hidden" name='role'
		id="role" value="<?=$out->role;?>">
	</div>


  <script>

  $( function() {

    $.contextMenu({
          selector: '.context-menu', 
          callback: function(key, options) {
              var m = "clicked: " + key;
              window.console && console.log(m) || alert(m); 
              var id = $(this).attr('id');       
              console.log('ID:'+id);
          },
          items: {
              <?php foreach ($out->action as $values) {
                # code...
                switch ($values) {
                  case 'add':
                  ?>
                     "new": {name: "Шинэ", icon: "copy",
                        callback: function(itemKey){

                          // var id = $(this).attr('id');     

                          // var title = $(this).find( "td:eq(3)" ).text();
                        
                          add_dialog(); 

                          return true;             
                        }
                      },            
                  <?php
                    # code...
                    break;  
                    case 'edit':
                  ?>
                     "edit": {name: "Засах", icon: "edit",
                        callback: function(itemKey){

                          var id = $(this).attr('id');     

                          var title = $(this).find( "td:eq(3)" ).text();
                        
                          edit_dialog(id); 

                          return true;             
                        }
                      },            
                  <?php
                    # code...
                    break;    
                  default:
                  ?>

                  "delete": {name: "Устгах", icon: "delete",
                        callback: function(itemKey){
                       
                          var id = $(this).attr('id');     

                          var spare = $(this).find( "td:eq(3)" ).text();
                       
                          _delete(id); 

                          return true;             
                        }
                      }, 

                   "outservice": {name: "Ашиглалтаас хасах", icon: "delete",
                        
                        callback: function(itemKey){
                       
                          var id = $(this).attr('id');     

                          var spare = $(this).find( "td:eq(3)" ).text();
                       
                          outservice(id); 
                          // alert('clicked here'+id);

                          return true;             
                        }
                      },

                  <?php 
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