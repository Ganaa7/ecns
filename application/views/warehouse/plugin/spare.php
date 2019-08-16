<style type="text/css">
.ui-widget, .ui-button-text-only {
	font-size: 12px;
}

.ui-widget-header {
	font-size: 13px;
}

.ui-button .ui-button-text {
	line-height: 0.7;
	font-size: 12px;
}

form select {
	margin-right: 0.2em;
}
</style>
<script type="text/javascript">    
    $(function() {
        //autocomplete here
       $( "#spare" ).autocomplete({
          source: "spareJson",
          minLength: 2,
          select: function( event, ui ) {
             if(ui.item.value){             
                $('#spare_id').val(ui.item.id);                
             }   
          },
          search:function( event, ui){
             $('#spare_id').val(0); 
          }
        });
        // tooltip here
  
         $( document ).tooltip({
             track: true
         });
    });
</script>
<?php
/*
 * Created by Ganaa
 * 2013-07-04
 * Plugin can spare
 */
echo form_input ( 'spare', null, "id='spare' style='width: 200px;  margin: 0 3px;' title='Бичиж, гарч ирэх жагсаалтаас сэлбэгийг сонгоно уу!'" );
echo "<input type='hidden' name='spare_id' id='spare_id'/>";

?>

