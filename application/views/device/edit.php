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
			
			<?php echo form_dropdown('location_id', $location, $device->location->location_id, "id='location_id'");?>
			
			</b>
		</p>
		<br>

		<p>		
		<b>Хэсэг/Тасаг: <?=$section[$device->section_id];?>
			<?php echo form_dropdown('section_id', $section, $device->section_id, "id='section_id'");?>
		</b>		
		</p>
		<br>

		<p>
			<b>Тоног төхөөрөмжийн нэр: <?php

			 echo form_dropdown('equipment_id', $equipment, $device->equipment->equipment_id, "id='equipment_id'");?>

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

					  echo form_dropdown('certificate_id', $certificate, $device->certificate_id, "id='certificate_id'");

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

	

		</div>

	</form>


</div>



