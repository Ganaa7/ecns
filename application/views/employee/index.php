<div style="margin: 20px 10px;">
	<script>  
   </script>
<?php


echo ($equipment_OBJ);

?>
</div>


<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'ИТА нэмэх')); ?>
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

      <label for="position_id">Албан тушаал:</label>

      <?php echo form_dropdown('position_id', $position, null, 'id="position_id"');?>

    </div>

    <div class="field">

      <label for="first_name">Нэр:</label>

       <?php echo form_input('first_name', null, "id='first_name'");?>

    </div>
    
    <div class="field">
    
      <label for="last_name">Эцэг эхийн нэр:</label>
      
      <?php echo form_input('last_name', null, "id='last_name'");?>
    
    </div>
    
    <div class="field">

       <label for="isbn">Нэвтрэх нэр:</label>

       <?=form_input('username', null, "id='username'");  ?>@mcaa.gov.mn
       
    </div>

    <div class="field">

       <label for="isbn">Нууц үг:</label>

       <?=form_password('password', null, "id='password'");  ?>
       
    </div>


    <div class="field">

       <label for="author">Нууц үг давтах:</label>

       <?=form_password('re_password', null, "id='re_password'");  ?>

    </div>

  
  </div>
 <?php echo form_close();?>
<!-- /create form -->


<!-- edit form -->
<?php echo form_open('#', array('name'=>'edit', 'id'=>'edit', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('equipment_id', null);?> 

<p class="feedback"></p>

  <div>

    <div class="field" >

      <label for="section_id">Хэсэг:</label>

      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>

    </div>

    <div class="field">

      <label for="section_id">Тасаг:</label>

      <?php echo form_dropdown('sector_id', $sector, null, 'id="sector_id"');?>

    </div>

    <div class="field">

      <label for="position_id">Албан тушаал:</label>

      <?php echo form_dropdown('position_id', $position, null, 'id="position_id"');?>

    </div>

    <div class="field">

      <label for="first_name">Нэр:</label>

       <?php echo form_input('first_name', null, "id='first_name'");?>

    </div>
    
    <div class="field">
    
      <label for="last_name">Эцэг эхийн нэр:</label>
      
      <?php echo form_input('last_name', null, "id='last_name'");?>
    
    </div>
    
    <div class="field">

       <label for="isbn">Нэвтрэх нэр:</label>

       <?=form_input('username', null, "id='username'");  ?>@mcaa.gov.mn
       
    </div>

    <div class="field">

       <label for="check_pass">Нууц үгийг солих уу?:</label>

       Тийм<?=form_checkbox('is_change', 1, FALSE, "id='check_pass'");  ?>
       
    </div>

    <div class="field">

       <label for="isbn">Нууц үг:</label>

       <?=form_password('password', null, "id='password'");  ?>
       
    </div>


    <div class="field">

       <label for="author">Нууц үг давтах:</label>

       <?=form_password('re_password', null, "id='re_password'");  ?>

    </div>

    <?=form_hidden('employee_id', null, "id='employee_id'");  ?>

  </div>
 

<?php echo form_close();?>

<!-- /edit form -->

<!-- view form -->

<?php echo form_open('#', array('name'=>'view', 'id'=>'view', 'title'=>'')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('employee_id', null);?> 

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

      <label for="first_name">Нэр:</label>

       <?php echo form_input('first_name', null, "id='first_name'");?>

    </div>
    
    <div class="field">
    
      <label for="last_name">Эцэг эхийн нэр:</label>
      
      <?php echo form_input('last_name', null, "id='last_name'");?>
    
    </div>
    
    <div class="field">

       <label for="isbn">Нэвтрэх нэр:</label>

       <?=form_input('username', null, "id='username'");  ?>@mcaa.gov.mn
       
    </div>

 

</div>
<?php echo form_close();?>
<!-- /view form -->




