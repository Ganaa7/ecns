<script src="<?=base_url();?>assets/js/timepicker/jquery-ui-timepicker-addon.js"
  type="text/javascript"></script>

<script src="<?=base_url();?>assets/treeview/js/jquery.tree_log.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/treeview/js/jquery.cookie.js" type="text/javascript"></script>

<script src="<?=base_url();?>assets/js/timepicker/jquery-ui-timepicker-addon.js"
  type="text/javascript"></script>

<link rel="stylesheet" type="text/css"
  href="<? echo base_url();?>assets/treeview/css/jquery.treeview.css"/>

<link rel="stylesheet" type="text/css"
  href="<? echo base_url();?>assets/treeview/css/screen.css">

<!-- choosen here -->
<link rel="stylesheet"
  href="<? echo base_url();?>assets/chosen/chosen.css">

<script src="<?=base_url();?>assets/chosen/chosen.jquery.js" type="text/javascript"></script>

<!-- choosen end here -->
<link rel="stylesheet" type="text/css"
  href="<? echo base_url();?>assets/ftree/style.css">


<div style="margin: 20px 10px;">
  
  <style>
    .field{
      padding:3px 0;
    }

    .field input{
      width: 20em;
    }
  </style>
  
  <?php
  
  echo ($equipment_OBJ);
  ?>

</div>


<?php echo form_open('#', array('name'=>'pass', 'id'=>'pass', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', null, 'id="device_id"');?>

<p class="feedback"></p>

  <div class="field">
    
    <div class="field">
    
      <label for="section_id">Байршил:</label>
    
      <?php echo form_dropdown('location_id', $location, null, 'id="location_id" disabled');?>
    
    </div>

    <div class="field" >
    
      <label for="section_id">Хэсэг:</label>
    
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id" disabled');?>
    
    </div>
    
    <div class="field">
    
      <label for="equipment_id">Төхөөрөмж:</label>
    
       <?php echo form_dropdown('equipment', $equipment, null, "id='equipment_id'");?>
    
    </div>

    <div class="field">
    
      <label for="equipment_id">Пасспорт №:</label>
    
       <?php echo form_input('passport_no', null, "id='passport_no'");?>
    
    </div>
    
  
  </div>
 <?php echo form_close();?>
<!-- /create form -->

