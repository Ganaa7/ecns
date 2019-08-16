<style>
#find {
	margin-right: 30px;
}
</style>
<?php
/*
 * Created by Ganaa
 * 2013-05-28
 * This file shows filter from plugin_model in ecns
 */
$attribute = array (
		'name' => 'find' 
);
echo form_open ( '', $attribute );
echo "<div id ='find' align='right'>";
echo "<input name='field'/>";
echo "<input type='submit' name='find' value ='Хайх'/>";
echo "</div>";
echo form_close ();
// ХЭСЭГ, Төхөөрөмж, Сэлбэгийн аль нэгний нэрийг бичиж хайж болно.
?>