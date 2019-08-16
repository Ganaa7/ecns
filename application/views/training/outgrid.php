<div id="main_wrap" style="margin: 20px auto; width: 1260px">    

<?php if($out->role=='ADMIN'||$out->role=='TECHENG'){ ?>

<?php } ?>

  <table id="outgrid" class="scroll" cellpadding="0" cellspacing="0">
  </table>
	<div id="pager" class="scroll" style="text-align: center;"></div>     
  
   <?php
			foreach ( $out->action as $key => $value ) {
				echo "<input type='hidden' name='actions' class='action' value='$value'/>";
			}
			?>
</div>

<script type="text/javascript">

$( function() {

  var currentDate = new Date();

// contexxt menu 2
 $.contextMenu({
        selector: '.context-menu', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
            var id = $(this).attr('id');       
        },
        items: {
            <?php foreach ($out->action as $values) {
							# code...
							
              switch ($values) {
                case 'new':
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
                      
                       init_edit(id);

                        return true;             
                      }
                    },            
                <?php
                  
									break;  
									case 'delete':
                ?>
									 "delete": {name: "Устгах", icon: "delete",
										
                      callback: function(itemKey){

                        var id = $(this).attr('id');
                      
                        _delete(id); 

                        return true;             
                      }
                    },            
                <?php
                  # code...
									break; 	
									case 'license_print':
                ?>
									 "print": {name: "Хэвлэх", icon: "context-menu-icon context-menu-icon-loading",
										
                      callback: function(itemKey){

                        var id = $(this).attr('id');
                      
                        _print(id); 

                        return true;             
                      }
                    },            
                <?php
                  # code...
									break; 
									case 'archive':
                ?>
									 "archive": {name: "Архив", icon: "new",
										
                      callback: function(itemKey){

                        var id = $(this).attr('id');
                      
                        outservice(id); 
                       

                        return true;             
                      }
                    },            
                <?php
                  # code...
                  break;        
                default:
                ?>

                  "more": {name: "Дэлгэрэнгүй", icon: "copy",
                      
                      callback: function(itemKey){
                     
                        var id = $(this).attr('id');     

                        init_view(id); 

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