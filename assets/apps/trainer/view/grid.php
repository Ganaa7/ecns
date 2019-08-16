<div id="main_wrap" style="margin: 20px auto; width: 1260px">    


  <p style='margin: 10px;'>
		<a href="<?=base_url();?>training/trainer/add" class="button">Шинээр бүртгэл
			үүсгэх</a>
	</p>


  <table id="grid" class="scroll" cellpadding="0" cellspacing="0">
  </table>
	<div id="pager" class="scroll" style="text-align: center;"></div>     
  
   
</div>

<script>
	// contexxt menu 2
		$.contextMenu({
     selector: '.context-menu1', 
     callback: function(key, options) {
       
      
         var id = $(this).attr('id');       
       
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
             <?php 
           }
         } ?>
         
         "sep1": "---------",
         "help": {name: "Тусламж", icon: function(){
             return 'context-menu-icon context-menu-icon-quit';
         }}
     }
  });

	
</script>