<?php
$p = array ();
$training = array ();
// (object)$training=(object)$out->data['training_json'];
// var_dump($out->data);
 // var_dump($out);

$json_array = json_decode ( $out->data ['training_json'] );
$json_education = json_decode ( $out->data ['trainer_education'] );
	
$print_history = isset($out->data ['print_history']) ? (  $out->data ['print_history'] ) : null ;
// print_r($json_array);

( object ) $p = ( object ) $out->data;

if (!$p->trainer_id) {
	echo "<div id='message' align='center'>";
	echo "<p>Таны бүртгэл Сургалтын бүртгэлд бүртгэгдээгүй байгаа тул инженеринг сургалтын хэсэгт хандана уу?</p>";
	echo "</div>";
} else {
	?>
<style>
.main {
	width: 100%;
	margin: 0 auto;
	text-align: center;
}

.anket td {
	text-align: left;
	text-indent: 1.5em;
}
</style>
<div class="main clearfix" >

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Инженер/техникчийн анкет</a></li>
			<li><a href="#tabs-2">Үнэмлэх хэвлэлтийн мэдээлэл</a></li> 
	
		</ul>
		<div id="tabs-1">

			<table class='anket' align="center" width="70%" border="0"
				cellpadding="5" cellspacing="5">
				<tr>
					<th colspan="2">
						<h2>АЭРОНАВИГАЦИЙН ИНЖЕНЕР/ТЕХНИКЧИЙН АНКЕТ</h2>
					</th>
				</tr>
				<tr>
					<th colspan="2"><hr></th>
				</tr>
				<tr>
					<th colspan="2">1.ИНЖЕНЕР/ТЕХНИКЧИЙН ТАЛААРХ МЭДЭЭЛЭЛ</th>
				</tr>
				<tr>
					<td colspan='2'>1.1.Регистрийн дугаар:<?=$p->register?></td>
				</tr>
				<tr>
					<td>1.2.Эцэг эхийн нэр:<i><strong><?=$p->lastname?></strong></i></td>
					<td>Нэр:<strong><?=$p->firstname?></strong></td>
				</tr>
				<tr>
					<td>1.3.Хүйс:<strong><?=$p->gender?></strong></td>
					<td>1.4.Төрсөн он-сар-өдөр <strong><?=$p->birthdate?></strong></td>
				</tr>
				<tr>
					<td>1.5.Харьяа Байгууллага:<strong><?=$p->location?></strong></td>
					<td>1.6.Байршил: <strong><?=$p->location?></strong></td>
				</tr>
				<tr>
					<td>1.7.Хэсэг, тасаг: <strong><?=$p->section?></strong></td>
					<td>1.8.Албан тушаал: <strong><?=$p->position?></strong>
					
					<td>
				
				</tr>
				<tr>
					<td>1.9.Ажлын утас:<strong><?=$p->workphone?></strong></td>
					<td>1.10.Гар утас:<strong><?=$p->phone?></strong></td>
				</tr>
				<tr>
					<td colspan="2">1.11.Имэйл хаяг: <strong><?=$p->email?></strong></td>
				</tr>
				<tr>
					<td>1.12.Онцгой шаардлага гарвал харилцах хүн (<strong><?=$p->rel_type?></strong>)
					</td>
					<td>Холбогдох хүний утас: <strong><?=$p->rel_phone?></strong></td>
				</tr>
				<tr>
					<td>1.13.Мэргэжил: <strong><?=$p->occupation;?></strong></td>
					<td>1.14.Боловсрол: <strong><?=$p->education?></strong>
					</td>
				</tr>
				<tr>
					<td>1.15.Мэргэжлийн үнэмлэх: <strong><?=$p->license_no;?></strong></td>
					<td>1.16.Үнэмлэхний төрөл: <strong><?=$p->license_type;?></strong></td>
				</tr>
				<tr>
					<td>Хэрэв цахилгааны инженер/техникч бол АА үнэмлэх:<strong><?=$p->aa_license;?></strong></td>
					<td>АА групп:<strong><?=$p->aa_group;?></strong></td>
				</tr>
				<tr>
					1.17.Үнэмлэх олгосон хугацаа:
					<strong><?=$p->issued_date;?></strong>
					</td> 1.18.Үнэмлэхний хүчинтэй хугацаа:
					<strong><?=$p->valid_date;?></strong>
					</td>
				</tr>
				<tr>
					<td colspan="2">1.19.Ажиллах тоног төхөөрөмжүүд:<strong><?=$p->license_equipment?></strong></td>
				</tr>

				<tr>
					<td colspan="2"><h3 align='center'>2.БОЛОВСРОЛЫН ТАЛААРХ МЭДЭЭЛЭЛ</h3>
						Боловсролын /ерөнхий, тусгай дунд, дээд боловсрол, дипломын,
						баклаврын болон магистрийн зэргийг оролцуулан/
						<table align='center' border="1" cellpadding="0" cellspacing="0"
							width="90%">
							<tr>
								<th>Сургуулийн нэр</th>
								<th>Орсон он, сар</th>
								<th>Төгссөн он, сар</th>
								<th>Эзэмшсэн боловсрол, мэргэжил</th>
							</tr>
							<?php
			if (isset ( $json_education )) {
				foreach ( $json_education as $key => $value ) {
					foreach ( $value as $row ) {
						echo "<tr>";
						echo "<td>" . $row->school . "</td>";
						echo "<td>" . $row->entered . "</td>";
						echo "<td>" . $row->finished . "</td>";
						echo "<td >" . $row->education . "</td>";
						echo "</tr>";
					}
				}
			}
			?>
						</table></td>
				</tr>
				<tr>
					<td colspan="2"><h3 align="center">3.МЭРГЭШЛИЙН БЭЛТГЭЛИЙН ТАЛААРХ
							МЭДЭЭЛЭЛ</h3> 3.1. Мэргэшлийн бэлтгэл /Мэргэжлийн болон бусад
						чиглэлээр мэргэшүүлэх сургалтанд хамрагдсан байдлыг бичнэ/

						<table align='center' border="1" cellpadding="0" cellspacing="0"
							width="100%">
							<tr>
								<th>Тушаалын дугаар</th>
								<th style="width: 90px;">Огноо</th>
								<th>Сургалт</th>
								<th style="width: 120px;">Төрөл</th>
								<th>Хугацаа /цагаар/</th>
								<th>Газар</th>
							</tr>
							
							<?php
								if (isset ( $json_array )) {
									foreach ( $json_array as $key => $value ) {
										foreach ( $value as $row ) {
											echo "<tr>";
											echo "<td>" . $row->orderN . "</td>";
											echo "<td>" . $row->date . "</td>";
											echo "<td>" . $row->training . "</td>";
											echo "<td >" . $row->type . "</td>";
											echo "<td>" . $row->time . "</td>";
											echo "<td>" . $row->place . "</td>";
											echo "</tr>";
										}
									}
								}
								?>
						</table></td>
				</tr>

			</table>
		
		</div>

		<!-- Үнэмлэх хэвлэлтийн мэдээлэл энд гарна -->

		<div id="tabs-2">

			<?php
	
				if(isset($print_history)){			?>
					<table>
						<tr>
							<td>Инженер техникийн ажилтны нэр: <strong><?=$print_history[0]->trainer;?></strong></td>
							
							<td>Үнэмлэхний дугаар: <strong><?=$print_history[0]->license_number;?></strong></td>
						</tr>
						
					
					<table align='center' border="1" cellpadding="0" cellspacing="0"
							width="100%">
							<tr>
								<th>Хуудасны дугаар</th>								
								<th>Хэвлэгдсэн агуулга</th>								
								<th>Хэвлэсэн Технологич Инженер</th>
								<th>Хэвлэсэн огноо</th>
							</tr>

						<?php 
							foreach ( $print_history as $row ) {
								echo "<tr>";
								echo "<td>" . $row->page_number . "</td>";
								echo "<td>" . $row->page. "</td>";
								echo "<td>" . $row->printed_by . "</td>";
								echo "<td >" . $row->printed_date . "</td>";
								echo "</tr>";
							} 
							?>
			<?php 							

				}else{ ?>

								
					<p style="margin-top:20px;" class="warning">
						
						Таны үнэмлэхний хэвлэгдээгүй тул мэдээлэл хадгалагдаагүй байна! Технологич инженертэй холбоо барина уу!
						
					</p>

		<?php 

				}

			?>
				
		</div>


	</div>
	

</div>
<?php
}
?>