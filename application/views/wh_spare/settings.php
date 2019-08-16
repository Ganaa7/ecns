	<?php $this->load->view('header', $this->data);?>
<?php echo $settigns;?>

<!-- info -->
<?php $this->load->view('footer');?> 

<!-- create -->
<?php echo form_open('#', array('name'=>'new-form', 'id'=>'new-form', 'title'=>'Шинэ сэлбэг')); ?>
<?php echo validation_errors();?>

<p class="feedback"></p>

	<input type="hidden" id="user_type" value="<?=$user_type;?>">		
	
	<div class="field">
		<label for="section">Сэлбэг:</label> 
		<?php echo form_input('spare', null, "id='spare' size='30'");?>
	</div>
	<div class="field" >
		<label for="section">Хэсэг:</label> 
		<?php echo form_dropdown('section_id', $section, null, 'id="section_id" class="chosen-select"  data-placeholder="Хэсгүүдээс сонго.."');?>
	</div>
	<div class="field" >
		<label for="sector">Тасаг:</label> 
		<?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id" data-placeholder="Хэсгүүдээс сонго.."');?>
	</div>		
	<div class="field">
		<label for="equipment">Төхөөрөмж:</label> 
		<?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id"');?>
	</div>
	
	<div class="field">
		<label for="part_number">Парт №:</label> 
		<?php echo form_input('part_number', null, "id='part_number'");?>
	</div>
	<div class="field">
		<label for="sparetype">Төрөл:</label> 
		<?php echo form_dropdown('type_id', $sparetype, null, 'id="type_id"');?>
	</div>
	<div class="field">
		<label for="measure">Хэмжих нэгж:</label> 
		<?php echo form_dropdown('measure_id', $measure, null, 'id="measure_id"');?>
	</div>	    
	<div class="field">
		<label for="manufacture_id">Үйлдвэрлэгч:</label> 
		<?php echo form_dropdown('manufacture_id', $manufacture, null, 'id="manufacture_id"');?>
	</div>	  
<!-- 	<div class="field">
		<label for="section">Ашиглалтад байгаа тоо/ш:</label> 
		<?php //echo form_input('required_qty', null, "id='required_qty' size='30'");?>
	</div>	 -->
<?php echo form_close();?>
<!-- edit -->

<?php echo form_open('#', array('name'=>'edit-form', 'id'=>'edit-form', 'title'=>'Сэлбэг засах')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>		
	<div class="field" style="width: 500px;">
		<label for="section">Сэлбэг:</label> 
		<?php echo form_input('spare', null, "id='spare' size='30'");?>
	</div>
	<div class="field" style="width: 500px;">
		<label for="section">Хэсэг:</label> 
		<?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
	</div>

	<div class="field" >
		<label for="sector">Тасаг:</label> 
		<?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id" data-placeholder="Хэсгүүдээс сонго.."');?>
	</div>	

	<div class="field">
		<label for="equipment">Төхөөрөмж:</label> 
		<?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id"');?>
	</div>		    
		
	<div class="field">
		<label for="part_number">Парт дугаар:</label> 
		<?php echo form_input('part_number', null, "id='part_number'");?>
	</div>
	<div class="field">
		<label for="sparetype">Төрөл:</label> 
		<?php echo form_dropdown('type_id', $sparetype, null, 'id="type_id"');?>
	</div>
	<div class="field">
		<label for="measure">Хэмжих нэгж:</label> 
		<?php echo form_dropdown('measure_id', $measure, null, 'id="measure_id"');?>
	</div>	    
	<div class="field">
		<label for="manufacture_id">Үйлдвэрлэгч:</label> 
		<?php echo form_dropdown('manufacture_id', $manufacture, null, 'id="manufacture_id"');?>
	</div>	
<!-- 	<div class="field">
		<label for="section">Ашиглалтад байгаа тоо/ш:</label> 
		<?php //echo form_input('required_qty', null, "id='required_qty' size='30'");?>
	</div>	  	 -->
<?php echo form_close();?>
<!-- delete -->




