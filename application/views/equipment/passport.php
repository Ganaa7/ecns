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
				<th><?=$device->country->country;?></th>
			</tr>
			<tr>
				<th colspan="2">Үйлдвэр, компани</th>
				<th>04</th>
				<th><?=$device->manufacture->manufacture;?></th>
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
				<th colspan="3"></th>
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

		<!-- device parameters -->
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

			// var_dump($parameter);
			
			 foreach ($parameter as $row) { 
				echo "<tr>";
				echo "<td>".++$cnt."</td>";
				echo "<td>$row->parameters</td>";
				echo "<td>$row->measure</td>";
				echo "<td>$row->value</td>";
				echo "</tr>";
			} ?>
		</table>

		<br>
		<div>
		<a class="button" onclick="parameter_modal('<?=$device->device;?>')">Үзүүлэлт нэмэх</a>
		</div>
		
		<br><!-- device materials -->

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
			$cnt = 0;
			 foreach ($material as $row) { 
				echo "<tr>";
				echo "<td>".++$cnt."</td>";
				echo "<td>$row->materials</td>";
				echo "<td>$row->qty</td>";
				echo "<td>$row->part_number</td>";
				echo "</tr>";
			} ?>
			
		</table>
		<br>
		<div>
		<a class="button" onclick="material_modal('<?=$device->device;?>')" >Материал нэмэх</a>
		</div>
		
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
				<th>Гүйцэтгэгчийн гарын үсэг, албан тушаал</th>
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

			foreach ($event as $row) {

					$duration = (strtotime($row->end)-strtotime($row->start))/3600;

					echo "<tr>";
					echo "<td>".++$cnt."</td>";
					echo "<td>".$row->start."</td>";
					echo "<td>".$row->eventtype->eventtype."</td>";
					echo "<td>".$duration."</td>";
					echo "<td>".$row->title."</td>";
					echo "<td>".$row->createdby_id."</td>";
					echo "</tr>";
				
			} ?>
			
	
		</table>

		<br>		
		<br>		

		<h4>ЗАСВАРЫН БҮРТГЭЛ</h4>	

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
					echo "<td>".($rep->duration)."</td>";
					echo "<td>$rep->repairedby</td>";				

					echo "</tr>";

				}

			}else{
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
		</div>
		


	</div>


</div>

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
       <?=form_input('parameters', null, "id='parameters'");  ?>
    </div>
    <div class="field">
       <label for="author">Тоо ширхэг:</label>
       <?=form_input('measure', null, "id='measure'");  ?>
    </div>
       
  <div class="field">
    <label for="">Үйлдвэрийн №, Засварын №:</label>
    <?=form_input('value', null, "id='vaule'");  ?>
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
       <?=form_input('repair', null, "id='repair' size='40'");  ?>
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