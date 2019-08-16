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

label {
	display: inline-block;
	width: 5em;
}

input.search_btn {
	background-image: url('http://localhost/ecns/images/search.png');
	/* background-color: transparent;  make the button transparent     
    background-position: 0px 0px;   equivalent to 'top left'     */
	top: 0px;
	background-repeat: no-repeat;
	line-height: 0.95; /* make this the size of your image */
	width: 30px;
	border: none;
	height: 30px;
}

input.search_btn:hover {
	border: none;
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
 * This file shows filter from plugin_model in ecns
 */
$section = $this->main_model->get_industry ();
// $sector=$this->main_model->getSector($section_id);
// $equipment=$this->main_model->getEquipment($section_id);
$attribute = array (
		'name' => 'filter' 
);
echo form_open ( '', $attribute );

echo "<div align='center' style='margin:5px 30px 5px;'>";
echo "<fieldset>";
echo "<legend>Хайлт</legend>";
echo form_input ( 'spare', null, "id='spare' style='width: 200px;  margin: 0 3px;' title='Сэлбэгийн нэрийг сонгож хайна уу!'" );
echo "<input type='hidden' id='spare_id'/>";
echo "<input type='button' name='search' id='search' value ='Хайх'/>";
echo "<input type='button' class='search_btn' id='searchbtn' title='Нэмэлт утгаар хайлт хийх.'/>";
// src='".base_url()."images/search2.png'
echo "</fieldset>";
echo "</div>";
echo form_close ();
?>

