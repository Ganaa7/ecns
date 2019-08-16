<?=$library_src; ?>
<? //=var_dump($out)?>
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

	<div id="pager" class="scroll" style="text-align: center;">
		
	</div> 
   <?php
			foreach ( $out->action as $key => $value ) {
				echo "<input type='hidden' name='actions' class='action' value='$value'/>";
			}
			?>
 <input type="hidden" name='sec_code' id="sec_code"
		value="<?=$out->sec_code;?>"> <input type="hidden" name='role'
		id="role" value="<?=$out->role;?>">
</div>

<form id="page_dialog" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="cert_no">Гэрчилгээний дугаар:</label> <input type="text"
			name="cert_no" id="cert_no"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="location">Байршил:</label> <input type="text"
			name="location" id="location"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="equipment">Тоног төхөөрөмжийн маяг загвар:</label> <input
			type="text" size="30" name="equipment" id="equipment"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="serial_no_year">Үйлдвэрийн болон сери дугаар:</label> <input
			type="text" name="serial_no_year" id="serial_no_year"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="intend">Зориулалт:</label>
		<textarea name="intend" id="intend" cols="30" rows="5" disabled></textarea>
	</div>
	<div class="field">
		<label for="">Олгосон он-сар-өдөр:</label> <span id="issueddate"></span>
	</div>
	<div class="field">
		<label>Хүчинтэй хугацаа:</label> <span id="validdate"></span>
	</div>
	<div class="field" id='_file'>
		<Label>Гэрчилгээний файл:</Label>
	</div>
</form>

<!-- EDIT DIALOG -->
<?php echo form_open_multipart('ecns/certificate/upload_file/', 'id="edit_dialog"'); ?>
<!-- <form id="edit_dialog" action="" method="POST"> -->
<p class="feedback"></p>
<input type="hidden" name='cert_id' id='cert_id'>
<div class="field">
	<label for="cert_no">Гэрчилгээний дугаар:</label> <input type="text"
		name="cert_no" id="cert_no"
		class="text ui-widget-content ui-corner-all">
</div>
<div class="field">
	<label for="location">Байршил:</label>      
      <?=form_dropdown('location_id',$out->location, null, 'style="width:250px;" id="location_id"')?>
    </div>
<div class="field">
	<label for="equipment">Тоног төхөөрөмжийн маяг загвар:</label>
	<!--  <input type="text" size="30" name="equipment" id ="equipment" class="text ui-widget-content ui-corner-all"> -->
      <?=form_dropdown('equipment_id',$out->equipment, null, 'style="width:250px;" id="equipment_id"')?>
    </div>
<div class="field">
	<label for="serial_no_year">Үйлдвэрийн болон сери дугаар:</label> <input
		type="text" name="serial_no_year" id="serial_no_year"
		class="text ui-widget-content ui-corner-all">
</div>
<div class="field">
	<label>Зориулалт:</label>
	<textarea name="intend" id="intend_2" cols="30" rows="5"></textarea>
</div>
<div class="field">
	<label>Олгосон он-сар-өдөр:</label> <input type="text"
		name="issueddate" class="edit_date">
</div>
<div class="field">
	<label>Хүчинтэй хугацаа:</label> <input type="text" name="validdate"
		class="edit_date">
</div>
<div class="field" id='_file'>
	<Label>Гэрчилгээний файл:</Label> <input type="file" name="userfile"
		value="" id="cert_file">
</div>
</form>

<!-- sunsleg erkh medel -->
<form id="license_dialog" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="location">Байршил:</label> <input type="text"
			name="location" id="location"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="equipment">Тоног төхөөрөмжийн маяг загвар:</label> <input
			type="text" size="30" name="equipment" id="equipment"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<textarea name="license" id="" cols="30" rows="10"></textarea>
	</div>

</form>