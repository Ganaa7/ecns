<?php echo $trainer;?>


<?php echo form_open_multipart('#', array('name'=>'create-form', 'id'=>'create-form', 'title'=>'Шинэ хөтөлбөр үүсгэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>    
  
  <div class="field ">

     <label for="number">Овог:</label> 

     <?=form_input('first_name', null, "id='first_name'");?>

  </div>

  <div class="field ">

     <label for="number">Нэр:</label> 

     <?=form_input('last_name', null, "id='last_name'");?>

  </div>  

  <div class="field ">

      <label for="number">Регистр:</label> 

      <?=form_input('register', null, "id='register'");?>

  </div>
  <div class="field ">

      <label for="number">Хүйс:</label> 

      <input type="radio" name="gender" value="Эрэгтэй" checked> Эр<br>
      <input type="radio" name="gender" value="Эмэгтэй"> Эм<br>

  </div>
  
  <div class="field clearfix" style="margin-top: 10px;" >

    <label for="end_dt">Төрсөн огноо:</label> 

    <?=form_input('birthdate', null, "id='birthdate'");?>

  </div>
  <div class="field ">

      <label for="number">Үндэс угсаа:</label> 

      <?=form_input('nationality', null, "id='nationality'");?>      

  </div>

  <div class="field">
  
    <label for="section">Харьяа байгууллага:</label> 
  
    <?php echo form_dropdown('org_id', $organization, null, 'id="org_id" data-placeholder="Нэг утгыг сонго.."');?>
  
  </div>
  

  <div class="field">
    <label for="section">Хэсэг:</label> 
    <?php echo form_dropdown('section_id', $section, null, 'id="section_id" data-placeholder="Хэсгүүдээс сонго.."');?>
  </div>

  <div class="field">

    <label for="equipment">Албан тушаал:</label> 

    <?php echo form_dropdown('position_id', $equipment, null, 'id="position_id" class="chosen-select"  data-placeholder="Албан тушаал сонго.."');?>

  </div>  

  <div class="field">
    <label for="">Холбоо барих утас:</label> 
    <?=form_input('phone', null, "id='phone' size = '30'");?>
  </div>  

  <div class="field">
    <label for="location">Имэйл хаяг:</label> 
    <?=form_input('email', null, "id='email' size = '30'");?>
  </div>  

   <div class="field">
    <label for="location">Холбоо барих хүний утас:</label> 
    
      <?=form_input('rel_phone', null, "id='rel_phone' size = '30'");?>
  </div>  

  <div class="field ">

      <label for="number">Төгссөн сургууль, мэргэжил, зэрэг:</label> 

      <?=form_input('education', null, "id='education'");?>      

  </div>

   <div class="field ">

      <label for="number">Үнэмлэхний дугаар:</label> 

      <?=form_input('education', null, "id='education'");?>      

  </div>

  <div class="field">

    <label for="equipment">Үнэмлэхний төрөл:</label> 

    <?php echo form_dropdown('license_type_id', $license_type, null, 'id="license_type_id" data-placeholder="Үнэмлэхний төрлийг сонго."');?>

  </div> 

  <div class="field ">

      <label for="number">Анх олгосоно огноо:</label> 

      <?=form_input('initial_date', null, "id='initial_date'");?>      

  </div>
  <div class="field ">

      <label for="number">Олгосоно огноо:</label> 

      <?=form_input('issued_date', null, "id='issued_date'");?>      

  </div>
  <div class="field ">

      <label for="number">Хүчинтэй огноо:</label> 

      <?=form_input('valid_date', null, "id='valid_date'");?>      

  </div>
  <div class="field ">

      <label for="number">Дуусах огноо:</label> 

      <?=form_input('expired_date', null, "id='expired_date'");?>      

  </div>

    <div class="field ">

      <label for="number">Ажиллах тоног төхөөрөмж:</label> 

      <?=form_input('license_equipment', null, "id='license_equipment'");?>      

  </div>
 
  
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

