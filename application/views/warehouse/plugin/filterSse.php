<?
/*
 * Created by Ganaa
 * 2013-02-20
 * This file shows filter from plugin_model in ecns
 */
$section = $this->main_model->get_industry ();
$sector = $this->main_model->getSector ( 0 );
$equipment = $this->main_model->getEquipment ( 0 );

$attribute = array (
		'name' => 'filter' 
);
echo form_open ( '', $attribute );
echo "<div id ='sectionFilter' align='center'>";
echo "<fieldset style='padding-top:5px; margin-top:5px;'>";
echo "<legend>Шүүлт</legend>";
echo "<div>";
echo form_dropdown ( 'section_id', $section, 0, "id=section_id onclick='getSelect(this.value);' style='width: 150px; margin: 0 3px;'" );
echo form_dropdown ( 'sector_id', $sector, 0, "id=sector_id onclick='getEquipments(this.value);' style='width: 170px;  margin: 0 3px;'" );
echo form_dropdown ( 'equipment_id', $equipment, 0, "id=equipment_id style='width: 150px;  margin: 0 3px;'" );
echo form_hidden ( 'flag', 1 );
echo "<input type='button' id='filterBy' value ='Шүүх'/>";
// echo "<input type='button' name='search' id='search' value ='Хайх'/>";
echo "</div>";
echo "</fieldset>";
echo "</div>";
echo form_close ();
?>
