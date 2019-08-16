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
	
	.main{

		width: 100%;
		margin: 0 auto;
		text-align: center;
	}

	select {

		width: 40%;
		max-width: 25em;
	}

	.field{
		margin-bottom: 4px;
	}

	table.bordered {
		border-collapse: collapse;

	}

	table.bordered > thead > tr , th, td{
		padding:5px 0;
		    border: 1px solid #7a7a7a;
	}

	.noborder{
		text-align: center;
		border: none;
	}

	.noborder > tr {
		border: none;
	}

	.noborder >td {
		border: 1px solid #7a7979;
	}

	.wrapper{
		width: 90%;
	    margin: auto;
	}

	.my_label{
		padding: 0;
		width: 700px;
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
	<b>
		<?php if($device->certificate) echo $device->certificate->cert_no; 
			else echo "Гэрчилгээгүй байна";
	?>
	</b></p>
	<br>
	<p><b>Ашиглалтад орсон он: <?php echo $device->year_init;?></b></p>
	<br>
	<p><b>Байршил: <?=$device->location->location;?></b></p>
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
				<th>10</th>
				<th colspan="3"><?=$device->section->section;?>								
							</th>
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
					</tr>
					<?php $cnt = 0;

					$parameter = $this->parameter_model->get_many_by(array('passbook_id'=>$row->id));
					
					 foreach ($parameter as $prow) { 
						echo "<tr>";
						echo "<td>".++$cnt."</td>";
						echo "<td>$prow->parameters</td>";
						echo "<td>$prow->measure</td>";
						echo "<td>$prow->value</td>";
						echo "</tr>";
					} ?>
				</table>

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
						
						echo "</tr>";
					} ?>
					
				</table>
				
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
					</tr>
					<tr>
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
						<th>5</th>
						<th>6</th>
					</tr>
					
					<?php 
					$cnt = 0;

					 $event = $this->event_model->with('eventtype')->get_many_by(array('location_id'=>$device->location_id, 'equipment_id'=>$device->equipment_id, 'device_id'=>$device->id, 'passbook_id'=>$row->id)); 

					foreach ($event as $erow) {

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
					echo "<td>".$erow->title.". Гүйцэтгэл:".$erow->done."</td>";

					if($itas){

					echo "<td>";

						foreach ($itas as $ita) {

							echo $ita->employee; echo ", ";
							
						}

					echo "</td>";

				}else echo "<td></td>";

						
						echo "</tr>";
						
					} ?>
					
			
				</table>


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
						<th colspan="3" width="30%" >Засварын материал, сэлбэгийн зарцуулалт</th>
						<th rowspan="2">Засварын үргэлжилсэн хугацаа</th>
						<th rowspan="2">Гүйцэтгэгчийн гарын үсэг</th>
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

							foreach ($rep->repair_employee as $employee ) {
									echo $employee->employee; echo ". ";
								} echo "</td>";		

							echo "</tr>";

						}

					}

					?>

				</table>
				<br>
				
		    
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

