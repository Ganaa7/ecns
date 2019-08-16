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
        selector: '.context-menu1', 
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

                        var title = $(this).find( "td:eq(3)" ).text();
                      
                        edit_modal(id, title); 

                        console.log('Edit'+title);

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
                     
                        console.log('Delete this i delete it!');

                        _delete(id); 

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