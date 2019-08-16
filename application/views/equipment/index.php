<div style="margin: 20px 10px;">
	<script>  
   </script>
<?php

if($this->session->userdata('role')=='ADMIN'|| $this->session->userdata('role')=='TECHENG')
echo "<div style='margin-left:10px;'>
  <a class='button' onclick='add_modal()'>Шинэ төхөөрөмж нэмэх</a>
</div>";


echo ($equipment_OBJ);

?>
</div>


<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>
  <div class="field">
    <div class="field" >
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>
    <div class="field">
      <label for="section_id">Тасаг:</label>
      <?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id"');?>
    </div>
    <div class="field">
      <label for="equipment_id">Төхөөрөмж:</label>
       <?php echo form_input('equipment', null, "id='equipment_id'");?>
    </div>
    <div class="field">
       <label for="isbn">Гэмтэл дутагдлын код:</label>
       <?=form_input('code', null, "id='code'");  ?>
    </div>
    <div class="field">
       <label for="author">Зориулалт:</label>
       <?=form_input('intend', null, "id='intend'");  ?>
    </div>
    <div class="field">
       <label for="title">Үзүүлэлт:</label>
       <?php
       echo form_textarea(array(
              'name'        => 'spec',
              'id'          => 'spec',              
              'rows'        => '5',
              'cols'        => '35'
              
            ));
       ?>    
    </div>      
    
  <div class="field">
    <label for="">Ашиглалтад авсан он:</label>
    <?=form_input('year_init', null, "id='year_init'");  ?>
  </div>  
  
  </div>
 <?php echo form_close();?>
<!-- /create form -->


<!-- edit form -->
<?php echo form_open('#', array('name'=>'edit', 'id'=>'edit', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
<?php echo validation_errors();?>
<?php echo form_hidden('equipment_id', null);?> 
<p class="feedback"></p>
  <div class="field">
    <div class="field" >
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>
    <div class="field">
      <label for="section_id">Тасаг:</label>
      <?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id"');?>
    </div>
    <div class="field">
      <label for="equipment_id">Төхөөрөмж:</label>
       <?php echo form_input('equipment', null, "id='equipment' style='width:300px'");?>
    </div>
    <div class="field">
       <label for="isbn">Г/Д-ын Код:</label>
       <?=form_input('code', null, "id='code'");  ?>
    </div>
    <div class="field">
       <label for="author">Зориулалт:</label>
       <?=form_input('intend', null, "id='intend'");  ?>
    </div>
    <div class="field">
       <label for="title">Үзүүлэлт:</label>
       <?php
       echo form_textarea(array(
              'name'        => 'spec',
              'id'          => 'spec',              
              'rows'        => '5',
              'cols'        => '35'
              
            ));
       ?>    
    </div>      
    
  <div class="field">
    <label for="">Ашиглалтад авсан он:</label>
    <?=form_input('year_init', null, "id='year_init'");  ?>
  </div>  

  </div>
 
    <?=form_hidden('sp_id', null, "id='sp_id'");  ?>

<?php echo form_close();?>

<!-- /edit form -->

<!-- view form -->

<?php echo form_open('#', array('name'=>'view', 'id'=>'view', 'title'=>'')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('equipment_id', null);?> 
<p class="feedback"></p>
  <div class="field">
    <div class="field" >
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>
    <div class="field">
      <label for="section_id">Тасаг:</label>
      <?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id"');?>
    </div>
    <div class="field">
      <label for="equipment_id">Төхөөрөмж:</label>
       <?php echo form_input('equipment', null, "id='equipment' style='width:300px'");?>
    </div>
    <div class="field">
       <label for="isbn">Г/Д-ын Код:</label>
       <?=form_input('code', null, "id='code'");  ?>
    </div>
    <div class="field">
       <label for="author">Зориулалт:</label>
       <?=form_input('intend', null, "id='intend'");  ?>
    </div>
    <div class="field">
       <label for="title">Үзүүлэлт:</label>
       <?php
       echo form_textarea(array(
              'name'        => 'spec',
              'id'          => 'spec',              
              'rows'        => '5',
              'cols'        => '35'
              
            ));
       ?>    
    </div>      
    
  <div class="field">
    <label for="">Ашиглалтад авсан он:</label>
    <?=form_input('year_init', null, "id='year_init'");  ?>
  </div>  
 
    <?=form_hidden('sp_id', null, "id='sp_id'");  ?>
</div>
<?php echo form_close();?>
<!-- /view form -->




