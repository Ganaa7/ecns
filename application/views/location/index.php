<div style="margin: 20px 10px;">
	<script>  
   </script>
<?php

if($this->session->userdata('role')=='ADMIN'|| $this->session->userdata('role')=='TECHENG')
echo "<div style='margin-left:10px;'>
  <a class='button' onclick='add_modal()'> Шинэ байршил</a>
</div>";


echo ($location);

?>
</div>


<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Байршил нэмэх')); ?>

<?php echo validation_errors();?>

<p class="feedback"></p>
  
  <div class="field">

    <div class="field" >

      <label for="location_id">Байршил:</label>

      <?php echo form_input('location', null, 'id="location"');?>

    </div>

    <div class="field">

      <label for="section_id">Код:</label>

      <?php echo form_input('code', null, 'id="code"');?>

    </div>

    <div class="field">

      <label for="latitude">Өргөрөг:</label>

       <?php echo form_input('latitude', null, "id='latitude'");?>

    </div>
    
    <div class="field">
    
      <label for="longitude">Уртараг:</label>
      
      <?php echo form_input('longitude', null, "id='longitude'");?>
    
    </div>
    
    
  
  </div>
 <?php echo form_close();?>
<!-- /create form -->


<!-- edit form -->
<?php echo form_open('#', array('name'=>'edit', 'id'=>'edit', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('location_id', null);?> 

<p class="feedback"></p>

 <div class="field">

    <div class="field" >

      <label for="location_id">Байршил:</label>

      <?php echo form_input('location', null, 'id="location"');?>

    </div>

    <div class="field">

      <label for="section_id">Код:</label>

      <?php echo form_input('code', null, 'id="code"');?>

    </div>

    <div class="field">

      <label for="latitude">Өргөрөг:</label>

       <?php echo form_input('latitude', null, "id='latitude'");?>

    </div>
    
    <div class="field">
    
      <label for="longitude">Уртараг:</label>
      
      <?php echo form_input('longitude', null, "id='longitude'");?>
    
    </div>
    
    
  
  </div>
 

<?php echo form_close();?>

<!-- /edit form -->

<!-- view form -->

<?php echo form_open('#', array('name'=>'view', 'id'=>'view', 'title'=>'')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('location_id', null);?> 

<p class="feedback"></p>

 <div class="field">

    <div class="field" >

      <label for="location_id">location:</label>

      <?php echo form_input('location', null, 'id="location"');?>

    </div>

    <div class="field">

      <label for="section_id">Код:</label>

      <?php echo form_input('code', null, 'id="code"');?>

    </div>

    <div class="field">

      <label for="latitude">Өргөрөг:</label>

       <?php echo form_input('latitude', null, "id='latitude'");?>

    </div>
    
    <div class="field">
    
      <label for="longitude">Уртараг:</label>
      
      <?php echo form_input('longitude', null, "id='longitude'");?>
    
    </div>
    
  
  </div>


<?php echo form_close();?>
<!-- /view form -->




