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
		width: 70%;
	    margin: auto;
	}
</style>
<?php 

?>
<div class="main">

	<br>
	<h4>ТЕХНИК АШИГЛАЛТЫН ДЭВТЭР</h4>	
	<br>
	<form id="create_pass">

	<p class="feedback"></p>
	<p>(Зөвхөн Иргэний Нисэхийн дотоод хэрэгцээнд ашиглана.)</p>
	<br>

	<p><b>№: 

		<!-- <input type="hidden" name="passport_no" value="" id="pass_no"> -->

	 <?php 
	 echo form_input('passport_no', null, "id='passport_no'");
	 ?>	 	
	 </b>
		

	</p>
	 
	<br>

	<p>
		<b>Хэсэг/Тасаг: <?php echo form_dropdown('section_id', $section, null, 'id="section_id"');?>	
		</b>
	</p>
	<br>

	<p><b>Байршил:  <?php echo form_dropdown('location_id', $location, null, 'id="location_id" disabled');?></b></p>
	<br>


	
	<p>
		<b>Тоног төхөөрөмжийн нэр: <?php echo form_dropdown('equipment_id', $equipment, null, 'id="equipment_id" disabled');?>	
		</b>
	</p>
	<br>		
	<!-- <p><b>Тоног төхөөрөмжийн нэр: </b></p> -->
		<?=form_hidden('device', null, "id='device'");  ?>
	<!-- <br> -->

	<p><b>Тоног төхөөрөмжийн марк, тип: <?=form_input('mark', null, "id='mark'");  ?></b></p>
	<br>
	<p> 
		
		<b id="certificate_wrap">
			Гэрчилгээ:
			<select name="certificate_id" id="certificate_id">				
			</select>
		</b>

		<span id='no_cert_no'><a href="#" id="cert_null" class="button">Гэрчилгээ дугаар = 0 </a></span>

	</p>

	<?php //=form_hidden('certificate_id', null, "id='certificate_id'");  ?>
	<br>
	<p><b>Ашиглалтад орсон он: <?=form_input('year_init', null, "id='year_init'");  ?></b></p>
	<br>	
	<br>
	<h4><b>ТЕХНИКИЙН ТОДОРХОЙЛОЛТ</b></h4>	
	<br>
 	<div class="wrapper">

		<table width="100%" cellpadding="0" cellspacing="0" class="bordered" >
			<thead>
			<tr>
				<th align="center">1</th>
				<th align="center" width="40%">Зориулалт</th>
				<th align="center" width="20%">01</th>
				<th align="center" colspan="3" align="left">
					<?php 
					 echo form_textarea(array(
				              'name'        => 'intend',
				              'id'          => 'intend',              
				              'rows'        => '3',
				              'cols'        => '45'			            
				     )); 
				     ?>
				</th>
			</tr>
			<tr>
				<th>2</th>
				<th>Хүчин чадал</th>
				<th>02</th>
				<th colspan="3">
					  <?=form_input('power', null, "id='power'"); 		   ?>
				</th>
			</tr>	
			<tr>
				<th rowspan="5">3</th>
				<th rowspan="5">Үйлдвэрлэсэн</th>
				<th colspan="2">Улсын нэр</th>				
				<th width="20px;">03</th>
				<th>
					<?=form_dropdown('country_id', $country, null, 'id="country_id"');?></th>
			</tr>
			<tr>
				<th colspan="2">Үйлдвэр, компани</th>
				<th>04</th>
				<th><?=form_dropdown('manufacture_id', $manufacture, null, 'id="manufacture_id"');?></th>
			</tr>
			<tr>
				<th colspan="2">Загвар, моделийн №
					(part number)
				</th>
				<th>05</th>
				<th>
					<?=form_input('part_number', null, "id='part_number'");  ?></th>
			</tr>
			<tr>
				<th colspan="2">Үйлдвэрийн №
					(serial number)
				</th>
				<th>06</th>
				<th><?=form_textarea(array(
			              'name'        => 'serial_number',
			              'id'          => 'serial_number',              
			              'rows'        => '2',
			              'cols'        => '40'			              
			            ));
			            ?>  
			     </th>
			</tr>			
			<tr>
				<th colspan="2">Он сар өдөр</th>
				<th>07</th>
				<th><?=form_input('factory_date', null, "id='factory_date'");  	?></th>
			</tr>
			<tr>
				<th rowspan="2">4</th>
				<th rowspan="2">Ашиглалтад оруулсан</th>
				<th colspan="2">Тушаал, шийдвэр №</th>				
				<th>08</th>
				<th><?=form_input('order_no', null, "id='order_no'");  
					?></th>
			</tr>
			<tr>
				<th colspan="2">Он сар өдөр</th>
				<th>09</th>
				<th><?=form_input('order_date', null, "id='order_date'");  

					?></th>
			</tr>	
			<tr>
				<th width="20px;">5</th>
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
					<?=form_input('invoice_no', null, "id='invoice_no'");  ?></th>
			</tr>		
			<tr>
				<th>7</th>
				<th>Засвар хоорондын хугацаа</th>
				<th>12</th>
				<th colspan="3">
					<?=form_input('repair_time', null, "id='repair_time'");  ?>
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
					<?=form_dropdown('maintenance_time', $maintenance_duration, null, 'id="maintenance_time"');?>
					
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
					<?=form_dropdown('lifetime', $lifetime, null, 'id="lifetime"');?>
				</th>
			</tr>	
			<thead>				
		</table>
		<br>
		
		<p><strong><i>Бүртгэл нээсэн ИТА: <?=$this->session->userdata('fullname');?></i></strong></p>
			<input type="hidden" name="created_by" value="<?=$this->session->userdata('fullname');?>">		
		<br>
		<p><strong>Албан тушаал: <i><?=$this->session->userdata('position');?></i></strong>
			
		</p>

		<br>

		<input type="button" id="save" name="save" value="Хадгалах">
		<a class="button" href="<?=base_url();?>equipment" >Буцах</a>

		</form>

	</div> 


</div>