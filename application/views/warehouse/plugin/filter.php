<script>  
    /*
   $(document).ajaxComplete(function() {
      var sector_id;
      //sector_id=$('#sector_id :selected').val();
       
      // Equipment selection
      if(sector_id!=''){
         $.post( '/ecns/wm_ajax/getEquipments', {sector_id:sector_id}, function(newOption) {   
            var select = $('#equipment_id');
            if(select.prop) {
               var options = select.prop('options');
            }else {
               var options = select.attr('options');
           }        
           $('option', select).remove();
           $.each(newOption, function(val, text) {
              options[options.length] = new Option(text, val);
           });
        });
      }     
   });
  */
</script>
<?php
/*
 * Created by Ganaa
 * 2013-02-20
 * This file shows filter from plugin_model in ecns
 */
$section = $this->main_model->get_industry ();
$sector = $this->main_model->getSector ( $section_id );
$equipment = $this->main_model->getEquipment ( $section_id );
$sparetype = $this->wm_model->getSparetype ();
$attribute = array (
		'name' => 'filter' 
);
echo form_open ( '', $attribute );
echo "<div id ='section' align='center'>";
echo "<fieldset style='padding-top:5px; margin-top:5px;'>";
echo "<legend>Шүүлт</legend>";
echo "<div>";
echo form_dropdown ( 'section_id', $section, $section_id, "id=section_id onclick='getSelect(this.value);' style='width: 150px; margin: 0 3px;'" );
echo form_dropdown ( 'sector_id', $sector, $sector_id, "id=sector_id onclick='getEquipments(this.value);' style='width: 170px;  margin: 0 3px;'" );
echo form_dropdown ( 'equipment_id', $equipment, $equipment_id, "id=equipment_id style='width: 150px;  margin: 0 3px;'" );
echo form_dropdown ( 'sparetype_id', $sparetype, $sparetype_id, "id=sparetype_id style='width: 150px;  margin: 0 3px;'" );
echo form_hidden ( 'flag', 1 );
echo "<input type='submit' name='filter' value ='Шүүх'/>";
echo "</div>";
echo "</fieldset>";
echo "</div>";
echo form_close ();
?>
