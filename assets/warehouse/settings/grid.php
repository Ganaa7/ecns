<div id="main_wrap" style="margin: 10px 20px 20px">
	<!-- <button id = 'create_trip' class="button">Нэмэх</button> -->
    <div style="margin:5px 0px; color: red">Үйлдлүүдийг сонгохдоо <b>тухайн мөрөн</b> дээр баруун товчоо дараад гарч ирэх фүнкцүүдээс сонгоно уу! </div>
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>
<script type="text/javascript">
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
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {
                case 'add':
                ?>
                "new": {name: "Шинэ", icon: "paste",
                    callback: function(itemKey){                        
                        create_modal();
                        // Do not close the menu after clicking an item
                        return true;             
                    }
                  },
                <?php                
                break;              

                case 'edit':
                ?>
                "edit": {name: "Засах", icon: "edit",
                    callback: function(itemKey){
                        var id = $(this).attr('id');                              
                        edit_modal(id);
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
	                   callback: function(itemKey){
	                   	  var id = $(this).attr('id');  
                          spare_delete(id);
                          return true;          
	                    }
	                  },
                <?php 
                  # code...
                  break; 

                  default:?>
                 
                  "barcode": {name: "Баркод хэвлэ", icon: "edit  ",                               
                     callback: function(itemKey){
                        var id = $(this).attr('id');  
                          // print_barcode(id);
                          return true;          
                      }
                    },
                    <?php break;
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