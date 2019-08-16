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
	background-image: url(../images/search2.png);
	/* background-color: transparent;  make the button transparent     
    background-position: 0px 0px;   equivalent to 'top left'     */
	top: 1px;
	background-repeat: no-repeat;
	line-height: 0.95; /* make this the size of your image */
	width: 30px;
	border: none;
}

input.search_btn:hover {
	border: none;
}
</style>

<?
/*
 * Created by Ganaa
 * 2013-07-04
 * This file shows filter from plugin_model in ecns
 * no auto complete
 */
// $sector=$this->main_model->getSector($section_id);
// $equipment=$this->main_model->getEquipment($section_id);
$attribute = array (
		'name' => 'searchby' 
);
echo form_open ( '', $attribute );

echo "<div align='center' style='margin:5px 30px 5px;'>";
echo "<fieldset>";
echo "<legend>Шүүлт</legend>";
echo form_input ( 'spare', null, "id='spare' style='width: 200px;  margin: 0 3px;' title='Сэлбэгийн нэрийг сонгож хайна уу!'" );
echo "<input type='button' name='search' id='search' value ='Шүүх'/>";
echo "<input type='button' class='search_btn' id='searchbtn' title='Нэмэлт утгаар хайлт хийх.'/>";
// src='".base_url()."images/search2.png'
echo "</fieldset>";
echo "</div>";
echo form_close ();
?>

