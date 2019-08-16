

<!-- edit trip -->
<?php echo form_open('#', array('name'=>'edit-form', 'id'=>'edit-form', 'title'=>'Томилолт засах')); ?>
	<?php echo validation_errors();?>	
	<p class="feedback"></p>
	<input type="hidden" id ='trip_id' name="trip_id">
	<div class="field">
		<label for="no">Томилолтын №:</label> 
		<?=form_input('trip_no', null, "id='trip_no'");?>
	</div>
	<div class="field" style="width: 500px;">
		<label for="section">Хэсэг:</label> 
		<?php echo form_dropdown('section_id', $section, null, 'id="section_id" multiple="multiple" class="chosen-select"  data-placeholder="Хэсгүүдээс сонго.."');?>
	</div>
	<div class="field">
		<label for="section">Томилолтоор ажиллах ИТА-д:</label> 
		<?php echo form_dropdown('employee_id[]', array(0 =>''), null, 'class="multiselect" multiple id="employee_id_edit"');?>
	</div>
  <div  class="field">
    <label for="location">Маршрут:</label> 
      <!-- <ul id="trip"></ul> -->
      <div id="wrapper-tag">
        
      </div>     
      
    <!-- <span id="tag"></span> -->
  </div>
	<div  class="field">
		<label for="location">Байршил:</label> 
		<?=form_dropdown('location_id', $location, null,  "id='location'");?>
	</div>
	<div  class="field">
		<label for="purpose">Зорилго:</label> 
		<?=form_dropdown('purpose', $purpose, null,  "id='purpose'");?>
	</div>
	<div class="field">
		<label for="end_dt">Тээврийн хэрэгсэл:</label> 
		<?php
		$transport = array('Машин'=>'Машин', 'Онгоц'=>'Онгоц');
		echo form_dropdown('transport', $transport, null, "id='transport'");?>
	</div>
	<div class="field">
		<label for="">Эхлэх огноо:</label> 
		<?=form_input('start_dt', null, "id='start_dt_edit' ");?>
	</div>
	<div class="field">
		<label for="end_dt">Дуусах огноо:</label> 
		<?=form_input('end_dt', null, "id='end_dt_edit'");?>
	</div>
	
<?php echo form_close();?>
</div>


<!-- come dialog -->
<div id="in-form" title="Очсон цаг">
<p class="feedback"></p>
<?php echo validation_errors();?>
<?php echo form_open(); ?>  
	<div class="field">
		<label for="spot">Гарах цэг:</label> 		
		<input type="text" name='from_route' id='from_route' disabled="true">
	</div>	
	<div class="field">
    <label for="action_dt">Очих цэг:</label>    
    <input type="text" name='to_route' id='to_route' disabled="true">
  </div>  
  <div class="field">
		<label for="distance">Замын урт:</label> 		
		<input type="text" name='distance' id='distance' disabled="true"> км
	</div>	  
  <div class="field" style="width: 500px;">
    <label for="section">Гарсан цаг:</label> 
    <input type="text" name="out_dt" id="out_dt" disabled="true">
  </div>
  <!-- энэ цагийг оруулбал шууд очсонд тооцно -->
<!--   <div class="field" style="width: 500px;">
      <label>Очсон эсэх:</label>
      <?php // $is_come = array('Y'=>"Тийм", 'N'=>"Үгүй");
          //echo form_dropdown('is_come', $is_come);
      ?>
  </div> -->
  <div class="field" style="width: 500px;">
		<label for="section">Очсон цаг:</label> 
		<input type="text" name="est_dt" id="est_dt_">
	</div>

  <div class="field" style="width: 500px;">
    <label for="section">Мэдээ өгсөн ИТА:</label> 
    <select name="infoby_id" id="employee_id">
      <option value="0">ИТА</option>
    </select>
  </div>
    
<?php echo form_close();?>
</div>


<!-- edit dialog -->
<div id="edit-spot" title="Чиглэл засах">
<p class="feedback"></p>
<?php echo validation_errors();?>
<?php echo form_open(); ?>  
  <div class="field">
    <label for="spot">Гарах цэг:</label>    
    <input type="text" name='from_route' id='from_route'>
  </div>  
  <div class="field">
    <label for="action_dt">Очих цэг:</label>    
    <input type="text" name='to_route' id='to_route' >
  </div>  
  <div class="field">
    <label for="distance">Замын урт:</label>    
    <input type="text" name='distance' id='distance' disabled="true"> км
  </div>    
  <div class="field" style="width: 500px;">
    <label for="section">Гарсан цаг:</label> 
    <input type="text" name="out_dt" id="out_dt_edit">
  </div>        
  <div class="field" id='wrap_est_dt'  style="width: 500px;">
    <label for="section">Очих цаг:</label> 
    <input type="text" name="est_dt" id="est_dt_edit">
  </div>  
     
  <div class="field" style="width: 500px;">
    <label for="section">Мэдээ өгсөн ИТА:</label> 
    <select name="infoby_id" id="employee_id">
      <option value="0">ИТА</option>
    </select>
  </div>
  <div class="field" style="width: 500px;">
    <label>Тэмдэглэл:</label> 
    <textarea name="comment" id="comment" cols="62" rows="5"></textarea>
  </div>    
<?php echo form_close();?>
</div>
