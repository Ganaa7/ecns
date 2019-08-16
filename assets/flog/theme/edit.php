
<script>
            $(document).ready(function() {
            	// tree called here
	             $("#black, #gray").treeview({
					   collapsed: false,
					   animated: "fast",
					   control: "#sidetreecontrol",
					   persist: "location",
					   cookieId: "treeview-black",
					   mod_class: "module"			
					});
            });
            </script>
<!-- edit dialog here -->
<form id="edit_form" name="edit_form" method="post"
	style="margin-top: 15px; padding-left: 35px;">	
	<?php print_r($error);?>
	<h3>Гэмтэл засах</h3>
	<div class="wrapper">	
	<p class="feedback"></p>
	<input type="hidden" id="id" name="id" value="<?php echo $id;?>" /> <input
		type="hidden" id="flag" name="flag" value="<?php echo $flag;  ?>" /> <input
		type="hidden" id="status" value="<?php echo $status;?>">
   <?php 
// Гэмтлий дугаар өгсөн бол харуул
	if ($status != 'C') {
				?> 
   <div class="field">
		<label>Гэмтлийн дугаар:</label> <span><?php echo $log_num;?></span> <input
			type="hidden" name='log_num' value="<?php echo $log_num;?>">
	</div>
   <?php } ?>
   <div class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_dt" id="created_dt"
			class="text ui-widget-content ui-corner-all"
			value="<?php echo $created_dt;?>">
	</div>
	<div class="field">
		<label>Хэсэг:</label>
      <?php echo form_dropdown('section_id', $section, $section_id, "id = 'section_id'");?>
   </div>
	<div class="field">
		<label>Байршил:</label>
      <?php echo form_dropdown('location_id', $location, $location_id, "id='location_id' title='Байршлийг сонгоход тухайн байршил дээрх тоног төхөөрөмжүүд гарч ирнэ!'");?>
    </div>
	<div class="field">
		<label id='equipment_label'>Тоног төхөөрөмж:</label>      
      <?php echo form_dropdown('equipment_id', $equipment, $equipment_id, "id='vw_loc_equip_id' title='Тухайн тоног төхөөрөмжийг сонгоход тухайн т/т гэмтлийн модыг үүсгэнэ. Тоног төхөөрөмжийг өөрчлөхөд өмнөх Мод болон утгууд өөрчлөгдөхийг анхаарна уу!'");?>      
    </div>
    <div class="field">  
        <label>Алдааны мод</label>
        <div class="tree">
        	<br>
        <?php echo $ftree;?>
        </div>   
    </div>
    <div class="field">
     	<label>Гэмтлийн төрөл:</label>
	 		 <?php echo form_dropdown('type_id', $log_type, $log_type_id, "id = 'log_type_id' disabled ");?>
	 		 <i class="warning">Алдаа гарсан хэсгүүдийг сонгоход автоматаар сонгогдоно!</i>	 
     </div>
	<div class="field">
		<label id='ftree_label'> Гэмтсэн дэд хэсэг, модиулууд:</label>
		<!-- <ul id="myTags" placeholder="Гэмтлийн модоос сонго!"> -->
		<div id="wrapper-tag" style="min-height: 20px;">							
          <?php echo $tags; ?>
		</div>
          <!-- </ul> -->
	</div>
    
	<div class="field">
		<label id="label_reason">Шалтгаан:</label>      
        <?php echo form_dropdown('reason_id', $reason, $reason_id, "id = 'reason_id'");?>
        <span>гэмтэл гарахад нөлөөлсөн хүчин зүйлс:</span>
	</div>
	<div id="reason_log_1">
		<fieldset style="width: 100%; padding: 0;">
			<legend id="reason_title">Бусад гэмтлээс хамаарсан гэмтэл:</legend>
			<div style="margin: 5px; font-style: italic" class="warning">Нээж буй
				гэмтлийн тухайн байршилд, нээж буй хугацаанаас өмнөх 72 цагийн дотор
				гарсан гэмтлүүдийг харуулж буйг анхаарна уу!</div>
			<em class="warning">Гэмтлийн №, Хэсэг, Нээгдсэн огноо, Тоног
				төхөөрөмж, Модиулууд гэсэн форматаар харуулна!</em> <br> <label
				id="reason_log_title"> Бусад гэмтлүүд:</label>                
                   <?php
																			
		if (isset ( $parent_log ))
			echo form_dropdown ( 'parent_id', $parent_log, "id='parent_id'" );
		else
			echo "<select name='parent_id' id='parent_id'></select>"?>
                    <!-- <select id="parent_id" name="parent_id" data-placeholder="Гэмтлийн нэр || хугацаа || тоног төхөөрөмжийг бичиж шүүж болно!" class="chosen-select" tabindex="1"> -->
			<!-- </select> -->
		</fieldset>
	</div>
	<div id="reason_log_2" class="field">
		<fieldset>
			<legend id="reason_title">Гадны байгууллагын тоног төхөөрөмж:</legend>
			<div class='warning' style="font-style: italic;">Тухайн гэмтлийн
				байршил дээрх гадны тоног төхөөрөмжүүдийг харуулахыг анхаарна уу!</div>
			<label for="">Тоног төхөөрөмж, байгууллага</label>
         <?php		
		if (isset ( $equip_com ))
			echo form_dropdown ( 'equip_com_id', $equip_com, $equip_com_id, "id = 'equip_com_id'" );
		else
			echo "<select name='equip_com_id' id='equip_com_id'></select>";
		?>
       </fieldset>
	</div>
	<div class="field">
		<label for="">Нээхэд хийсэн тайлбар</label>
		<textarea name="comment" id="comment" cols="80" rows="7"><?=$comment?></textarea>
	</div>
	<div id="wrap_closed">
        <?php if($status=='Y'||$status=='N'||$status=='Q'||$status=='F'){ ?>
        <div class="field">
			<label>Хаасан:</label> <span><input type="text" name="closed_dt"
				id="closed_dt" value="<?php echo $closed_dt;?>"></span>
		</div>
		<div class="field">
			<label>Засварласан байдал:</label>          
           <?php echo form_dropdown('completion_id', $completion_type, $completion_id, "id='completion_id'");?>
            хэвийн болсон.
        </div>
		<!-- Ашиласан зассан эсэх-->
		<!-- <div class="field fixing_field">
			<div id="spare_radio">
				<label>Сэлбэг ашигласан уу?:</label> <input type="radio"
					name="is_spare" id="is_spare" value="N"
					<?php // if (isset($is_spare) && $is_spare=="N") echo "checked";?>>
				Үгүй <input type="radio" name="is_spare" id="is_spare" value="Y"
					<?php //if (isset($is_spare) && $is_spare=="Y") echo "checked";?>>
				Тийм <br>
			</div>
		</div> -->
		<div class="field spare_fields">
			<label>Сэлбэгийн төрөл</label>
           <?php		
				if (isset ( $sparetype_id ))
										echo form_dropdown ( 'sparetype_id', $sparetype, $sparetype_id, "id='sparetype_id'" );
									else
										echo form_dropdown ( 'sparetype_id', $sparetype, null, "id='sparetype_id'" );
									?>
        </div>
		<div class="field spare_fields">
			<label>Сэлбэгийн нэр:</label> 

			<input type="hidden" name='spare' id="spare" value="<?php if(isset($spare)) echo $spare ?>">
			<?php 
				if(isset($spare_id))
				   echo form_dropdown('spare_id', $spare, $spare_id, 'id="spare_id" class="chosen-select"');

				else echo form_dropdown('spare_id', $spare, null, 'id="spare_id" class="chosen-select"');

			?> 
				
		</div>
		<div class="field spare_fields">
			<label>Парт дугаар:</label> <input type="text" name="part_number"
				id="part_number" placeholder="Парт дугаарыг бичнэ үү!" value="<?php if(isset($part_number)) echo $part_number ?>">
		</div>	
		<div class="field spare_fields">
		
			<label>Тоо ширхэг:</label> 
			<input type="text" name="qty" id="qty" placeholder="Тоо ширхэг" value="<?php if(isset($qty)) echo $qty ?>">
		
		</div>
        <?php } ?>
        <!-- хэрэв ашигласан бол -->
	</div>
	<?php if(isset($closed_comment)){ ?>
	<div class="field">
		<label for="">Хаахад хийсэн тайлбар</label>
		<textarea name="closed_comment" id="comment" cols="80" rows="7"><?=$closed_comment?></textarea>
	</div>
	<?php } ?>

	<div class="field">
		<label>Гэмтэл дээр ажилласан ИТА:</label>

		<?php 

			if(isset($repairedby_id))
				echo form_dropdown('repairedby_id', $employees, $repairedby_id, "id='employee_id' class='chosen-select'");
			else
				echo form_dropdown('repairedby_id', $employees, null, "id='employee_id' class='chosen-select'");
			
		?>

		
		<i>Тухайн гэмтсэн тоног төхөөрөмж дээр ажилласан ИТА</i>
	</div>
				

	<div style="text-align: right;">
		<span style="margin-right: 20%;"> <a href="#" id='btn_save'
			class="button">Хадгалах</a> <a href="<?=base_url();?>flog" class="button">Болих</a>
		</span>
	</div> 
      <?php echo $inputs; ?> 
     </div>
</form>


<script type="text/javascript">

	<?php if(isset($spare_name)) {  ?>

		$("#spare_id").val(<?=$spare_id?>).trigger("liszt:updated");

	<?php } ?>
	

</script>