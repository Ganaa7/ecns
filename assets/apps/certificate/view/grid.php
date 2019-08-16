<div id="main_wrap" style="margin: 10px 20px 20px">

    
    <button class="button" onclick="add_dialog()">Шинэ гэрчилгээ нэмэх</button>
    
<br>  

	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>

	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>

<script type="text/javascript">

$( function() {

  var currentDate = new Date();

  $( "#date_dt").datepicker({   
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

// contexxt menu 2
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