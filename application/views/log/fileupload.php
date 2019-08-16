<? $this->load->view('header');?> 
<?php

$attributes = array (
		'class' => 'constrained',
		'id' => 'closelog' 
);
// echo form_open('shiftlog/fileupload/'.$log_id, $attributes);
echo form_open_multipart ( 'log/fileupload/' . $log_id );
?>       

    <?php
				
foreach ( $cols as $row ) {
					?> 
    <?=form_fieldset('Гэмтэлд тайлангийн файл хавсаргах')?>
    <?=form_hidden('log_id', $row->log_id)?>
<p class='error'>Крилл нэртэй файл хавсаргаж болохгүйг анхаарна уу!
	Файлын нэрийг Англи галигаар өгнө үү!</p>
<div>
       <?
					if (isset ( $row->filename )) {
						echo "<label>Хавсаргах файл аль хэдийн сонгосон байна.</label>";
						echo "<label>$row->filename</label>";
					} else {
						// Хавсаргах хэсэг энд байх ёстой...
						echo "<label>Хавсаргах файл сонго:</label>";
						echo form_upload ( 'userfile' );
					}
					?>
      
   </div>
<div>
	<label>Гэмтлийн дугаар:</label><?php
					
if (isset ( $row->log_num ))
						echo "<label>$row->log_num</label>";
					else
						echo "<label>Логийг идэвхижүүлээгүй байна</label>";
					?>
         <p class="small indent">Засварлах гэмтлийн дугаар</p>
</div>
<div>         
      <?=form_label('Нээсэн огноо цаг:мин', 'created_datetime')?>   
      <?=form_label($row->created_datetime);?>
   </div>
<div>         
      <?=form_label('Нээсэн ИТА', 'engineer')?>   
      <?=form_label($row->createdby);?>
   </div>
<!-- Байрлал -->
<div>
         <?php
					echo "<label>Байрлал:</label>";
					?>
         <?=form_label($row->location, 'Улаанбаатар');//$row->createdby);  ?>
   </div>
<!-- гэмтсэн төхөөрөмж -->
<div>
      <?
					echo "<p><label for='parent'>Тоног төхөөрөмж:</label>";
					?>
          <?=form_label($row->equipment, 'Тест');//$row->equipment_id)) ?>
          <?=form_hidden('equipment_id', $row->equipment_id);?>
          <?php
					
echo "</p>";
					?>
   </div>

<!-- Гэмтэл-->
<div><?=form_label('Гэмтэл', 'defect')?>   
      <span class='vars'>
          <?=form_label($row->defect,'Тест');//$row->equipment_id)) ?>
      </span>
</div>

<!-- Шалтгаан-->
<div><?=form_label('Шалтгаан', 'reason')?>   
      <span class='vars'>
          <?=form_label($row->reason,'Тест');//$row->equipment_id)) ?>
      </span>
</div>

<!-- Шалтгаан-->
<div><?=form_label('Засварын явц', 'process')?>   
      <span class='vars'>       
       <?php if(isset($row->process)){?>
           <?=form_label($row->process,'Тест');//$row->equipment_id)) ?>
       <?php }else echo form_label('Тодорхойгүй байна','Тест');//$row->equipment_id)) ?>
      </span>
</div>

<?php if(isset($row->closed)&&$row->closed=='Y') { ?>
<!-- Хаасан хугацаа-->
<div><?=form_label('Хаасан хугацаа', 'closed_datetime')?>   
      <span class='vars'>
          <?php
						
if (isset ( $row->closed_datetime ))
							echo form_label ( $row->closed_datetime, 'closed_datetime' ); // $row->equipment_id))
						else
							echo form_label ( 'Гэмтэл хаагдаагүй байна', 'closed_datetime' );
						?>
      </span>
</div>

<!-- Хаасан ИТА-->
<div><?=form_label('Хаасан ИТА', 'closedby')?>   
      <span class='vars'>
          <?php
						
if (isset ( $row->closedby ))
							echo form_label ( $row->closedby, 'closedby' ); // $row->equipment_id))
						else
							echo form_label ( "-", 'closedby' );
						
						?>
      </span>
</div>

<!-- Хаасан ИТА-->
<div><?=form_label('Үргэлжилсэн хугацаа', 'duration')?>   
      <span class='vars'>
        <?php
						
if (isset ( $row->duration_time ))
							echo form_label ( $row->duration_time, 'duration' ); // $row->equipment_id))//$row->equipment_id))
						else
							echo form_label ( "-", 'closedby' );
						
						?>
      </span>
</div>

<!-- Хаасан ИТА-->
<div><?=form_label('Гүйцэтгэл', 'completion')?>   
      <span class='vars'>
          <?=form_label($row->completion,'duration');//$row->equipment_id)) ?>
      </span>
</div>

<!-- Хаасан ИТА-->
<div><?=form_label('Танилцсан ЕЗИ', 'Танилцсан')?>   
      <span class='vars'>
          <?=form_label($row->provedby,'provedby');//$row->equipment_id)) ?>
      </span>
</div>
<?
					
} else {
						echo form_label ( 'Гэмтэл:' );
						echo "<span class='vars'>";
						echo form_label ( 'Хаагдаагүй байна', 'closed' );
						echo "</span>";
					}
					?>

<div class="submits">
      <? echo form_submit('upload', 'Хавсаргах'); ?>
      <?php $attr = array('class' =>'button good'); ?>
      <?=anchor('log/index', '<< Буцах', $attr);?>
      <?=form_fieldset_close();?>
      
   </div>
<?php } ?>

<? $this->load->view('footer');?>