
<?php
echo $library;
?>

<?php //echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Шинээр ном нэмэх')); ?>
<?php echo form_open_multipart('/library/upload_file/', 'id="create" title="Шинээр ном нэмэх"'); ?>
<?php echo validation_errors();?>
<p class="feedback"></p>
  <div class="field">
    <div class="field" style="width: 500px;">
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>
    <div class="field">
      <label for="equipment_id">Төхөөрөмж:</label>
      <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id"');?>
    </div>
   <div>
    <label for="title">Номын гарчиг:</label>
    <?php
    echo form_input('title', null, "id='title'");
    ?>    
  </div>
  <div class="field">
    <label for="author">Зохиогч:</label>
    <?=form_input('author', null, "id='author'");  ?>
  </div>
  <div class="field">
    <label for="isbn">ISBN:</label>
    <?=form_input('isbn', null, "id='isbn'");  ?>
  </div>
  <div class="field">
    <label for="year_of_pub">Хэвлэгдсэн Он:</label>
    <?=form_input('year_of_pub', null, "id='year_of_pub'");  ?>
  </div>
  <div class="field" id='_file'>
    <label>Цахим номын файл:</label>
       <input type="file" name="userfile"  value="" id="userfile" >
  </div>
  <input type="hidden" name="ebook" value="" id="ebook">
<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'edit', 'id'=>'edit', 'title'=>'Засах:')); ?>
<?php echo validation_errors();?>
<?php echo form_hidden('id', null);?> 
<p class="feedback"></p>

  <div class="field">
    <div class="field" style="width: 500px;">
      <label for="section_id">Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>
    </div>
    <div class="field">
      <label for="equipment_id">Төхөөрөмж:</label>
      <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id"');?>
    </div>
   <div>
    <label for="title">Номын гарчиг:</label>
    <?php
    echo form_input('title', null, "id='title'");
    ?>
    
  </div>
  <div class="field">
    <label for="author">Зохиогч:</label>
    <?=form_input('author', null, "id='author'");  ?>
  </div>
  <div class="field">
    <label for="isbn">ISBN:</label>
    <?=form_input('isbn', null, "id='isbn'");  ?>
  </div>
  <div class="field">
    <label for="year_of_pub">Хэвлэгдсэн Он:</label>
    <?=form_input('year_of_pub', null, "id='year_of_pub'");  ?>
  </div>
  <div class="field" id='_file'>
    <label>Цахим номын файл:</label>
       <input type="file" name="userfile"  value="" id="userfile" >
  </div>
  <input type="hidden" name="ebook" value="" id="ebook">
<?php echo form_close();?>
