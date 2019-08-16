<?php
/*
 * Created by Ganaa
 * 2017-08-05
 * This file shows filter from plugin_model in ecns
 */
if ($industy_id) {
	$section = $this->main_model->_plugin_section($industy_id);
	$sector = $this->main_model->getSector($industy_id);
	$equipment = $this->flog_model->filter_equipment($industy_id);
} else {
	$section = $this->main_model->_plugin_section();
	$sector = $this->main_model->getSector();
	$equipment = $this->flog_model->filter_equipment(0);
}

$attribute = array(
	'name' => 'log_filter',
	'id' => 'log_filter'
);
echo form_open('', $attribute);
?>
<div id='sectionFilter' align='center'>
	<fieldset style='padding-top: 5px; margin-top: 5px; width: 90%'>
		<legend>Шүүлт</legend>
		<!-- <div class='plugin'> -->
		<?php

		$filter = $this->session->userdata('flog_filter');

		if (isset($industy_id))
			echo form_dropdown('section_id', $section, $industy_id, "id=section_id style='width: 150px; margin: 0 3px;'");
		else if (isset($filter))
			echo form_dropdown('section_id', $section, 0, "id=section_id style='width: 150px; margin: 0 3px;'");


		echo form_dropdown('sector_id', $sector, 0, "id=sector_id style='width: 170px;  margin: 0 3px;'");
		echo form_dropdown('equipment_id', $equipment, 0, "id=equipment_id style='width: 150px;  margin: 0 3px;'");

		$date_option = array(
			'0' => 'Огнооны төрөл!',
			'created_dt' => 'Нээсэн огноо',
			'closed_dt' => 'Хаасан огноо'
		);
		echo form_dropdown('date_option', $date_option, null, "id='date_option'");
		?>
		<input type='text' name='start_dt' id="start_dt" style='width: 80px' placeholder="Эхлэх утга" value /> <input type='text' name='end_dt' id="end_dt" style='width: 80px' placeholder="Дуусах утга" value />
		<?php
		$log_option = array(
			'0' => 'Бүх гэмтэл',
			'N' => 'Нээлттэй гэмтэл',
			'Y' => 'Хаалттай гэмтэл',
			'Q' => 'Үнэлгээ хийх гэмтэл',
			'F' => 'Файл хавсаргах шаардлагатай',
			'G' => 'Файл хавсаргасан гэмтэл'
		);
		echo form_dropdown('log', $log_option, null, "id='log'");
		?>

		<input type='button' id='filterBtn' value='Шүүх' />

		<!-- </div> <-->
		</-->
	</fieldset>
</div>
<?php
echo form_close();
?>