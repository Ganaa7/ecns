<link rel="stylesheet" href="<? echo base_url();?>assets/chosen/chosen.css">

<script src="<?=base_url();?>assets/chosen/chosen.jquery.js" type="text/javascript"></script>
<?php 
	
	function str_to_time($seconds){

		$hours = floor($seconds / 3600);
		$mins = floor($seconds / 60 % 60);
		$secs = floor($seconds % 60);

		$hours = ($hours==0) ? $hours.'0' : $hours;
		$mins = ($mins==0) ? $mins.'0' : $mins;
		$secs = ($secs==0) ? $secs.'0' : $secs;

		return $hours.":".$mins.":".$secs;
	}


?>

<style>
	.main {

		width: 100%;
		margin: 0 auto;
		text-align: center;
	}

	select {

		width: 40%;
		max-width: 25em;
	}

	.field {
		margin-bottom: 6px;
	}

	table.bordered {
		border-collapse: collapse;

	}

	table.bordered>thead>tr,
	th,
	td {
		padding: 5px 0;
		border: 1px solid #7a7a7a;
	}

	.noborder {
		text-align: center;
		border: none;
	}

	.noborder>tr {
		border: none;
	}

	.noborder>td {
		border: 1px solid #7a7979;
	}

	.wrapper {
		width: 90%;
		margin: auto;
	}

	.my_label {
		padding: 0;
		width: 700px;
	}

	label {
		padding: 0;
		margin: 0;
	}
</style>

<div class="main">
	<br>
	<h4>ТЕХНИК АШИГЛАЛТЫН ДЭВТЭР</h4>
	<br>
	<p>(зөвхөн иргэний нисэхийн дотоод хэрэгцээнд ашиглана.)</p>
	<br>
	<p><b>№:__</b></p>
	<br>
	<p><b>Тоног төхөөрөмжийн нэр: <?=$device->equipment->equipment;?></b></p>
	<br>
	<p><b>Тоног төхөөрөмжийн марк, тип: <?=$device->mark;?></b></p>
	<br>
	<p><b>Гэрчилгээний дугаар:</b>
		<?php if($device->certificate) echo $device->certificate->cert_no; 
			else echo "Гэрчилгээгүй байна";
	?></p>
	<br>
	<p><b>Ашиглалтад орсон он: <?php echo $device->year_init;?></b></p>
	<br>
	<p><b>Байршил: <?=$device->location->location;?></b></p>
	<br>
	<h4><b>ТЕХНИКИЙН ТОДОРХОЙЛОЛТ</b></h4>
	<br>

	<div class="wrapper">

		<table width="100%" cellpadding="0" cellspacing="0" class="bordered">
			<thead>
				<tr>
					<th align="center">1</th>
					<th align="center" width="40%">Зориулалт</th>
					<th align="center" width="20%">01</th>
					<th align="center" colspan="3"><?=$device->intend?></th>
				</tr>
				<tr>
					<th>2</th>
					<th>Хүчин чадал</th>
					<th>02</th>
					<th colspan="3"><?=$device->power?></th>
				</tr>
				<tr>
					<th rowspan="5">3</th>
					<th rowspan="5">Үйлдвэрлэсэн</th>
					<th colspan="2">Улсын нэр</th>
					<th>03</th>
					<th><?php if(isset($device->country->country)) echo $device->country->country;?></th>
				</tr>
				<tr>
					<th colspan="2">Үйлдвэр, компани</th>
					<th>04</th>
					<th><?php if(isset($device->manufacture->manufacture)) echo $device->manufacture->manufacture;?></th>
				</tr>
				<tr>
					<th colspan="2">Загвар, моделийн №
						(part number)
					</th>
					<th>05</th>
					<th><?=$device->part_number;?></th>
				</tr>
				<tr>
					<th colspan="2">Үйлдвэрийн №
						(serial number)
					</th>
					<th>06</th>
					<th><?=$device->serial_number;?></th>
				</tr>
				<tr>
					<th colspan="2">Он сар өдөр</th>
					<th>07</th>
					<th><?=$device->factory_date;?></th>
				</tr>
				<tr>
					<th rowspan="2">4</th>
					<th rowspan="2">Ашиглалтад оруулсан</th>
					<th colspan="2">Тушаал, шийдвэр №</th>
					<th>08</th>
					<th><?=$device->order_no;?></th>
				</tr>
				<tr>
					<th colspan="2">Он сар өдөр</th>
					<th>09</th>
					<th><?=$device->order_date;?></th>
				</tr>
				<tr>
					<th>5</th>
					<th>Суурилагдсан хэсэг, тасгийн нэр</th>
					<th>10432</th>
					<th colspan="3"><?=$device->section->desc;?></th>
				</tr>
				<tr>
					<th>6</th>
					<th>Санхүүгийн бүртгэл №</th>
					<th>11</th>
					<th colspan="3"><?=$device->invoice_no;?></th>
				</tr>
				<tr>
					<th>7</th>
					<th>ИНЕГ-ын гэрчилгээний бүртгэл №</th>
					<th>12</th>
					<th colspan="3">
						<?php 
					if($device->certificate) echo $device->certificate->cert_no;
					else echo "Гэрчилгээгүй"
					?>
					</th>
				</tr>
				<thead>
		</table>
		<br>

		<p>Бүртгэл нээсэн ИТА:<?=$device->createdby;?></p>
		<p>Албан тушаал:<?=$device->createdby_position;?></p>

		<br>
		<?php 
		 
		// if($device->section->section_id == 3){

			if(isset($node)){  ?>

		<button class="button" onclick="add_passbook(<?=$device->id;?>, <?=$device->equipment_id?>)">Техник үйлчилгээний
			дэвтэрт бүлэг нэмэх</button>

		<?php }else{ ?>

		<button>Алдааны мод байхгүй тул нээх боломжгүй байна.</button>

		<?php }	 

		// }

		?>

		<br>
		<br>

		<!-- Тухайн төхөөрөмжийн бүртгэлүүдийг энд бичнэ -->
		<div id="tabs">
			<?php 

		if(isset($passbooks)){ ?>

			<ul>

				<?php 
	
		  	$i= 1;
		  	foreach ($passbooks as $book => $row) {
		  		# code...
		  	    echo "<li><a href='#tabs-".$i."'>".$row->passbook_no."</a></li>";
		    // <li><a href="#tabs-2">Proin dolor</a></li>
		    // <li><a href="#tabs-3">Aenean lacinia</a></li>
		    	$i++;
			} ?>

			</ul>

			<?php 
		  	
		  	$j = 1;

		  	foreach ($passbooks as $book => $row) {

		  	echo "<div id='tabs-".$j++."'>";
		   
		   ?>
			<div>
				<h4>Модулиудын нэрс:</h4>

				<?php 

		   				$passbook_detail= $this->passbook_detail_model->get_many_by(array('passbook_id' =>$row->id));

		   				foreach ($passbook_detail as $detail=> $detail_row) {

								 echo "<button style='margin:5px;'>$detail_row->module</button>";
								 
		   				}
		   			?>

				<a  href="#" onclick="edit_passbook(<?=$row->id;?>)">Засах</a> |
				<a href="#" onclick="delete_passbook(<?=$row->id;?>)">Устгах</a>
			</div>

			<!-- device parameters -->
			<br>
			<h4>ТЕХНИКИЙН ҮНДСЭН ҮЗҮҮЛЭЛТҮҮД</h4>
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

					$parameter = $this->parameter_model->get_many_by(array('passbook_id'=>$row->id));
					
					 foreach ($parameter as $prow) { 
						echo "<tr>";
						echo "<td>".++$cnt."</td>";
						echo "<td>$prow->parameters</td>";
						echo "<td>$prow->measure</td>";
						echo "<td>$prow->value</td>";
						echo "<td><a href='#' onclick='edit_parameter_(".$prow->id.")'>Засах</a>
										| <a href='#' onclick='delete_parameter(".$prow->id.")'>Устгах</a>
								</td>";
						echo "</tr>";
					} ?>
			</table>

			<br>
			<div>
				<a class="button" onclick="parameter_modal('<?=$device->device;?>', <?=$row->id;?>)">Техникийн үндсэн үзүүлэлт
					нэмэх</a>
			</div>

			<br>
			<br>

			<h4>ТЕХНИКИЙН ИЖ БОЛГОГЧ ЗҮЙЛС</h4>
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

					$material = $this->material_model->get_many_by(array('passbook_id'=>$row->id));

					$cnt = 0;
					 foreach ($material as $mrow) { 
						echo "<tr>";
						echo "<td>".++$cnt."</td>";
						echo "<td>$mrow->materials</td>";
						echo "<td>$mrow->qty</td>";
						echo "<td>$mrow->part_number</td>";
						echo "<td><a href='#' onclick='edit_material_(".$mrow->id.")'>Засах</a> | <a href='#' onclick='delete_material(".$mrow->id.")'>Устгах</a>
							</td>";
						echo "</tr>";
					} ?>

			</table>
			<br>

			<div>
				<a class="button" onclick="material_modal('<?=$device->device;?>', <?=$row->id;?>)">Техникийн иж болгогч зүйлс
					нэмэх</a>
			</div>

			<br>
			<br>

			<h4>ТЕХНИК ҮЗЛЭГ, ҮЙЛЧИЛГЭЭНИЙ БҮРТГЭЛ</h4>

			<br>

			<table cellpadding="0" cellspacing="0" class="bordered">
				<tr>
					<th>№</th>
					<th>он, сар, өдөр</th>
					<th>Хийгдсэн ТҮ-ний нэр төрөл</th>
					<th>ТҮ-үргэлжилсэн хугацаа /цаг/</th>
					<th>ТҮ-ийн явцад илэрсэн дутагдал, ТҮ сэлбэг, материалийн зарцуулалт</th>
					<th>Гүйцэтгэгч, албан тушаал</th>
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

					$sql = "SELECT * FROM (SELECT * FROM m_event where location_id = $device->location_id and equipment_id = $device->equipment_id and device_id=$device->id and passbook_id = $row->id
									union 
									SELECT * FROM m_event where location_id = $device->location_id and equipment_id = $device->equipment_id and device_id=$device->id and passbook_id is null) AS event
									order by start asc;
									";

					$query = $this->db->query($sql);

					// $event = $this->event_model->with('eventtype')->get_many_by(array('location_id'=>$device->location_id, 'equipment_id'=>$device->equipment_id, 'device_id'=>$device->id, 'passbook_id'=>$row->id)); 

					// print_r($event);

					
				// if ($query->num_rows() > 0)
				// {	
					
				// }

					
				foreach ($query->result() as $erow) {

					$duration = (strtotime($erow->end)-strtotime($erow->start))/3600;

					$itas = $this->event_detail_model
								 ->get_many_by(array('event_id' => $erow->id));

					echo "<tr>";
					echo "<td>".++$cnt."</td>";
					echo "<td>".$erow->start."</td>";
					echo "<td>";
					if(!empty($erow->eventtype->eventtype_id))	echo $erow->eventtype->eventtype. ". ".$erow->title;
					else echo $erow->title;
					echo "</td>";
					echo "<td>".round($duration)."</td>";
					echo "<td> Гүйцэтгэл:".$erow->done."</td>";

					if($itas){

						echo "<td>";

						foreach ($itas as $ita) {

							echo $ita->employee; echo ", ";
							
						}

						echo "</td>";

					}else echo "<td></td>";

						echo "<td><a href='#' onclick='_edit_maintenance(".$erow->id.", ".$row->id.")'>Засах</a>
										| <a href='#' onclick='delete_maintenance(".$erow->id.")'>Устгах</a></td>";

						echo "</tr>";
						
					} ?>


	
			</table>

			<br>
			<a class="button" onclick="maintenance_modal('<?=$device->device;?>', <?=$row->id;?>)">Техник үйлчилгээний бүртгэл
				нэмэх</a>
			<a class="button"
				onclick="maintenance_load(<?=$device->equipment_id;?>, <?=$device->location_id;?>, <?=$row->id;?>)">Т/Ү-г Техник
				үйлчилгээний бүртгэлээс татаж оруулах</a>
			<br>
			<br>


			<!-- Засварын бүртгэл нэд -->
			<br>
			<h4>ЗАСВАРЫН БҮРТГЭЛ</h4>
			<br>

			<table class="bordered" cellpadding="0" cellspacing="0">
				<tr>
					<th rowspan="2">№</th>
					<th rowspan="2">он, сар, өдөр</th>
					<th rowspan="2">Засвар хийх болсон шалтгаан</th>
					<th rowspan="2">Засварын ажлын нэр</th>
					<th colspan="3" width="30%">Засварын материал, сэлбэгийн зарцуулалт</th>
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
					<th>10</td>
				</tr>
				<?php 

					$repair = $this->repair_model->with('repair_spare')->with('repair_employee')->get_many_by(array('passbook_id'=>$row->id));

					if($repair){

						$i =0;

						foreach ($repair as $rep) {
							
							echo "<tr>";

								echo "<td>".++$i."</td>";

								echo "<td>$rep->repair_date</td>";

								echo "<td>$rep->reason</td>";

								echo "<td>$rep->repair</td>";	

								echo "<td>";

									$count = 0;
								
									foreach ($rep->repair_spare as $spare ) {
										
										echo "<div>";
									
										echo $spare->spare; 
									
										echo "</div>";
									}

								echo "</td>";
								
								echo "<td>";
								
									foreach ($rep->repair_spare as $spare ) {
									
										echo "<div>";
										
										echo $spare->qty; 
									
										echo "</div>";
										}
								
										echo "</td>";
								
								echo "<td>";
								
								foreach ($rep->repair_spare as $spare ) {
									echo "<div>";
										echo $spare->part_number; 
									echo "</div>";
								}
								echo "</td>";
								
								echo "<td>".str_to_time($rep->duration)."</td>";
								
								echo "<td>"; 

								foreach ($rep->repair_employee as $employer ) {
										
									echo $employer->employee; echo ". ";
								
								} echo "</td>";		

								echo "<td>"; 
									
								  echo "<a href='#' onclick='edit_repair_(".$rep->id.", ".$row->id.")'>Засах</a> |";
											
									echo "<a href='#' onclick='delete_repair(".$rep->id.")'>Устгах</a>";

								echo "</td>";

							echo "</tr>";

						}

					}

					?>

			</table>
			<br>
			<div>
				<a class="button" id="add_parameter"
					onclick="repair_modal('<?=$device->device;?>', <?=$row->id;?>, <?=$device->id;?>)">Засварын бүртгэл үүсгэх</a>

				<a class="button"
					onclick="add_repair_model(<?=$device->equipment_id;?>, <?=$device->location_id;?>, <?=$row->id;?>)">Гэмтэл
					дутагдлын бүртгэлээс засварыг татаж оруулах</a>


			</div>


			<?php echo "</div>";

				}

		  ?>

			<?php }

		?>


		</div>

		<br>
		<a class="button" href="<?=base_url('/equipment');?>">Буцах</a>

	</div>


</div>


<?php echo form_open('#', array('name'=>'passbook', 'id'=>'passbook', 'title'=>'ТҮ дэвтэрт хэсэг нэмэх')); ?>
<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?>

<?php echo form_hidden('equipment_id', $device->equipment_id);?>

<p class="feedback"></p>

<div class="field">

	<label for="section_id">Төхөөрөмж:</label>

	<span><?=$device->device?></span>

</div>

<div class="field">

	<label for="passbook_no">Нэр:</label>

	<span>
		<input type="text" name="passbook_no" id="passbook_no">
	</span>

</div>

<div class="field">

	<div for="author">Хамаарах модулиуд: <span class="warning">Тухайн дэвтэрт хамаарах модиулуудийг оруулна уу! Оруулсан
			модулиудтай холбоотой гэмтлийн модулиудыг засвар хэсэгт сонгогдохыг анхаарна уу!</span><a href=""> Тусламж ?</a>
	</div>

	<span class="warning" for=""></span class="warning">


	<?php echo form_dropdown('node_id[]', $node, null, 'class="multiselect" multiple="multiple" id="node_id"');?>

</div>



<?php echo form_close();?>

<!-- 	1 Байршил
	2 Төхөөрөмж
	3 Төрөл
	4. Ажлын тодорхойлолт
	5. Гүйцэтгэл
	6. Гүйцэтгэсэн ИТА
	7. Бүртгэсэн ИТА
 -->
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

<div class="field">

	<label for="section_id">Төхөөрөмж:</label>

	<?=form_input('device', $device->device, "id='device' disabled");  ?>

</div>

<div class="field">

	<label for="author">Төрөл:</label>

	<?=form_dropdown('eventtype_id', $eventtype, null, 'id=eventtype_id'); ?>

</div>

<div class="field" id="add_passbook_all">

	<label for="author">Бүх дэд дэвтэрт хамаарна:</label>

	<?php echo form_checkbox('passbook_all', 'true', FALSE); ?> Тийм

</div>

<div class="field">

	<label for="author">Ажлын тодорхойлолт:</label>

	<textarea name='event' id='event' col="60" row="20" style="width: 270px; height: 70px;"></textarea>

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

	<textarea name='done' id='done' col="40" row="10" style="width: 270px; height: 70px;"></textarea>

</div>

<div class="field">

	<label for="">Гүйцэтгэсэн ИТА:</label>

	<?php echo form_dropdown('doneby_id[]', $employee, null, 'size = "5" class="multiselect" multiple="multiple" id="doneby_id" data-placeholder="ИТА-с нэгийг сонго."');?>

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
	<div class="field">
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

	<div class="field">
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

<!-- Сэлбэг нэмэх -->
<?php echo form_open('#', array('name'=>'repair', 'id'=>'repair', 'title'=>'Сэлбэг нэмэх')); ?>

	<?php echo validation_errors();?>

	<input type="hidden" name="device_id" value="<?=$device->id;?>" id="device_id">

	<p class="feedback"></p>

	<div class="field">

		<label for="isbn">Он, сар , өдөр:</label>

		<?=form_input('repair_date', null, "id='repair_date'");  ?>

	</div>

	<div class="field">

		<label for="isbn">Засвар хийх болсон шалтгаан:</label>

		<textarea name="reason" id="reason" cols="48" rows="3"></textarea>

	</div>

	<div class="field">

		<label for="author">Засварын ажлын нэр:</label>

		<textarea name="repair" id="repair" cols="48" rows="3"></textarea>

	</div>

	<div style="margin:10px">Материалын нэр төрөл: <strong>
			<button type="button" id='btn_spare' class="button" onclick='add_spare()'>Ашигласан сэлбэг/материал нэмэх</button>
		</strong>

		| <label for="no_spare"> <strong>Сэлбэг/материал хэрэглээгүй </strong></label><input type="checkbox" name="no_spare"
			value="1" id="no_spare">


	</div>

	<div id='spare_wrapper'>

		<table style="width:100%" cellspacing="0" cellpadding="0" class="noborder" id="spare_table">
			<tr>
				<th>#</th>
				<th>Материал</th>
				<th>Тоо/ш</th>
				<th>Үйлдвэр №</th>
				<th>Үйлдэл</th>
			</tr>

		</table>

	</div>
	<br>

	<div class="field">

		<label for="">Засвар үргэлжилсэн хугацаа:</label>

		<?=form_input('duration', null, "id='duration' size='8'"); ?> цаг:мин

	</div>

	<div class="field">

		<label for="">Гүйцэтгэсэн ИТА:</label>

		<?=form_dropdown('repairedby_id[]', $employee, null, "id='repairedby_id' multiple='multiple' class='chosen-select' data-placeholder='ИТА-с нэгийг сонго.'"); ?>

	</div>

<?php echo form_close();?>



<!-- Edit Repair  -->
<?php echo form_open('#', array('name'=>'edit_repair', 'id'=>'edit_repair', 'title'=>'Засварын бүртгэл засах')); ?>

	<?php echo validation_errors();?>

	<input type="hidden" name="device_id" value="<?=$device->id;?>" id="device_id">

	<input type="hidden" name="repair_id" id="repair_id">

	
	<p class="feedback"></p>

	<div class="field">
		<label for="isbn">Он, сар , өдөр:</label>
		<?=form_input('repair_date', null, "id='edit_repair_date'");  ?>
	</div>

	<div class="field">
		<label for="isbn">Засвар хийх болсон шалтгаан:</label>

		<textarea name="reason" id="reason" cols="48" rows="3"></textarea>

	</div>

	<div class="field">
		<label for="author">Засварын ажлын нэр:</label>
		<textarea name="repair" id="repair" cols="48" rows="3"></textarea>
	</div>

	<div style="margin:10px">Материалын нэр төрөл: <strong>
			<button type="button" id='edit_btn_spare' class="button" onclick='add_spare_edit()'>Ашигласан сэлбэг/материал нэмэх</button>
		</strong>

		| <strong><label for="edit_no_spare"> Сэлбэг/материал хэрэглээгүй </label></strong><input type="checkbox" name="no_spare"
			value="1" id="edit_no_spare">


	</div>

	<div id='spare_wrapper'>

		<table style="width:100%" cellspacing="0" cellpadding="0" class="noborder" id="edit_spare_table">
			<tr>
				<th>#</th>
				<th>Материал</th>
				<th>Тоо/ш</th>
				<th>Үйлдвэр №</th>
				<th>Үйлдэл</th>
			</tr>

		</table>

	</div>
	<br>

	<div class="field">
		<label for="">Засвар үргэлжилсэн хугацаа:</label>
		<?=form_input('duration', null, "id='duration' size='8'"); ?> цаг:мин
	</div>

	<div class="field">
		<label for="">Гүйцэтгэсэн ИТА:</label>
		<?=form_dropdown('repairedby_id[]', $employee, null, "id='edit_repairedby_id' multiple='multiple' class='chosen-select' data-placeholder='ИТА-с нэгийг сонго.'"); ?>
	</div>


<?php echo form_close();?>

<!-- Параметр Засах -->

<?php echo form_open('#', array('name'=>'edit_parameter', 'id'=>'edit_parameter', 'title'=>'Шинэ төхөөрөмж нэмэх')); ?>
	
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

<?=form_open('#', array('name'=>'edit_material', 'id'=>'edit_material', 'title'=>'Шинэ материал нэмэх')); ?>

	<?php echo validation_errors();?>

	<?php echo form_hidden('device_id', $device->id);?>

	<?php echo form_hidden('id');?>

	<p class="feedback"></p>

	<div class="field">

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


<!-- SPARE DIALOG HERE -->

<?=form_open('#', array('name'=>'spare', 'id'=>'spare', 'title'=>'Сэлбэгийн материалын жагсаалтад нэмэх')); ?>

	<?php echo validation_errors();?>

	<?php echo form_hidden('device_id', $device->id);?>

	<?php echo form_hidden('id');?>

	<p class="feedback"></p>

	<div class="spare_field">

		<?=form_dropdown('spare_id', $wh_spare, null, "id='spare_id' class='chosen-select' required");  ?>

		<span for="author">Тоо23:</span>
		<?=form_input('qty', null, "id='qty_spare' size='4' required");  ?>

		<span for="author">Үйлдвэр №:</span>
		<?=form_input('part_number', null, "id='part_number_spare' size='20' required");  ?>

		<strong><a href="#" onclick="add_new_spare()">Шинэ +</a></strong>

	</div>
<?php echo form_close();?>


<!-- Засварыг жагсаалтад нэмэх диалог -->

<?=form_open('#', array('name'=>'spare', 'id'=>'spare_edit_list', 'title'=>'Сэлбэгийн материалын жагсаалтад нэмэх')); ?>

	<?php echo validation_errors();?>

	<?php echo form_hidden('device_id', $device->id);?>

	<?php echo form_hidden('id');?>

	<p class="feedback"></p>

	<div class="spare_field">

	
		<?=form_dropdown('spare_id', $wh_spare, null, "id='spare_id_add_list' class='chosen-select' required");  ?>

		<span for="author">Тоо213:</span>
		<?=form_input('qty', null, "id='qty_spare_add_list' size='4' required");  ?>

		<span for="author">Үйлдвэр №:</span>
		<?=form_input('part_number', null, "id='part_number_spare_add_list' size='20' required");  ?>

		<strong><a href="#" onclick="add_new_spare()">Шинэ +</a></strong>

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

<div class="field">

	<label for="section_id">Төхөөрөмж:</label>

	<?=form_input('device', $device->device, "id='device' disabled");  ?>

</div>

<div class="field">

	<label for="author">Төрөл:</label>

	<?=form_dropdown('eventtype_id',$eventtype, null, 'id=eventtype_id'); ?>

</div>

<div class="field" id="edit_passbook_all">

	<label for="author">Бүх дэд дэвтэрт хамаарна:</label>

	<?php

	$data = array(
    'name'        => 'passbook_all',
    'id'          => 'passbook_all_edit',
    'value'       => 'true',
    'checked'     => FALSE    
    );
	
	echo form_checkbox($data); ?> Тийм

</div>


<div class="field">

	<label for="author">Ажлын тодорхойлолт:</label>

	<textarea name='event' id='event' col="60" row="20" style="width: 270px; height: 70px;"></textarea>

</div>

<div class="field">

	<label for="intterupt">Үйлчилгээ тасалдах эсэх?</label>

	<?=form_dropdown('is_interrupt',$intterupt, null, 'id=is_interrupt'); ?>

</div>

<div class="field">

	<label for="author">Эхлэх огноо:</label>

	<td><input type="text" name="start" id="startdate_edit" size=16 /></td>

</div>

<div class="field">

	<label for="author">Дуусах огноо:</label>

	<input type="text" name="end" id="enddate" size=16 />

</div>


<div class="field">

	<label for="done">Гүйцэтгэл:</label>

	<textarea name='done' id='done' col="40" row="10" style="width: 270px; height: 70px;"></textarea>

</div>

<div class="field">

	<label for="">Гүйцэтгэсэн ИТА:</label>

	<?=form_dropdown('doneby_id[]', $employee, null, 'class="multiselect" data-placeholder="ИТА-с нэгийг сонго." multiple="multiple" id="doneby_ita_id"');  ?>

</div>


<div>
	<label for="">ТҮ-ны бүртгэл нээсэн ИТА:</label>

	<input type="text" name="createdby" id="createdby" disabled="true">

</div>

<?php echo form_close();?>


<!-- FORM EDIT MAINTENANCE -->
<?php echo form_open('#', array('name'=>'maintenance_load_id', 'id'=>'maintenance_load_id', 'title'=>'Т/Ү ЗАСАХ')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id);?>

<?php echo form_hidden('equipment_id', $device->equipment_id);?>

<?php echo form_hidden('location_id', $device->location_id);?>

<p class="feedback"></p>

<p class="warning"><i>Энд нэмсэн мэдээлэл нь "Техник Үйлчилгээ" модульд шууд нэмэгдэхийг анхаарна уу!</i></p>
<br>

<div class="field">

	<label for="section_id">Төхөөрөмж:</label>

	<?=form_input('device', $device->device, "id='device' disabled");  ?>

</div>


<h3>Техник үйлчилгээнүүд:</h3>
<br>

<div class="field">

	<?php //form_dropdown('event_id[]', $events, null, "id='id_events_id' multiple='multiple'");?>

	<?php 

    	$n=0;

       	foreach ($events as $event) { 

    		
    		?>
	<div style="overflow: auto; padding-bottom: 5px; border-bottom: 1px solid black">

		<div style="float:left"> <input type="checkbox" name="event_id[]" id="event_<?=$n;?>" value="<?=$event->id;?>">

			<label class="my_label" for="event_<?=$n;?>">
				<?php

					echo "Нээсэн:". date('Y-m-d H:i', strtotime($event->start)). '/ Хаасан:'. date('Y-m-d H:i', strtotime($event->end)). ' '.$event->title. ' Гүйцэтгэл: '.$event->done;
				?>
			</label>
		</div>
	</div>

	<?php 

    	$n++;

    	} ?>


</div>


<?php echo form_close();?>


<!-- Засварын бүртгэл -->
<?php echo form_open('#', array('name'=>'repair_load', 'id'=>'repair_load', 'title'=>'Засварын бүртгэл дуудах')); ?>

<?php echo validation_errors();?>

<?php echo form_hidden('device_id', $device->id, null, "id='device_id'");?>

<?php echo form_hidden('equipment_id', $device->equipment_id);?>

<?php echo form_hidden('location_id', $device->location_id);?>

<p class="feedback"></p>


<div class="field">

	<label for="section_id">Төхөөрөмж:</label>

	<?=form_input('device', $device->device, "id='device' disabled");  ?>

</div>


<h3>Засварын мэдээлэл:</h3>
<br>

<div class="field">

	<?php //form_dropdown('event_id[]', $events, null, "id='id_events_id' multiple='multiple'");?>

	<?php 

    	$n=0;

       	foreach ($logs as $log) { 
    		?>
	<div style="overflow: auto; padding-bottom: 5px; border-bottom: 1px solid black">

		<div style="float:left"> <input type="checkbox" name="log_id[]" id="log_<?=$n;?>" value="<?=$log->id;?>">

			<label class="my_label" for="log_<?=$n;?>">
				<?php

					echo "Нээсэн:". date('Y-m-d H:i', strtotime($log->created_dt)). '/ Хаасан:'. date('Y-m-d H:i', strtotime($log->closed_dt)). ' '.$log->comment. ' Гүйцэтгэл: '.$log->closed_comment;
				?>
			</label>
		</div>
	</div>

	<?php 

    	$n++;

    	} ?>


</div>

<?php echo form_close();?>


<!-- create -->
<?php echo form_open('#', array('name'=>'new-form', 'id'=>'new-form', 'title'=>'Шинэ сэлбэг')); ?>

<?php echo validation_errors();?>

<p class="feedback"></p>

<input type="hidden" name="equipment_id"
	value="<?php echo (isset($device->equipment->sp_id)) ?  $device->equipment->sp_id : $device->equipment->parent_id; ?>"
	id="sp_id">

<input type="hidden" name="section_id" value="<?=$device->section_id;?>">

<input type="hidden" name="sector_id" value="<?=$device->equipment->sector_id;?>">

<div class="field">

	<label for="section">Сэлбэг нэр:</label>

	<?php echo form_input('spare', null, "id='new_spare' size='30'");?>

</div>


<div class="field">
	<label for="part_number">Парт №:</label>
	<?php echo form_input('part_number', null, "id='part_number'");?>
</div>
<div class="field">
	<label for="sparetype">Төрөл:</label>
	<?php echo form_dropdown('type_id', $sparetype, null, 'id="type_id"');?>
</div>
<div class="field">
	<label for="measure">Хэмжих нэгж:</label>
	<?php echo form_dropdown('measure_id', $measure, null, 'id="measure_id"');?>
</div>
<div class="field">
	<label for="manufacture_id">Үйлдвэрлэгч:</label>
	<?php echo form_dropdown('manufacture_id', $manufacture, null, 'id="manufacture_id"');?>
</div>

<?php echo form_close();?>
<!-- edit -->


<script type="text/javascript">
	$(document).ready(function () {

		$('.available').css('width', '250px');

		$('.selected').css('width', '250px');

		$('#id_events_id').css('width', '500px');
		$('#id_events_id').css('max-width', '500px');

	});
</script>