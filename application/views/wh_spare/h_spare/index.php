<?php echo $spare;?>

<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Алслагдсан обьектын үлдэгдэл сэлбэг нэмэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>      
  <div class="field">
    <label for="">Нэмсэн огноо:</label> 
    <?=form_input('date', null, "id='add_date' ");?>
  </div>
  <div class="field">
    <label for="section">Сэлбэг:</label> 
    <?php
    echo form_input('spare', null, "id='spare'");
    // echo form_dropdown('spare_id', $spares, null, 'id="section_id" class="chosen-select"  data-placeholder="Сэлбэгээс сонго.."');?>
    <?php echo form_hidden('spare_id', null);?>
  </div>  
  <div class="field">
    <label for="title">Үлдэгдэл тоо:</label>
    <input type="text" value="" name="qty" id="qty">
  </div>        
  <div class="field" >
    <table id='site_wrapper'>
      <tr>
        <td align="left">Байршил:</td><td align="left">Сериал:</td><td align="left" colspan="2">Баркод:</td>        
      </tr>
      <tr>
        <td><?=form_dropdown('site_id[]', $site, null, "id='site'");?></td>        
        <td><input type="text" value="" name="serial[]"></td>
        <td><input type="text" value="" name="barcode[]"></td>
        <!-- <td>[<a href="#" id='add_site' title="Шинэ байршил дээр сэлбэгийн тоог нэмэх бол энд дар!">+</a>]</td> -->
      </tr>
    </table>
  </div>  
<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'using', 'id'=>'using', 'title'=>'Ашиглагдаж буй тоо/ширхэг нэмэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>      
 <div class="field">
    <label for="section">Сэлбэг:</label> 
    <?php
    echo form_input('spare', null, "id='spare'"); ?>
  </div>    
    
  <div class="field">
    <label for="">Нэмсэн огноо:</label> 
    <?=form_input('date', null, "id='using_date' ");?>
  </div>  
  <div class="field">
    <label for="title">Ашиглагдаж буй тоо/ш тоо:</label>
    <input type="text" value="" name="using_qty" id="using_qty">
  </div>          
  <input type="hidden" name="spare_id">
<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'need', 'id'=>'need', 'title'=>'Сэлбэгэнд байх ёстой тоо/ширхэг нэмэх')); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>      
 <div class="field">
    <label for="section">Сэлбэг:</label> 
    <?php
    echo form_input('spare', null, "id='spare'"); ?>
  </div>    
    
  <div class="field">
    <label for="">Нэмсэн огноо:</label> 
    <?=form_input('date', null, "id='need_date' ");?>
  </div>  
  <div class="field">
    <label for="title">Сэлбэгэнд байх ёстой тоо/ш:</label>
    <input type="text" value="" name="need_qty" id="need_qty">
  </div>          
  <input type="hidden" name="spare_id">
<?php echo form_close();?>


<div id="dialog" title="Сэлбэгийн модулийн өөрчлөлт:Тусламж">
  <p>
    Сэлбэгийн нэршилийг ИНД 171.79-ын 6.4-ын дагуу өөрчиллөө.
  </p>
  <p style="padding-top: 3px;">
    Үүнд: 
    
    <ul>
      <ol><strong>Бэлэн</strong> гэснийг <strong>Алслагдсан обьект үлдэгдэл</strong> болгож</ol>
      <ol><strong>Ашиглалтад байгаа</strong> гэснийг  <strong>Ашиглагдаж буй</strong> болгож </ol>            
    </ul>
    тус тус өөрчиллөө.
    <br>

    Мөн шинээр: 
    <br>

    <ul>
      <ol><strong>Сэлбэгэнд байх ёстой тоо/ш</strong> хэсгийн нэмж орууллаа!</ol>
    </ul>

    <span class="error">Жич: Тохиргоо цэсний Сэлбэг нэршил дээр <strong>Ашиглалтад байгаа тоо/ш</strong> хэсгийг энэ Сэлбэг хэсэгт <strong>Ашиглагдаж буй тоо/ш бүртгэх </strong> гэж өөрчиллөө!</span>
     

  </div>
</div>