<style>
	
	.main{

		width: 100%;
		margin: 0 auto;
		text-align: center;
	}

	table.bordered {
		border-collapse: collapse;

	}

	table.bordered > thead > tr , th, td{
		padding:5px 0;
		    border: 1px solid black;
	}

	.wrapper{
		width: 80%;
	    margin: auto;
	}
</style>

<div class="main">
	<br>
	<h3 class="black">ТЕХНИК АШИГЛАЛТЫН ДЭВТЭР</h3 class="black">	
	<br>

	<form id="edit_pass">

		<p class="feedback"></p>

		<?=form_hidden('device_id', $device->id);?>

		<?=form_hidden('device', $device->device, "id='device'");  ?>

		<p>(зөвхөн иргэний нисэхийн дотоод хэрэгцээнд ашиглана.)</p>
		<br>
			<input type="hidden" name="passport_no" value="<?=$device->passport_no;?>" id="pass_no">

		<p><b>№: <?=$device->passport_no;?></b></p>
		<br>

		<p>
			<b>Байршил: <?=$location[$device->location->location_id];?>
			<input type="hidden" name="location_id" value="<?=$device->location->location_id;?>">
			</b>
		</p>
		<br>

		<p>		
		<b>Хэсэг/Тасаг: <?=$section[$device->section_id];?>
			<input type="hidden" name="section_id" value="<?=$device->section_id;?>">			
		</b>		
		</p>
		<br>

		<p>
			<b>Тоног төхөөрөмжийн нэр: <?php

			if(isset($device->equipment->equipment))
			   
			   echo $equipment[$device->equipment->equipment_id];		
			?>	
			<input type="hidden" name="equipment_id" value="<?=$device->equipment->equipment_id;?>">		

			</b>
		</p>

		<br>
		<p><b>Тоног төхөөрөмжийн марк, тип: <?=form_input('mark', $device->mark);?></b></p>
		<br>
		<p><b>Гэрчилгээний дугаар:</b>	

			<b id="certificate_wrap">
				
				<?php 

					$certificate_null = array('0' =>'Гэрчилээгүй т/т');

					if(sizeof($certificate))

					  echo form_dropdown('certificate_id', $certificate, $device->certificate, "id='certificate_id'");

					else 
						echo form_dropdown('certificate_id', $certificate_null, 0, "id='certificate_id'");

				?>
				
			</b>
			
			<span id='no_cert_no'><a href="#" id="cert_null" class="button">Гэрчилгээ дугаар = 0 </a></span>
		</p>
		<br>
		<p><b>Ашиглалтад орсон он: <?=form_input('year_init', $device->year_init, "id='year_init'");?></b></p>
		<br>
		
		<br>
		<h3 class="black"><b>ТЕХНИКИЙН ТОДОРХОЙЛОЛТ</b></h3>	
		<br>

		<div class="wrapper">

			<table width="100%" cellpadding="0" cellspacing="0" class="bordered" >
				<thead>
				<tr>
					<th align="center">1</th>
					<th align="center" width="40%">Зориулалт</th>
					<th align="center" width="20%">01</th>
					<th align="center" colspan="3">
						<?php  echo form_textarea(array(
					              'name'        => 'intend',
					              'id'          => 'intend',              
					              'rows'        => '3',
					              'cols'        => '45'			            
					     ), $device->intend
					 ); ?>
						
							
					</th>
				</tr>
				<tr>
					<th>2</th>
					<th>Хүчин чадал</th>
					<th>02</th>
					<th colspan="3">
						 <?=form_input('power', $device->power, "id='power'");  ?>
							
						</th>
				</tr>	
				<tr>
					<th rowspan="5">3</th>
					<th rowspan="5">Үйлдвэрлэсэн</th>
					<th colspan="2">Улсын нэр</th>				
					<th>03</th>
					<th><?php if(isset($device->country->country_id)) 

							echo form_dropdown('country_id', $country, $device->country->country_id, 'id="country_id"');

							else 
								echo form_dropdown('country_id', $country, 0, 'id="country_id"');

							?></th>
					
				</tr>
				<tr>
					<th colspan="2">Үйлдвэр, компани</th>
					<th>04</th>
					<th>
						<?php 
						if(isset($device->manufacture->manufacture_id))
							echo form_dropdown('manufacture_id', $manufacture, $device->manufacture->manufacture_id, 'id="manufacture_id"');
						else 
							echo form_dropdown('manufacture_id', $manufacture, null, 'id="manufacture_id"');

					?></th>
					
				</tr>
				<tr>
					<th colspan="2">Загвар, моделийн №
						(part number)
					</th>
					<th>05</th>
					<th>
						<?=form_input('part_number', $device->part_number, "id='part_number'");  ?>
												
						</th>
				</tr>
				<tr>
					<th colspan="2">Үйлдвэрийн №
						(serial number)
					</th>
					<th>06</th>
					<th>
						<?=form_textarea(array(
				              'name'        => 'serial_number',
				              'id'          => 'serial_number',              
				              'rows'        => '2',
				              'cols'        => '40'
				              
				            ),
							$device->serial_number
				        ); ?>  

											
						</th>
				</tr>			
				<tr>
					<th colspan="2">Он сар өдөр</th>
					<th>07</th>
					<th><?=form_input('factory_date', $device->factory_date, "id='factory_date'");  ?></th>
				</tr>
				<tr>
					<th rowspan="2">4</th>
					<th rowspan="2">Ашиглалтад оруулсан</th>
					<th colspan="2">Тушаал, шийдвэр №</th>				
					<th>08</th>
					<th><?=form_input('order_no', $device->order_no, "id='order_no'");  ?></th>				
				</tr>
				<tr>
					<th colspan="2">Он сар өдөр</th>
					<th>09</th>
					<th><?=form_input('order_date', $device->order_date, "id='order_date'");  ?></th>
					
				</tr>	
				<tr>
					<th>5</th>
					<th>Суурилагдсан хэсэг, тасгийн нэр</th>
					<th>10</th>
					<th colspan="3">
							<label id='label_section'></label>
					</th>
				</tr>	
				<tr>
					<th>6</th>
					<th>Санхүүгийн бүртгэл №</th>
					<th>11</th>
					<th colspan="3">
						<?=form_input('invoice_no', $device->invoice_no, "id='invoice_no'");  ?></th>
						
				</tr>		

				<tr>
					<th>7</th>
					<th>Засвар хоорондын хугацаа</th>
					<th>12</th>
					<th colspan="3">
						<?=form_input('repair_time', $device->repair_time, "id='repair_time'");  ?>
					</th>
				</tr>

				<tr>
					<th>8</th>
					<th>Техник үйлчилгээ хоорондын хугацаа</th>
					<th>13</th>
					<th colspan="3">
						<?php 
						$maintenance_duration = array(
										'Сард' =>'Сард',									
										'Улиралд' =>'Улиралд',
										'Хагас жилд' =>'Хагас жилд',									
										'Жилд 1 удаа' =>'Жилд 1 удаа',
										'Жилд 2 удаа' =>'Жилд 2 удаа',
									 );
						?>
						<?=form_dropdown('maintenance_time', $maintenance_duration, $device->maintenance_time, 'id="maintenance_duration"');?>
						
					</th>
				</tr>
				<tr>
					<th>9</th>
					<th>Ашиглалтын хугацаа</th>
					<th>14</th>
					<th colspan="3">
						<?php 
						$lifetime = array(
										'10' =>'10 жил',
										'9' =>'9 жил',
										'8' =>'8 жил',
										'7' =>'7 жил',
										'6' =>'6 жил',
										'5' =>'5 жил',
										'4' =>'4 жил',
										'3' =>'3 жил',
										'2' =>'2 жил',
										'15' =>'15 жил',
										'20' =>'20 жил',
										'25' =>'25 жил',
										'30' =>'30 жил',
										'35' =>'35 жил',
									 );
									 ?>
						<?=form_dropdown('lifetime', $lifetime, $device->lifetime, 'id="lifetime"');?>
					</th>
				</tr>
	
				<thead>				
			</table>
			<br>
			<p>Бүртгэл нээсэн ИТА:<?=$device->createdby;?></p>
			<p>Албан тушаал:<?=$device->createdby_position;?></p>
			<br>
			<div>
				<a class="button" id="update" >Техникийн тодорхойлолтыг засах</a>

				<a class="button" href="<?=base_url('/equipment');?>">Буцах</a>
			</div>

			<br>
			<br>

			<!-- device parameters -->
			<h3 class="black">ТЕХНИКИЙН ҮНДСЭН ҮЗҮҮЛЭЛТҮҮД</h3>
			<br>
			<table width="100%" cellpadding="0" cellspacing="0" class="bordered">
				<tr>
					<th>№</th>
					<th>Үзүүлэлтийн нэр</th>
					<th>Хэмжих нэгж</th>
					<th>Утга</th>
					<th>Үйлдэл</th>
				</tr>
				<?php $cnt = 0;

				// var_dump($parameter);
				
				 foreach ($parameter as $row) { 
					echo "<tr>";

						echo "<td>".++$cnt."</td>";
						
						echo "<td>$row->parameters</td>";
						
						echo "<td>$row->measure</td>";
						
						echo "<td>$row->value</td>";
						
						echo "<td><a href='#' onclick='edit_parameter_(".$row->id.")'>Засах</a>
								| <a href='#' onclick='delete_parameter(".$row->id.")'>Устгах</a>
						</td>";
						
					echo "</tr>";

				} ?>

			</table>

			<br>

			<div>
				
				<a class="button" onclick="parameter_modal('<?=$device->device;?>')">Техникийн үндсэн үзүүлэлт нэмэх</a>

			</div>
			
			<br><!-- device materials -->
			<br><!-- device materials -->

			<h3 class="black">ТЕХНИКИЙН ИЖ БОЛГОГЧ ЗҮЙЛС</h3>
			<br>
			<table width="100%" cellpadding="0" cellspacing="0" class="bordered">
				<tr>
					<th>№</th>
					<th>Иж болгогч зүйлсийн нэр, марк</th>
					<th>Тоо ширхэг</th>
					<th>Үйлдвэрийн №, Засварын №</th>
					<th>Үйлдэл</th>
				</tr>
				
				<?php 
				$cnt = 0;
				 foreach ($material as $row) { 
					echo "<tr>";
					echo "<td>".++$cnt."</td>";
					echo "<td>$row->materials</td>";
					echo "<td>$row->qty</td>";
					echo "<td>$row->part_number</td>";
					echo "<td><a href='#' onclick='edit_material_(".$row->id.")'>Засах</a> | <a href='#' onclick='delete_material(".$row->id.")'>Устгах</a>
					</td>";
					echo "</tr>";
				} ?>
				
			</table>

			<br>

			<div>

			<a class="button" onclick="material_modal('<?=$device->device;?>')" >Техникийн иж болгогч зүйлс нэмэх</a>
			
			</div>
			
			<br>
			<br>

			<h3 class="black">ТЕХНИК ҮЗЛЭГ, ҮЙЛЧИЛГЭЭНИЙ БҮРТГЭЛ</h3>	

			<br>
			<i class="warning">Техник үйлчилгээг техник үйлчилгээний бүртгэлээс нэмж, засах боломжтой.</i>
			<br>
			<br>

			<table cellpadding="0" cellspacing="0" class="bordered">
				<tr>
					<th>№</th>
					<th>он, сар, өдөр</th>
					<th>Хийгдсэн ТҮ-ний нэр төрөл</th>
					<th>ТҮ-үргэлжилсэн хугацаа /цаг/</th>
					<th>ТҮ-ийн явцад илэрсэн дутагдал, ТҮ сэлбэг, материалийн зарцуулалт</th>
					<th>Гүйцэтгэгчийн гарын үсэг, албан тушаал</th>
					<th>Үйлдэл</th>
				</tr>
				<tr>
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th>6</th>
					<th></th>
				</tr>
				
				<?php 
				$cnt = 0;

				foreach ($event as $row) {

						$duration = (strtotime($row->end)-strtotime($row->start))/3600;

						$ita = $this->employee_model->get($row->doneby_id);

						echo "<tr>";
						echo "<td>".++$cnt."</td>";
						echo "<td>".$row->start."</td>";
						echo "<td>".$row->eventtype->eventtype."</td>";
						echo "<td>".round($duration, 1)."</td>";
						echo "<td>".$row->title.".Гүйцэтгэл:".$row->done."</td>";
						echo "<td>".$ita->fullname."</td>";
						echo "<td><a href='#' onclick='_edit_maintenance(".$row->id.")'>Засах</a> | <a href='#' onclick='delete_maintenance(".$row->id.")'>Устгах</a>
						</td>";	
						echo "</tr>";
					
				} ?>
				
		
			</table>

			<br>		
			<a class="button" onclick="maintenance_modal('<?=$device->device;?>')" >Техник үйлчилгээний бүртгэл нэмэх</a>	
			<br>	
			<br>	

			<h3 class="black">ЗАСВАРЫН БҮРТГЭЛ</h3>	

			<br>

			<table class="bordered" cellpadding="0" cellspacing="0">
				<tr>
					<th rowspan="2">№</th>
					<th rowspan="2">он, сар, өдөр</th>
					<th rowspan="2">Засвар хийх болсон шалтгаан</th>
					<th rowspan="2">Засварын ажлын нэр</th>
					<th colspan="3" >Засварын материал, сэлбэгийн зарцуулалт</th>
					<th rowspan="2">Засварын үргэлжилсэн хугацаа</th>
					<th rowspan="2">Гүйцэтгэгчийн гарын үсэг</th>
					<th rowspan="2">Үйлдэл</th>
				</tr>
				<tr>
					<th>Нэр төрөл</th>
					<th>Тоо</th>
					<th>Үйлдвэрийн №</th>
				</tr>
				<tr>
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th>6</th>
					<th>7</th>
					<th>8</th>
					<th>9</td>
					<th></td>
				</tr>
				<?php 

				if($repair){

					foreach ($repair as $rep) {
						echo "<tr>";
						echo "<td></td>";
						echo "<td>$rep->repair_date</td>";
						echo "<td>$rep->reason</td>";
						echo "<td>$rep->repair</td>";					
						echo "<td>".$rep->wh_spare->spare."</td>";
						echo "<td>$rep->qty</td>";
						echo "<td>$rep->part_number</td>";
						echo "<td>$rep->duration</td>";
						echo "<td>$rep->repairedby</td>";				
						echo "<td><a href='#' onclick='edit_repair_(".$rep->id.")'>Засах</a> | <a href='#' onclick='delete_repair(".$rep->id.")'>Устгах</a>
						</td>";			
						echo "</tr>";
					}

				}

				else{
				?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

				<?php } ?>

				
				
			</table>
			<br>
			<div>

			<a class="button" id="add_parameter" onclick="repair_modal('<?=$device->device;?>')">Засварын бүртгэл үүсгэх</a>

			<a class="button" id="add_parameter" href="<?=base_url('/equipment')?>">Буцах</a>
			</div>
			
		</div>

	</form>


</div>


<?php echo form_open('#', array('name'=>'maintenance', 'id'=>'maintenance', 'title'=>'Т/Ү нэмэх')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?> 

<?php echo form_hidden('equipment_id', $device->equipment_id);?> 

<?php echo form_hidden('location_id', $device->location_id);?> 

<?php $intterupt = array(
				2=>'Нэг сонголтыг сонго',
				1=>'Тасалдана',
				0=>'Тасалдахгүй'
			);
?>

	<p class="feedback"></p>

	<p class="warning"><i>Энд нэмсэн мэдээлэл нь "Техник Үйлчилгээ" модульд шууд нэмэгдэхийг анхаарна уу!</i></p>
	<br>

    <div class="field" >
      
      <label for="section_id">Төхөөрөмж:</label>

      <?=form_input('device', $device->device, "id='device' disabled");  ?>

    </div>

    <div class="field">
    
       <label for="author">Төрөл:</label>
    
       <?=form_dropdown('eventtype_id',$eventtype, null, 'id=eventtype_id'); ?>
    
    </div>


    <div class="field">
    
       <label for="author">Ажлын тодорхойлолт:</label>
    
       <textarea name='title' id='event' col="60" row="20" style="width: 270px; height: 70px;"></textarea>
    
    </div>

    <div class="field">

    	<label for="intterupt">Үйлчилгээ тасалдах эсэх?</label>

		<?=form_dropdown('is_interrupt',$intterupt, null, 'id=is_interrupt'); ?>			
    	
    </div>
         
    <div class="field">
    
       <label for="author">Эхлэх огноо:</label>
    
       <td><input type="text" name="start" id="startdate" size=16 /></td>
    
    </div>

    <div class="field">
    
        <label for="author">Дуусах огноо:</label>
    
   		<input type="text" name="end" id="enddate" size=16 />
    
    </div>
       

  <div class="field">
  	
  	<label for="done">Гүйцэтгэл:</label>
	
	<textarea name='done' id='done' col="40" row="10"  style="width: 270px; height: 70px;"></textarea>
  
  </div>	

  <div class="field">

    <label for="">Гүйцэтгэсэн ИТА:</label>

    <?=form_dropdown('doneby_id', $employee, null, "id='doneby_id'");  ?>

  </div>  

 
  <div>
  		<label for="">ТҮ-ны бүртгэл нээсэн ИТА:</label>

  		<p class="warning">Энэ эрхээр орж бүртгэл үүсгэсэн ИТА нь бүртгэл нээсэн ИТА-р бүртгэгдэх тул анхаарна уу! </p>

  </div>
		
<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'material', 'id'=>'material', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
<?php echo validation_errors();?>
<?php echo form_hidden('device_id', $device->id);?> 

<p class="feedback"></p>
    <div class="field" >
      <label for="section_id">Төхөөрөмж:</label>
      <?=form_input('device', $device->device, "id='device' disabled");  ?>
    </div>
      
    <div class="field">
       <label for="isbn">Иж болгогч зүйлсийн нэр, марк:</label>
       <?=form_input('materials', null, "id='materials'");  ?>
    </div>
    <div class="field">
       <label for="author">Тоо ширхэг:</label>
       <?=form_input('qty', null, "id='qty'");  ?>
    </div>
       
  <div class="field">
    <label for="">Үйлдвэрийн №, Засварын №:</label>
    <?=form_input('part_number', null, "id='part_number'");  ?>
  </div>  

<?php echo form_close();?>

<?php echo form_open('#', array('name'=>'parameter', 'id'=>'parameter', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
<?php echo validation_errors();?>
<?php echo form_hidden('device_id', $device->id);?> 

<p class="feedback"></p>

  	<div class="field" >
      <label for="section_id">Төхөөрөмж:</label>
      <?=form_input('device', $device->device, "id='device' disabled");  ?>
      <?// echo form_dropdown('device_id', $device, null, 'id="device_id"');?>
    </div>
      
    <div class="field">
       <label for="isbn">Үзүүлэлт:</label>
       <?=form_input('parameters', null, "id='parameters'");  ?>
    </div>
    <div class="field">
       <label for="author">Хэмжих нэгж:</label>
       <?=form_input('measure', null, "id='measure'");  ?>
    </div>
       
  <div class="field">
    <label for="">Утга:</label>
    <?=form_input('value', null, "id='value'");  ?>
  </div>  

<?php echo form_close();?>


<?php echo form_open('#', array('name'=>'repair', 'id'=>'repair', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
<?php echo validation_errors();?>
<?php echo form_hidden('device_id', $device->id);?> 

<p class="feedback"></p>

  	<div class="field" >
      <label for="section_id">Төхөөрөмж:</label>
      <?=form_input('device', $device->device, "id='device' disabled");  ?>
      <?// echo form_dropdown('device_id', $device, null, 'id="device_id"');?>
    </div>
      
    <div class="field">
       <label for="isbn">Он, сар , өдөр:</label>
       <?=form_input('repair_date', null, "id='repair_date'");  ?>
    </div>

    <div class="field">
       <label for="isbn">Засвар хийх болсон шалтгаан:</label>
       <?=form_input('reason', null, "id='reason' size='40'");  ?>
    </div>

    <div class="field">
    
       <label for="author">Засварын ажлын нэр:</label>
       
       <textarea name='repair' id='repair' col="100" row="20" style="width: 270px; height: 70px;"></textarea>
    </div>    

    <div class="field">
       <label for="author">Материалын нэр төрөл:</label>
       <?=form_dropdown('spare_id', $spare, "id='spare_id' size='40'");  ?>
    </div>
    
    <div class="field">
       <label for="author">Тоо:</label>
       <?=form_input('qty', null, "id='qty' size='40'");  ?>
    </div>

    <div class="field">
       <label for="author">Үйлдвэрийн №:</label>
       <?=form_input('part_number', null, "id='part_number' size='40'");  ?>
    </div>
       
  <div class="field">
    <label for="">Засвар үргэлжилсэн хугацаа:</label>
    <?=form_input('duration', null, "id='duration' size='5'"); ?> цаг:мин
  </div>  

  <div class="field">
    <label for="">Гүйцэтгэсэн:</label>
    <?=form_dropdown('repairedby_id', $employee, "id='repairedby_id'"); ?>
  </div>  

<?php echo form_close();?>

<!-- Параметр Засах -->

<?php echo form_open('#', array('name'=>'edit_parameter', 'id'=>'edit_parameter', 'title'=>'Шинэ төхөөрөмж засах')); ?>
<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?> 

<?php echo form_hidden('id');?> 

<p class="feedback"></p>

	<div class="field">

      <label for="section_id">Төхөөрөмж:</label>

      <?=form_input('device', $device->device, "id='device' disabled");  ?>

    </div>
      
    <div class="field">

       <label for="isbn">Үзүүлэлт:</label>

       <?=form_input('parameters', null, "id='parameters'");  ?>

    </div>

    <div class="field">

       <label for="author">Хэмжих нэгж:</label>

       <?=form_input('measure', null, "id='measure'");  ?>

    </div>
       
  <div class="field">

    <label for="">Утга:</label>

    <?=form_input('value', null, "id='value'");  ?>

  </div>  

<?php echo form_close();?>


<!-- Материалыг засах -->

<?=form_open('#', array('name'=>'edit_material', 'id'=>'edit_material', 'title'=>'Шинэ материал засах')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?> 

<?php echo form_hidden('id');?> 

<p class="feedback"></p>

    <div class="field" >
    
      <label for="section_id">Төхөөрөмж:</label>
    
      <?=form_input('device', $device->device, "id='device' disabled");  ?>
    
    </div>
      
    <div class="field">
    
       <label for="isbn">Иж болгогч зүйлсийн нэр, марк:</label>
    
       <?=form_input('materials', null, "id='materials'");  ?>
    
    </div>
    
    <div class="field">
    
       <label for="author">Тоо ширхэг:</label>
    
       <?=form_input('qty', null, "id='qty'");  ?>
    
    </div>
       
  	<div class="field">
    
    	<label for="">Үйлдвэрийн №, Засварын №:</label>
    
    	<?=form_input('part_number', null, "id='part_number'");  ?>
  	
  	</div> 

<?php echo form_close();?>


<!-- Засварыг засах -->

<?=form_open('#', array('name'=>'edit_repair', 'id'=>'edit_repair', 'title'=>'Шинэ материал нэмэх')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?> 

<?php echo form_hidden('id');?> 

<p class="feedback"></p>

         
    <div class="field">
    
       <label for="repair">Засвар хийсэн он, сар, өдөр:</label>
    
       <?=form_input('repair_date', null, "id='repair_date'");  ?>
    
    </div>
    
    <div class="field">
    
       <label for="reason">Хийх болсон шалтгаан:</label>
    
       <?=form_input('reason', null, "id='reason'");  ?>
    
    </div>
       
  	<div class="field">
    
    	<label for="repair">Засварын ажлын нэр:</label>
    
    	<?=form_input('repair', null, "id='repair'");  ?>
  	
  	</div>  

 	<div class="field">

       <label for="author">Материалын нэр төрөл:</label>

       <?=form_dropdown('spare_id', $spare, null, "id='spare_id'");  ?>

    </div>
    
    <div class="field">

       <label for="author">Тоо:</label>

       <?=form_input('qty', null, "id='qty' size='40'");  ?>

    </div>

    <div class="field">

       <label for="author">Үйлдвэрийн №:</label>

       <?=form_input('part_number', null, "id='part_number' size='40'");  ?>

    </div>

    <div class="field">

	    <label for="">Засвар үргэлжилсэн хугацаа:</label>
	    
	    <?=form_input('duration', null, "id='duration' size='5'"); ?> цаг:мин
	  	
  	</div>  


  	<div class="field">
    
    	<label for="">Гүйцэтгэсэн:</label>
    	
    	<?=form_dropdown('repairedby_id', $employee, null, "id='repairedby_id'"); ?>
  	
  	</div> 

<?php echo form_close();?>


<!-- FORM EDIT MAINTENANCE -->
<?php echo form_open('#', array('name'=>'edit_maintenance', 'id'=>'edit_maintenance', 'title'=>'Т/Ү ЗАСАХ')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?> 

<?php echo form_hidden('id');?> 

<?php echo form_hidden('equipment_id', $device->equipment_id);?> 

<?php echo form_hidden('location_id', $device->location_id);?> 

<?php $intterupt = array(
				2=>'Нэг сонголтыг сонго',
				1=>'Тасалдана',
				0=>'Тасалдахгүй'
			);
?>

	<p class="feedback"></p>

	<p class="warning"><i>Энд нэмсэн мэдээлэл нь "Техник Үйлчилгээ" модульд шууд нэмэгдэхийг анхаарна уу!</i></p>
	<br>

    <div class="field" >
      
      <label for="section_id">Төхөөрөмж:</label>

      <?=form_input('device', $device->device, "id='device' disabled");  ?>

    </div>

    <div class="field">
    
       <label for="author">Төрөл:</label>
    
       <?=form_dropdown('eventtype_id',$eventtype, null, 'id=eventtype_id'); ?>
    
    </div>


    <div class="field">
    
       <label for="author">Ажлын тодорхойлолт:</label>
    
       <textarea name='title' id='title' col="60" row="20" style="width: 270px; height: 70px;"></textarea>
    
    </div>

    <div class="field">

    	<label for="intterupt">Үйлчилгээ тасалдах эсэх?</label>

		<?=form_dropdown('is_interrupt',$intterupt, null, 'id=is_interrupt'); ?>			
    	
    </div>
         
    <div class="field">
    
       <label for="author">Эхлэх огноо:</label>
    
       <td><input type="text" name="start" id="startdate" size=16 /></td>
    
    </div>

    <div class="field">
    
        <label for="author">Дуусах огноо:</label>
    
   		<input type="text" name="end" id="enddate" size=16 />
    
    </div>
       

  <div class="field">
  	
  	<label for="done">Гүйцэтгэл:</label>
	
	<textarea name='done' id='done' col="40" row="10"  style="width: 270px; height: 70px;"></textarea>
  
  </div>	

  <div class="field">

    <label for="">Гүйцэтгэсэн ИТА:</label>

    <?=form_dropdown('doneby_id', $employee, null, "id='doneby_id'");  ?>

  </div>  

 
  <div>
  		<label for="">ТҮ-ны бүртгэл нээсэн ИТА:</label>

  		<input type="text" name="createdby" id="createdby" disabled="true">

  </div>
		

<?php echo form_close();?>