<?=$library_src; ?>
<style>
iframe {
	width: 100%;
	height: 100%;
}

p.success {
	background: rgba(64, 211, 64, 0.97);
}
</style>

<div id="main_wrap" style="margin: 20px auto; width: 1260px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div> 
   <?php
    foreach ( $out->action as $key => $value ) {
        echo "<input type='hidden' name='actions' class='action' value='$value'/>";
    }
   ?>
 <input type="hidden" name='sec_code' id="sec_code"
		value="<?=$out->sec_code;?>"> <input type="hidden" name='role'
		id="role" value="<?=$out->role;?>">
</div>

<!-- Тазын шинээр нэмэх мэдээллийг харуулна-->
<?php echo form_open_multipart('ecns/index/manual/upload_file/', 'id="new_form"'); ?>
	<p class="feedback"></p>
    <input type="hidden" name='_id' id='_id'>
    <div class="field">
        <label for="section">Хэсэг:</label>
        <?php echo form_dropdown('section_id',$section, null, 'class="text ui-widget-content ui-corner-all" id="section_id"')?>

    </div>
	<div class="field">
		<label for="location">Тоног төхөөрөмж:</label>
        <?php echo form_dropdown('equipment_id',$equipment, null, 'class="text ui-widget-content ui-corner-all" id="equipment_id"')?>

	</div>
    <div class="field">
        <label for="equipment">Техник ашиглалтын заавар:</label>
        <input type="text" size="40" name="manual" id="manual" class="text ui-widget-content ui-corner-all">
    </div>
    <div class="field">
        <label for="doc_index">ТАЗ-ын дугаар:</label>
        <input type="text"	name="doc_index" id="doc_index"	class="text ui-widget-content ui-corner-all" >
    </div>
	<div class="field">
		<label>Батлагдсан огноо:</label>
        <input type='text' name='update_date' id='update_dated'/>
<!--        <input type='text' name='start_dt' id="start_dt"-->
	</div>
    <div class="field" id='_file'>
        <Label>Файл:</Label>
        <input type="file" name="userfile" value="" id="manual_file" style="display: inline-block;">
        <input type="hidden" name='uploaded_file' id='uploaded_file'>
    </div>
</form>
<!-- Тазын ерөнхий диалогийг харуулна-->
<form id="page_dialog" action="" method="POST">
	<p class="feedback"></p>
    <div class="field">
        <label for="equipment">Техник ашиглалтын заавар:</label>
        <input type="text" size="40" name="manual" id="manual" class="text ui-widget-content ui-corner-all">
    </div>
	<div class="field">
		<label for="location">Тоног төхөөрөмж:</label>
        <input type="text" size="40" name="equipment" id="equipment"	class="text ui-widget-content ui-corner-all">
	</div>
    <div class="field">
        <label for="doc_index">ТАЗ-ын дугаар:</label>
        <input type="text"	name="doc_index" id="doc_index"	class="text ui-widget-content ui-corner-all" disabled>
    </div>
	<div class="field">
		<label>Батлагдсан огноо:</label> <span id="update_date"></span>
	</div>
	<div class="field" id='_file'>
		<Label>Файл:</Label>
	</div>
</form>

<!-- EDIT DIALOG -->
<?php echo form_open_multipart('index', 'id="edit_dialog"'); ?>
<!-- <form id="edit_dialog" action="" method="POST"> -->
<p class="feedback"></p>
<input type="hidden" name='id' id='id'>
<div class="field">
    <label for="section_id">Хэсэг:</label>
    <?php echo form_dropdown('section_id',$section, null, 'class="text ui-widget-content ui-corner-all" id="section_id"')?>
</div>
<div class="field">
    <label for="equipment_id">Тоног төхөөрөмж:</label>
    <?php echo form_dropdown('equipment_id',$equipment, null, 'class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
</div>
<div class="field">
    <label for="equipment">Техник ашиглалтын заавар:</label>
    <input
            type="text" size="30" name="manual" id="manual"
            class="text ui-widget-content ui-corner-all">
</div>
<div class="field">
    <label for="doc_index">ТАЗ-ын дугаар:</label>
    <input type="text"	name="doc_index" id="doc_index"	class="text ui-widget-content ui-corner-all">
</div>
<div class="field">
    <label>Батлагдсан огноо:</label>
    <input type="text" name="update_date" id="edit_update_date"/>
</div>
<div class="field" id='_file'>

    <Label>Файл:</Label>
    <input type="file" name="userfile" value="" id="manual_file" style="display: inline-block;">
</div>
<input type="hidden" name='uploaded_file' id='uploaded_file'>
</form>
