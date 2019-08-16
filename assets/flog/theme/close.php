<!-- tag it here
<!-- <link rel="stylesheet" type="text/css"
	href="<? // echo base_url();?>assets/tag-it/css/jquery.tagit.css">
<link rel="stylesheet" type="text/css"
	href="<? // echo base_url();?>assets/tag-it/css/tagit.ui-zendesk.css">

<script src="<? // echo base_url();?>/assets/tag-it/js/tag-it.js"
	type="text/javascript" charset="utf-8"></script>


<link rel="stylesheet" type="text/css"
	href="<? // echo base_url();?>assets/treeview/css/jquery.treeview.css"/>

<link rel="stylesheet" type="text/css"
	href="<? // echo base_url();?>assets/treeview/css/screen.css"> -->
 
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

	function reset_by(){
		var id = $('#vw_loc_equip_id').val();
		$.ajax({
		   type:    'POST',
	   	   url:    base_url+'/flog/jx_reset/',
		   data:   {equipment_id:id},
	       dataType: 'json',
	       async: false,
	       success: function(json){
	          if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
	          	// console.log('restor successed');
	          	return true;
	             //alert(json.status);                  		
	          }else
	          	// console.log('restor false');
	          	return false;
			    // alert(json.status);                  		
	          }                   
		}).done(function(){
			return true;
		});
	}

	$(window).bind('beforeunload', function(){		  		 
   		 return reset_by();
	});

            </script>


<div class="gray-bg" style="padding-left: 40px; padding-top: 20px;">
	<h3>Гэмтэл хаах</h3>
<style>
table.ftree {
	display: block;
}

table.ftree td.col-r {
	text-align: right;
	font-weight: bold;
}

#close_form {
	display: block;
}
</style>
	<form id="close_form" name="close_form">
		<p class="feedback"></p>
	
		 <?php //echo var_dump($parent_error); ?>
		<input type="hidden" type="text" name="log_id"
			value="<?php echo $log['log_id'];?>">
		<table width="100%" class="ftree" cellspacing="15" cellpadding="5">
			<tr>
				<td class="col-r"><span>Нээсэн огноо:</span></td>
				<td> <input type="text" name="created_dt"
				value="<?php echo $log['created_dt'];?>" id="created_dt" disabled></td>
			</tr>
			<tr>
				<td class="col-r"><label>Нээсэн ИТА:</label></td>
				<td><label for=""><?php echo $log['createdby'];?></label></td>				
			</tr>
			<tr>
				<td class="col-r"><label for="">Хэсэг:</label></td>
				<td><label for=""><?php echo $log['section']?></label></td>		
			</tr>
			<tr>
				<td class="col-r"><label for="">Байршил:</label></td>
				<td><label for=""><?php echo $log['location']?></label></td>	
				<input type="hidden" name="location_id" id ="location_id" value="<?php echo $log['location_id'];?>">	
			</tr>
			<tr>
				<td class="col-r"><label for="">Тоног төхөөрөмж:</label></td>
				<td>
				<label for=""><?php echo $log['equipment']?>					
				</label>
				<input type="hidden" name="equipment_id" id="vw_loc_equip_id" value="<?=$log['equipment_id']?>" >
				</td>		
			</tr>
            <tr>
                <td class="col-r">	
                        <strong>Алдааны мод:</strong>
                </td>						
                <td class="col-c">
                <i class="warning"> Алдааны модноос [Үндсэн] буюу [тодорхой бус] төрлийн элементүүдийг сонгох ёстойг анхаарна уу! Тусламж хэсгээс [ХААХ] үйлдлийн тусламжыг уншина уу! </i>
                <br>
                <br>
                        <div class="tree">					
                         <?php echo $ftree;?> 
                        </div>
                </td>
			</tr>
                <tr>
                    <td class="col-r"><label for="">Гэмтэл/дутагдал гарсан дэд хэсэг:</label></td>
                    <td>
                        <div id="wrapper-tag" style="min-height: 20px;">							
		          	<?php echo $tags; ?>
					</div>			         
			   </td>		
			</tr>
			<tr>
				<td class="col-r"><label for="">Төрөл:</label></td>
				<td>
				<?php echo form_dropdown('type_id', $log_type, $log['type_id'], "id = 'log_type_id' disabled");?>
				<br><i class="warning">Алдаа гарсан хэсгүүдийг сонгоход автоматаар сонгогдоно!</i>				
				</td>		
			</tr>
			
                    
			<tr>
				<td class="col-r"><label for="">Шалтгаан:</label></td>
				<td>
				<!-- <label for=""><?php // echo $log['reason']?></label> -->
				 <?php echo form_dropdown('reason_id', $reason, $log['reason_id'], "id = 'reason_id'");?>
        			<i class="warning"> Тодорхойлох боломжгүй гэсэн шалтгаантай хаагдахгүй болохыг анхаарна уу!</i>
				</td>		
			</tr>
			<?php if(isset($log['comment'])){ ?>
			<tr>
				<td class="col-r"><label for="">Нээхэд өгсөн тайлбар:</label></td>
				<td>				
				<span>
					<?php echo $log['comment'];?>
				</span>
				</td>		
			</tr>
			<?php } ?>
			<?php if(isset($log['equip_com_id'])) { ?>
			<tr>
				<td class="col-r"><label for="">Гадны байгууллагын тоног төхөөрөмж:</label></td>
				<td><label for=""><?php echo $log['equip_comp']?></label></td>		
			</tr>			   
			<?php } ?>
			<?php if(isset($log['parent_id'])) { ?>
			<tr>
				<td class="col-r"><label for="">Шалтгаалсан гэмтэл:</label></td>
				<td><span><?php echo $parent_log ?> </span></td>		
			</tr>			   
			<?php } ?>
			<tr id="reason_log_1">
				<td colspan="2">
					<fieldset style="width: 100%; padding: 0;">
						<legend id="reason_title">Гэмтлийн ангилал:</legend>
						<div style="margin: 5px; font-style: italic" class="warning">Нээж
							буй гэмтлийн тухайн байршилд, нээж буй хугацаанаас өмнөх 72
							цагийн дотор гарсан гэмтлүүдийг харуулж буйг анхаарна уу!</div>
						<em class="warning">Гэмтлийн №, Хэсэг, Нээгдсэн огноо, Тоног
							төхөөрөмж, Модиулууд гэсэн форматаар харуулна!</em> <br> <label
							id="reason_log_title"> Бусад гэмтлүүд:</label> 
							<select	id="parent_id" name="parent_id"
							data-placeholder="Гэмтлийн нэр || хугацаа || тоног төхөөрөмжийг бичиж шүүж болно!"
							class="chosen-select" tabindex="1">
						</select>
					</fieldset>
				</td>
			</tr>
			<tr id="reason_log_2">
				<td colspan="2">
					<fieldset>
						<legend id="reason_title">Гадны байгууллагын тоног төхөөрөмжөөс
							хамаарсан:</legend>
						<div class='warning' style="font-style: italic;">Тухайн гэмтлийн
							байршил дээрх гадны тоног төхөөрөмжүүдийг харуулахыг анхаарна уу!</div>
						<label for="">Тоног төхөөрөмж, байгууллага</label> <select
							name="equip_com_id" id="equip_com_id">
							<option value='0'>Сонгох</option>
						</select> <br>
					</fieldset>

				</td>
			</tr>	
			<tr>
				<td class="col-r"><label for="">Хаасан огноо:</label></td>
				<td><input type="text" id="closed_dt" name="closed_dt" placeholder="Хаасан огноог бичнэ үү!"></td>		
			</tr>			
			<tr>
				<td class="col-r"><label for="">Засварласан байдал:</label></td>
				<td><?php echo form_dropdown('completion_id', $completion_type, null, "id='completion_id'");?>
        хэвийн болсон.</td>		
			</tr>
<!-- changed by IX/28	
			<tr class='fixing_field'>
				<td class="col-r"><label for="">Сэлбэг ашигласан эсэх?:</label></td>
				<td><input type="radio"
					name="is_spare" id="is_spare" value="N" checked> Үгүй <input
					type="radio" name="is_spare" id="is_spare" value="Y"> Тийм <br></td>		
			</tr> -->
			<tr class="spare_fields">
				<td class="col-r">
				<label>Сэлбэгийн төрөл</label>
				</td>
				<td><?php echo form_dropdown('sparetype_id', $sparetype, null, "id='sparetype_id'");?></td>
			</tr>
			<tr class="spare_fields">
				<td class="col-r">
				<label>Сэлбэгийн нэр:</label>
				</td>
				<td>
					 <input type="hidden" name='spare' id="spare">
					 <?php echo form_dropdown('spare_id', $spare, null, 'id="spare_id" class="chosen-select"'); ?> 
				</td>
			</tr>
			<tr class="spare_fields">
				<td class="col-r">
				<label>Парт дугаар (part number):</label>
				</td>
				<td>
					 <input type="text" name='part_number' id="part_serial">
				</td>
				
			</tr>
			<tr class="spare_fields">
				<td class="col-r">
				<label>Тоо ширхэг:</label>
				</td>
				<td>
					 <input type="text" name='qty' id="qty">
				</td>
				
			</tr>

			<tr id="comment_field"><td class="col-r"><span>Тайлбар:</span></td>
				<td>
					<textarea name="closed_comment" id="closed_comment" cols="80" rows="7"></textarea>
				</td>
			</tr>	

			<tr>
				<td class="col-r">
					<span id="label_reason">Гэмтэл дээр ажилласан ИТА:</span>

				</td>

				<td>
					<?=form_dropdown('repairedby_id', $employees, null, "id='employee_id' class='chosen-select'");?>
					<i>Тухайн гэмтсэн тоног төхөөрөмж дээр ажилласан ИТА</i>
				</td>

			</tr>		

		</table>
		<div style="text-align: right;">
			<span style="margin-right: 20%;"> <a href="#" id='btn_save'
				class="button">Хадгалах</a> <a href="<?=base_url();?>flog" class="button">Цуцлах</a>
			</span>
		</div>
		<?php echo $inputs; ?> 
	</form>


</div>