<?php echo $guidance;?>
<style>
    #custom-handle {
    width: 3em;
    height: 1.6em;
    top: 50%;
    margin-top: -.8em;
    text-align: center;
    line-height: 1.6em;
  }   
   #custom-handle-2 {
    width: 3em;
    height: 1.6em;
    top: 50%;
    margin-top: -.8em;
    text-align: center;
    line-height: 1.6em;
  }
</style>

<?php echo form_open_multipart('#', array('name'=>'create-form', 'id'=>'create-form', 'title'=>'Шинэ хөтөлбөр үүсгэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>    
  <div class="field ">
    <label for="number">Дугаар:</label> 
    <?=form_input('number', null, "id='number'");?>
  </div>
  <div class="field">
    <label for="section">Хэсэг:</label> 
    <?php echo form_dropdown('section_id', $section, null, 'id="section_id" data-placeholder="Хэсгүүдээс сонго.."');?>
  </div>
  <div class="field">
    <label for="equipment">Төхөөрөмж:</label> 
    <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id" class="chosen-select"  data-placeholder="Төхөөрөмж сонго.."');?>
  </div>  
  <div class="field">
    <label for="">Хөтөлбөр:</label> 
    <?=form_input('guidance', null, "id='guidance' class='guidance' size = '30'");?>
  </div>  
  <div  class="field">
    <label for="location">Болох газар:</label> 
    <?=form_dropdown('location', $location, null, "id='location'");?>
  </div>  
  <div class="field">
    <label for="end_dt">Хичээлийн цаг минут:</label> 
    <div id="slider" style="width: 300px; float:right; margin-top: 10px; margin-right: 20px;">
      <div id="custom-handle" class="ui-slider-handle"></div>
   </div>
   <div id="slider2" style="width: 300px; float:right; margin-top: 10px; margin-right: 20px;">
      <div id="custom-handle-2" class="ui-slider-handle"></div>
   </div>
    <?=form_hidden('hours', null);?> 
    <?=form_hidden('minute', null);?> 
  </div>
  <div class="field clearfix" style="margin-top: 10px;" >
    <label for="end_dt">Батлагдсан огноо:</label> 
    <?=form_input('date', null, "id='date_dt'");?>
  </div>
  <div class="field" id="file_wrap">
      <Label>Хөтөлбөрийн файл:</Label> <input type="file" name="userfile"  value="" id="_file">
  </div>
  <?=form_hidden('file_id', null);?>  
<?php echo form_close();?>

 
<?php echo form_open('#', array('name'=>'view-form', 'id'=>'view-form', 'title'=>'Хөтөлбөр')); ?>

<?php echo validation_errors();?>

<p class="feedback"></p>    
<div class="field ">
    <label for="number">Дугаар:</label> 
    <?=form_input('number', null, "id='number'");?>
  </div>

  <div class="field" style="width: 500px;">
    <label for="section">Хэсэг:</label> 

    <?php echo form_dropdown('section_id', $section, null, 'id="section_id" class="chosen-select"  data-placeholder="Хэсгүүдээс сонго.."');?>
  </div>

  <div class="field">

    <label for="equipment">Төхөөрөмж:</label> 

    <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id" class="chosen-select"  data-placeholder="Төхөөрөмж сонго.."');?>

  </div>  

  <div class="field">
    <label for="">Хөтөлбөр:</label> 
    <?=form_input('guidance', null, "id='guidance' class='guidance'");?>
  </div>  
  <div  class="field">
    <label for="location">Болох газар:</label> 
    <?=form_dropdown('location_id', $location, null, "id='location'");?>
  </div>  
  <div class="field">
    <label for="end_dt">Хичээлийн цаг:</label> 
    <?=form_input('hours', null, "id='hours'");?>
  </div>
  <div class="field">
    <label for="end_dt">Батлагдсан огноо:</label> 
    <?=form_input('date', null, "id='date'");?>
  </div> 

  
    <div class="field" id="_file">

    <label for="end_dt">Хөтөлбөрийн файл:</label> 

      <span id='file_link'><a href='#' target='_blank' style='color:blue'> Файл байхгүй байна</a></span>      

    </div>
  
  
<?php echo form_close();?>


 
<?php echo form_open('#', array('name'=>'edit-form', 'id'=>'edit-form', 'title'=>'Хөтөлбөр засах')); ?>


<?php echo validation_errors();?>

<p class="feedback"></p>    

 <?=form_hidden('id', null, array('id'=>'id'));?>  

 <div class="field ">
    <label for="number">Дугаар:</label> 
    <?=form_input('number', null, "id='number'");?>
  </div>

  <div class="field" style="width: 500px;">
    <label for="section">Хэсэг:</label> 

    <?php echo form_dropdown('section_id', $section, null, 'id="section_id" class="chosen-select"  data-placeholder="Хэсгүүдээс сонго.."');?>
  </div>

  <div class="field">

    <label for="equipment">Төхөөрөмж:</label> 

    <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id" class="chosen-select"  data-placeholder="Төхөөрөмж сонго.."');?>

  </div>  

  <div class="field">
    <label for="">Хөтөлбөр:</label> 
    <?=form_input('guidance', null, "id='guidance' class='guidance'");?>
  </div>  
  <div  class="field">
    <label for="location">Болох газар:</label> 
    <?=form_dropdown('location', $location, null, "id='location'");?>
  </div>  

  <div class="field clearfix">
      <label for="end_dt">Хичээлийн цаг минут:</label> 
        <div id="slider" style="width: 300px; float:right; margin-top: 10px; margin-right: 20px;">
        <div id="custom-handle" class="ui-slider-handle"></div>
      </div>
        <div id="slider2" style="width: 300px; float:right; margin-top: 10px; margin-right: 20px;">
        <div id="custom-handle-2" class="ui-slider-handle"></div>
      </div>
        <?=form_hidden('hours', null);?> 
        <?=form_hidden('minute', null);?> 
  </div>
  <div class="field">
    <label for="end_dt">Батлагдсан огноо:</label> 
    <?=form_input('date', null, "id='date'");?>
  </div> 

  
  <div class="field" id="file_wrap" >
      <Label>Хөтөлбөрийн файл:</Label> <input type="file" name="userfile"  value="" id="_file">
  </div>
  
  
<?php echo form_close();?>

