
<?php
echo $plan;
?>

<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Шинээр төлөвлөгөө нэмэх')); ?>
<?php // echo form_open_multipart('/library/upload_file/', 'id="create" title="Шинээр ном нэмэх"'); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>  
    <div class="field" style="width: 500px;">
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>

  <div class="field">

    <label for="isbn">Гүйцэтгэл:</label>

    <?=form_textarea(  array(
                        'name'        => 'work',
                        'id'          => 'work',                        
                        'rows'        => '10',
                        'cols'        => '10',
                        'style'       => 'width:50%',
                      ));  ?>

  </div>      
  
  <div class="field">
    <label for="author">Гүйцэтгэх хугацаа:</label>
    <?=form_input('date', null, "id='date'");  ?>
  </div>


<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'add_detail', 'id'=>'add_detail', 'title'=>'Хэрэгжүүлэх ажил нэмэх')); ?>

<?php echo validation_errors();?>

<p class="feedback"></p>  
    
    <div class="field" style="width: 500px;">
      <label for="input">Дугаар:</label>
        <?=form_input('number', null, "id='number'");  ?>
    </div>


    <div class="field" style="width: 500px;">
      
      <label for="detail">Хэрэгжүүлэх арга хэмжээ:</label>

          <?=form_textarea(  array(
                        'name'        => 'detail',
                        'id'          => 'detail',                        
                        'rows'        => '4',
                        'cols'        => '10',
                        'style'       => 'width:90%',
                      ));  ?>
        
    </div>    

  <div class="field">
    <label for="section">Хариуцсан ажилтан:</label> 
    <?php echo form_dropdown('employee_id[]', $employee, null, 'class="multiselect" multiple="multiple" id="employee_id"');?>
  </div>


<?php echo form_close();?>


<?php echo form_open('#', array('name'=>'completion', 'id'=>'completion', 'title'=>'Хэрэгжүүлэх ажил нэмэх')); ?>

<?php echo validation_errors();?>

<p class="feedback"></p>  


    <div class="field" style="width: 500px;">
      
      <label for="detail">Гүйцэтгэл:</label>

          <?=form_textarea(  array(
                        'name'        => 'completion',
                        'id'          => 'completion',                        
                        'rows'        => '4',
                        'cols'        => '10',
                        'style'       => 'width:90%',
                      ));  ?>
        
    </div>    

    <div class="field" style="width: 500px;">
    
      <label for="input">Биелэлт/%/:</label>
    
        <?=form_input('percent', null, "id='percent'");  ?>
    
    </div>


<?php echo form_close();?>



<?php echo form_open('#', array('name'=>'edit', 'id'=>'edit', 'title'=>'Засах:')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('id', null);?> 

<p class="feedback"></p>

   <div class="field" style="width: 500px;">
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>

  <div class="field">

    <label for="isbn">Төлөвлөсөн ажил:</label>

    <?=form_textarea(  array(
                        'name'        => 'work',
                        'id'          => 'work',                        
                        'rows'        => '10',
                        'cols'        => '10',
                        'style'       => 'width:50%',
                      ));  ?>

  </div>      
  
  <div class="field">
    <label for="author">Гүйцэтгэх хугацаа:</label>
    <?=form_input('date', null, "id='date'");  ?>
  </div>

<?php echo form_close();?>


<?php echo form_open('#', array('name'=>'edit_dtl', 'id'=>'edit_dtl', 'title'=>'Засах:')); ?>

<?php echo form_hidden('id', null);?> 

<p class="feedback"></p>

    <div class="field" style="width: 500px;">
   
      <label for="input">Дугаар:</label>
   
        <?=form_input('number', null, "id='number'");  ?>
    </div>

    <div class="field" style="width: 500px;">
      
      <label for="detail">Хэрэгжүүлэх арга хэмжээ:</label>

          <?=form_textarea(  array(
                        'name'        => 'detail',
                        'id'          => 'detail',                        
                        'rows'        => '4',
                        'cols'        => '10',
                        'style'       => 'width:90%',
                      ));  ?>
        
    </div>    

  <div class="field">

    <label for="section">Хариуцсан ажилтан:</label> 

    <?php echo form_dropdown('employee_id[]', $employee, null, 'class="multiselect" multiple="multiple" id="employee_edit_id"');?>

  </div>


  <div class="field" style="width: 500px;">
      
     <label for="detail">Гүйцэтгэл:</label>

       <?=form_textarea(  array(
            'name'        => 'completion',
            'id'          => 'completion',                        
            'rows'        => '4',
            'cols'        => '10',
            'style'       => 'width:90%',
          ));  ?>
  
    </div>    

    <div class="field" style="width: 500px;">
    
      <label for="input">Биелэлт/%/:</label>
    
        <?=form_input('percent', null, "id='percent'");  ?>
    
    </div>

<?php echo form_close();?>
