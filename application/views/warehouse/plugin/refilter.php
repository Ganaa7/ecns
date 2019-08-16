<?php
/*
 * Created by Ganaa
 * 2013-02-20
 * This file shows filter from plugin_model in ecns
 */
$section_id = $this->input->get_post ( 'section_id', TRUE );
$equipment_id = $this->input->get_post ( 'equipment_id', TRUE );
$sparetype_id = $this->input->get_post ( 'sparetype_id', TRUE );

$section = $this->main_model->get_industry ();
$equipment = $this->main_model->getEquipment ();
$sparetype = $this->wm_model->getSparetype ();

$attribute = array (
		'name' => 'filter' 
);
echo form_open ( '', $attribute );
echo "<div id ='section' align='center' margin='20px'>";
echo "<fieldset style='padding-top:5px; margin-top:5px;'>";
echo "<legend>Шүүлт</legend>";
echo "<div>";
echo form_dropdown ( 'section_id', $section, $section_id );
echo form_dropdown ( 'equipment_id', $equipment, null );
echo form_dropdown ( 'sparetype_id', $sparetype, null );
echo form_hidden ( 'flag', 1 );
?>
<div align="center" style="margin-top: 10px;">
	<input name='todate' id='todate' size='8' />
	<button id='c_button' class='btn_timer'></button>
	<script type="text/javascript">//<![CDATA[
            Calendar.setup({
            inputField : "todate",
            trigger    : "c_button",
            onSelect   : function() { this.hide() },
            showTime   : 24,
            dateFormat : "%Y-%m-%d"
            });
        //]]></script>
	<!--finished date -->
	<span>Хүртэл</span> <input type='submit' name='filter' value='Шүүх' />
</div>
<?

echo "</div>";
echo "</fieldset>";
echo "</div>";

echo form_close ();
?>
