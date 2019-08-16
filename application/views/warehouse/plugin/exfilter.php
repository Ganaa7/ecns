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
$tmp_spare = array (
		'0' => 'Сэлбэг!' 
);
echo form_open ( '', $attribute );
echo "<div id ='sectionFilter' >";
echo "<fieldset style='padding-top:5px; margin-top:5px;'>";
echo "<legend>Сэлбэгийн мэдээлэл</legend>";
echo "<div align='center'>";
echo form_dropdown ( 'section_id', $section, $section_id, "id=section_id onclick='getSelect(this.value);' style='width: 150px; margin: 0 3px;'" );
echo form_dropdown ( 'sector_id', $sector, $sector_id, "id=sector_id onclick='getEquipments(this.value);' style='width: 170px;  margin: 0 3px;'" );
echo form_dropdown ( 'equipment_id', $equipment, $equipment_id, "id=equipment_id style='width: 150px;  margin: 0 3px;' " );
echo form_dropdown ( 'sparetype_id', $sparetype, $sparetype_id, "id=sparetype_id style='width: 150px;  margin: 0 3px;' onclick=callSpare(this.value);" );
echo "</div>";
echo "<div style='margin-top:10px;'>";
echo '<label>Сэлбэг:</label>';
echo "<span id='spare'>";
echo form_dropdown ( 'spare_id', $tmp_spare, 0, 'id=spare_id' );
echo "</span>";
echo "</div>";
echo "</fieldset>";
echo "</div>";
echo form_close ();
?>
