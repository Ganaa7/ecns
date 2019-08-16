<?php
$p = array ();
$training = array ();

// var_dump($location[$out->trainer->location_id]);

// var_dump($organization);

// var_dump(intval($out->trainer->org_id));

( object ) $trainer = ( object ) $out->trainer;

( object ) $pos_history = ( object ) $out->pos_history;

( object ) $remarks = ( object ) $out->remarks;

$page_1 = array();
$page_2 = array();
$page_3 = array();

$cnt = 1;

$i =0; $j =0;

foreach ($out->exam_history as $row) {
	//page 1
	$i++; 

	if( $i <= 5){

	   $test1['code'] = $row->license_equipment->code;
	 
	   $test1['exam_date']= date("Y/m/d",strtotime($row->exam_date));

	   $test1['valid_date'] = date("Y/m/d",strtotime($row->valid_date));

	   array_push ( $page_1, $test1 );

	}
	
	else if( $i>5 && $i<=10 ){

	   $test2['code'] = $row->license_equipment->code;
	 
	   $test2['exam_date'] = date("Y/m/d",strtotime($row->exam_date));

	   $test2['valid_date'] = date("Y/m/d",strtotime($row->valid_date));

	   array_push ( $page_2, $test2 );

	}	
	else if( $i>10 && $i<=15 ){

	   $test3['code'] = $row->license_equipment->code;
	 
	   $test3['exam_date'] = date("Y/m/d",strtotime($row->exam_date));

	   $test3['valid_date'] = date("Y/m/d",strtotime($row->valid_date));

	   array_push ( $page_3, $test3 );

	}	
	else if( $i>15 && $i<=20 ){

	   $test4[$j++]['code'] = $row->license_equipment->code;
	 
	   $test4[$j++]['exam_date'] = date("Y/m/d",strtotime($row->exam_date));

	   $test4[$j++]['valid_date'] = date("Y/m/d",strtotime($row->valid_date));

	    array_push ( $page_4, $test4 );

	}
	    
}

$page_1 = (object)$page_1;

$page_2 = (object)$page_2;

$page_3 = (object)$page_3;

// echo count($test1);

( object ) $exam_history = ( object ) $out->exam_history;

$count_ph = $out->count_ph;

$count_exam = $out->count_exam;

$count_exam = $count_exam +2;

// echo $count_ph;

?>
<input type="hidden" name="license_no" id="license_no" value="<?=$trainer->license_no?>">
<input type="hidden" name="firstname" value="<?=$trainer->firstname?>">
<input type="hidden" name="lastname" value="<?=$trainer->lastname?>">
<input type="hidden" name="birthdate">

<input type="hidden" name="education" value="<?=$trainer->education?>">
<input type="hidden" name="nationality" value="<?=$trainer->nationality?>">
<input type="hidden" name="register" value="<?=$trainer->register?>">

<!-- trainer_id -->
<input type="hidden" name="trainer_id" id="trainer_id" value="<?=$this->input->get_post('id');?>">

<!-- printed_user_id -->
<input type="hidden" name="user_id" id="user_id" value="<?=$this->session->userdata('employee_id');?>">

<!-- Олгосон огноо -->
<input type="hidden" name="issued_date" value="<?=$trainer->issued_date?>">

<!-- Үнэмлэх төрөл -->
<input type="hidden" name="licence_type" value="<?=$trainer->license_type?>">

<!-- Анх олгосон огноо -->
<input type="hidden" name="initial_date" value="<?$trainer->initial_date?>">

<div class="main">
		<br>
		<br>
		<h4>АГААРЫН НАВИГАЦИЙН ИНЖЕНЕР ТЕХНИКИЙН АЖИЛТНЫ МЭРГЭЖЛИЙН ҮНЭМЛЭХ</h4>
		<br>

		<div class="lp-wrapper">

			<table width="100%" cellpadding="0" cellspacing="0" class="first">
					<tr>
						<td align="center" style="padding:20px;">
							<div style="margin-bottom: 20px;">
							I. <span class="title">MONGOLIA/ </span><span class="title_mn">Монгол Улс</span>
							</div>
							<div>
							<div class="title">II. AIR TRAFFIC SAFETY ELECTRONICS PERSONNEL LICENCE/ </div>
							<div class="title_mn">Агаарын навигацийн инженер техникийн ажилтны мэргэжлийн үнэмлэх</div>
							</div>

							<img src="<?=base_url();?>images/caam.png"  style="margin:20px 0;" alt="">

							<div class="title">
								This licence is valid only in endorsement of authorized offcials of Civil Aviation Authority of Mongolia
							</div>
							<div>
								Энэхүү үнэмлэх нь ИНЕГ-аас олгосон эрх бүхий албан тушаалтны гарын үсгээр баталгаажсан байна.
							</div>

							<div class="title" style="margin-top: 10px;">
								This licence is issued without a specific expiration date
							</div>
							<div>
								Энэ үнэмлэхийг хүчинтэй хугацаа үл тогтоон олгов
							</div>

						</td>

					</tr>
				<tr>

			</table>

			<!-- Printable page here -->

			<div id="page_0" onafterprint="myFunction()">

				<table width="100%" cellpadding="0" cellspacing="0" class="license" id="front_page">

					<tr >
						<td colspan="3" rowspan="2" height="40%">
							<div style="padding: 5px;">
								<span class="title">ISSUING OFFICIAL / </span><span class="title_mn">Олгосон албан тушаалтан</span>
								<span class="data"></span>
							</div>
							<div style="padding: 5px;">
								<span class="title">STAMP / </span><span class="title_mn">Тамга</span>
								<span class="data"></span>
							</div>
							<div style="padding: 5px;">
								<span class="title">SIGNATURE / </span><span class="title_mn">Гарын үсэг</span>
								<span class="data"></span>
							</div>
						</td>
						<td rowspan="2" width="5%">
							<span class="title">XI</span>
						</td>

						<td align="left">
							<div>
								<span class="title">SIGNATURE OF HOLDER / </span><span class="title_mn">Эзэмшигчийн гарын үсэг</span>
								<span class="data"></span>
							</div>
						</td>
						<td width="5%">
							<span class="title">VIII</span>
						</td>
					</tr>
					<tr>
						<td align="left" width="50%">
							<span class="title">ISSUED BY CAAM IN ACCORDANCE WITH THE MCAR 171 / </span><span class="title_mn">ИНЕГ-аас ИНД 171-ийн дагуу олгов/</span>
							<span class="data"></span>
						</td>

						<td>
							<span class="title">IX</span>
						</td>
					</tr>

					<tr>
						<td colspan="3">

								<div class="data" id="_issued_date">

									<?=$trainer->issued_date;?>

								</div>

								<span class="title">DATE OF ISSUE / </span><span class="title_mn">Олгосон огноо</span>
						</td>
						<td>
								<span class="title">X</span>
						</td>

						<td align="left">							
								<div id="register" class="data"><?=$trainer->register;?></div>
								<span class="title">REGISTRATION NUMBER / </span><span class="title_mn">Регистрийн дугаар</span>

							

								<div id="nationality" class="data"><?=$trainer->nationality;?></div>
								<span class="title">NATIONALITY / </span><span class="title_mn">Иргэний харьяалал</span>
							
						</td>
						<td>
							<span class="title">VII</span>
						</td>

					</tr>

					<tr>
						<td width="35%" rowspan="4"><span class="title">Photo/ </span><span class="title_mn">Зураг</span></td>

						<td  colspan="4" align="left">

							<div id="education" class="data">

								<?=$trainer->education;?>

								</div>

							<span class="title">EDUCATION / </span><span class="title_mn">Төгссөн сургууль, мэргэжил, зэрэг</span>

						</td>
						<td >
							<span class="title">VI</span>
						</td>
					</tr>

					<tr>

						<td colspan="4" align="left">
							<div id="datebirth" class="data"><?=$trainer->birthdate;?></div>
							<span class="title">DATE OF BIRTH/ </span><span class="title_mn">Төрсөн огноо</span>

						</td>
						<td>
							<span class="title">V</span>
						</td>
					</tr>

					<tr>
						<td colspan="4" align="right">

							<div class="data" id="name">

								<?=$trainer->lastname;?> <?=$trainer->firstname;?>

							</div>
							<span class="title">LAST & FIRST NAME OF HOLDER/ </span>
							<span class="title_mn">Эзэмшигчийн овог, нэр</span>
						</td>

						<td>
							<span class="title">IV</span>
						</td>

					</tr>

					<tr>
						<td colspan="4" align="left">

							<div id="number" class="data">

								<?=$trainer->license_no;?>

								</div>

							<span class="title">LICENSE NUMBER/ </span><span class="title_mn">Үнэмлэхний дугаар</span>

						</td>

						<td>
							<span class="title">III</span>
						</td>

					</tr>

				</table>

			</div>

			<div class="center" style="margin-top: 10px">
				<?php 
				$error = array();
				
				if($trainer->birthdate=='0000-00-00')

				   array_push($error, 'Төрсөн өдөр');

				 if(!$trainer->education)					
					
					array_push($error, 'Боловсрол');
					
				 if(!$trainer->nationality)
					
				    array_push($error, 'Иргэний харьяалал'); 

				 if(!$trainer->register)
					
				    array_push($error, 'Регистрийн дугаар');


				if(sizeof($error)>0){

					foreach ($error as $key) {						

						echo "<ul style='color:red'>";

							echo "<li>".$key."</li>";

						echo "</ul>";
					}

					echo "<b style='color:red'>Дээрх утгууд буруу байна! Засаж оруулсан хойно хэвлэх боломжтой! </b>";
					echo "<br>";
					echo "<div>";
					echo "<button id='add_info' class='button' onclick='add_info()'>Мэдээлэл засах</button>";
					echo "<a class='button' href=".base_url('/training')."> Буцах </a>";
					echo "</div>";
				}

				?>


				<?php if(!sizeof($error)>0){					
				?>

				<div>

					<button id="add_info" class="button" onclick="add_info()">Мэдээлэл засах</button>

					<button class='button page_break' onClick="printPage('page_0')">Нүүр хуудас 1</button>

					<button class='button page_break' onClick="printPage('page_0')">Нүүр хуудас 2</button>

				</div>

				<?php } ?>

			</div>

			<br>
			<br>

			<div id="printable_page2">

				<table width="100%" cellpadding="0" cellspacing="0" class="position" id="position">
						<tr>
							<td width="5%">
								<span class="title">XII</span>
							</td>
							<td colspan="2" align="left">
								<span class="title">LICENSE TYPE/ </span><span class="title_mn">Үнэмлэхний төрөл</span>
								<span class="title">INITIAL DATE OF ISSUE/ </span>
								<span class="title_mn">Анх олгосон огноо</span>
								<div class='data-license_'>
									<span class="data" style="margin-left:50px">
									<?=$trainer->license_type;?></span>
									<span class="data" style="margin-left:180px"><?=$trainer->initial_date;?></span>
								</div>
							</td>

						</tr>
						<tr class="section-2">
							<td width="5%">
								<span class="title">XIII</span>
							</td>
							<td colspan="2" align="left">
								<span class="title">This license holder is granted to exercise the duties and act as prescribed under the items II and XII.</span>
								<div class="title_mn">Энэхүү үнэмлэх эзэмшигч нь II, XII Заалтуудыг гүйцэтгэх эрхтэй болно.</div>
							</td>
						</tr>
						<tr id="position_header">
							<td width="5%">
								<span id="position_id" class="title">XIV</span>
							</td>
							<td align="left">
								<span id="position_title" class="title">POSITION /</span>
								<span id="position_name" class="title_mn">Албан тушаал.</span>
							</td>
							<td  align="left">
								<span span class="title">DATE OF APPOINTMENT /</span><span class="title_mn">Томилогдсон огноо.</span>
							</td>
						</tr>
						<?php

							$i =1; $cnt=0;

							// var_dump($pos_history);

							$location = array(15, 19, 21, 27, 30, 33, 34, 35, 36);
							
							foreach($pos_history as $row){

								// Count ni 7s baga buyu tentsuu tohioldold

								if($count_ph<7){

									echo "<tr>";
									$cnt++;

									if($i==1){

										echo "<td rowspan='$count_ph' width='5%'>";
											echo "<span class='title'>XIV</span>";
										echo "</td>";
										if(strlen($row->position_detail->detail)>90){
											echo "<td><div id='position-name' style='font-size:8pt;'>";
												echo $row->position_detail->detail;
												echo "</div>";
												if (!in_array($out->trainer->location_id, $location))
												echo "<div id=pos_loc_id>/".$organization[intval($out->trainer->org_id)]."/</div>";
												echo "</td>";
										}
										else{ 
											echo "<td>";
												echo "<div id='position-name' style='padding-top:5px;'>".$row->position_detail->detail."</div>";
												
												if (!in_array($out->trainer->location_id, $location))
												echo "<div id=pos_loc_id>/".$organization[intval($out->trainer->org_id)]."/</div>";
												echo "</td>";
										}
													


					   				if(strlen($row->position_detail->detail)>90)
					   				  echo "<td><div id='position-date' style='font-size:8pt;'>".$row->appointed_date."</div></td>";
					   				else
					   				  echo "<td><div id='position-date' style='padding-top:5px;'>".$row->appointed_date."</div></td>";

					   				$i=0;

									}else{

										//indicate last row
										if($cnt==$count_ph){
											if(strlen($row->position_detail->detail)>90)
											echo "<td valign='top'><div class='last-left' style='font-size:8pt; '>".$row->position_detail->detail."</div></td>";
											else
											echo "<td valign='top'><div class='last-left' style='padding-top:5px;'>".$row->position_detail->detail."</div></td>";
						   				echo "<td valign='top'><div class='last-right'>".$row->appointed_date."</div></td>";	

						   			}else{
											if(strlen($row->position_detail->detail)>90)
						   				echo "<td valign='top'><div class='row-left' style='font-size:8pt;'>".$row->position_detail->detail."</div></td>";
						   				else
						   				echo "<td valign='top'><div class='row-left' style='padding-top:5px;'>".$row->position_detail->detail."</div></td>";

						   				echo "<td valign='top'><div class='row-right'>".$row->appointed_date."</div></td>";		

						   			}					   			

					   			}
					   			echo "</tr>";		
								}							

				   		}
				   		
				   	?>

					</table>
			</div>

			<div class="center" style="margin-top: 20px; margin-bottom: 20px;">
				<button id='add_history' onclick="add_history()">Ажлын түүх нэмэх</button>
				<button class='page_break' onClick="printPage('printable_page2')"">III-р Хуудсыг бүхэлд нь хэвлэх</button>
				<button class='page_break' onClick="printLastPage('printable_page2')"">III-р Хуудсын сүүлчийн мөрийг хэвлэх</button>
			</div>


			<!-- Page 4  -->

			<!-- if Page 4 is set -->
			<?php if($page_1) { 

			?>

			<div id="printable_page5">

				<table width="100%" cellpadding="0" cellspacing="0" class="license" id="table_exam">
						<tr>
							<td rowspan="<?=$count_exam;?>"  width="5%">
								<span class="title">XV</span>
							</td>
							<td class="section-3" colspan="4" align="left"><span class="title">TECHNICAL COMPETENCY EMDORSEMENT AND VALIDITY/ </span><span class="title_mn section-3">Тусгай зөвшөөрөл, баталгаажилт</span>
							</td>
						</tr>
						<tr>
							<td align="left" width="31%" class="section-3" >
								<span class="title">Technical Competency /</span>
								<div class="title_mn">Ажиллах тоног төхөөрөмж.</div>
							</td>
							<td align="right" width="13%" class="section-3" >
								<span class="title">Date of exam /</span><div class="title_mn">Шалгасан огноо.</div>
							</td>
							<td align="left" width="13%" class="section-3" >
								<span class="title">Valid until /</span><div class="title_mn">Хүртэл.</div>
							</td>
							<td align="left" class="section-3">
								<span class="title">Signature and stamp /</span><div class="title_mn">Гарын үсэг, тамга aa</div>
							</td>
						</tr>
							<?php
							$i =1; $counter=0;

							$range_count = sizeof((array)$page_1);


							foreach ($page_1 as $row) {

								$counter++;

								echo "<tr class='section-exam'>";

								if($i==1){

								if(strlen($row['code'])>35)
									echo "<td class='exam-fr minify'><div class='ex-left'>".$row['code']."</div></td>";
								else echo "<td class='exam-fr'><div class='ex-left'>".$row['code']."</div></td>";

				   				echo "<td class='exam-fr'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
				   				echo "<td class='exam-fr'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
				   				echo "<td class='exam-fr'></td>";

				   				$i=0;

								}else{

									if($counter==$range_count){ //indicates last row

										if(strlen($row['code'])>35)
						   					echo "<td class='last-exam minify'><div class='ex-left'>".$row['code']."</div></td>";
						   				else echo "<td class='last-exam'><div class='ex-left'>".$row['code']."</div></td>";
					   				echo "<td class='last-exam'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
					   				echo "<td class='last-exam'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
					   				echo "<td class='last-exam'></td>";

					   			}else{

					   				if(strlen($row['code'])>35)
						   				echo "<td class='exam minify'><div class='ex-left'  style='padding:0px 0;'>".$row['code']."</div></td>";
						   			else
						   				echo "<td class='exam'><div class='ex-left'>".$row['code']."</div></td>";
					   				echo "<td class='exam'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
					   				echo "<td class='exam'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
					   				echo "<td class='exam'></td>";

					   			}

				   			}
				   			echo "</tr>";
				   		}

		   		?>
				</table>

			</div>

			<div class="center" style="margin-top: 10px">
				
				<button id='exam_history' class="exam_history_add">Шалгалтын түүх нэмэх</button>

				<button class='page_break' onClick="printPage('printable_page5')"">V-р Хуудсыг бүхэлд нь хэвлэх</button>
				<button class='page_break' onClick="printLastPage('printable_page5')"">V-р Хуудсын сүүлчийн мөрийг хэвлэх</button>
			</div>

			<br>


			<?php } ?>
		

			<!-- end Page 4 -->

			<!-- if Page 5 is set rotate the page -->
			<?php

			 $range_count = sizeof((array)$page_2);

			 if($page_2&&$range_count>0) { 
				
			?>

			<div id="printable_page6">

				<table width="100%" cellpadding="0" cellspacing="0" class="license" id="page_6">

					<tr>							
						<td width="5%" rowspan="<?=$count_exam;?>">
								<span class="title">XV</span>
							</td>
						<td class="section-6" colspan="4" align="left"><span class="title">TECHNICAL COMPETENCY EMDORSEMENT AND VALIDITY/ </span><span class="title_mn section-6">Тусгай зөвшөөрөл, баталгаажилт</span>
						</td>
					</tr>
					<tr>
						<td align="left" width="31%" class="section-6" >
							<span class="title">Technical Competency /</span>
							<div class="title_mn">Ажиллах тоног төхөөрөмж.</div>
						</td>
						<td align="right" width="12%" class="section-6" >
							<span class="title">Date of exam /</span><div class="title_mn">Шалгасан огноо.</div>
						</td>
						<td align="left" width="12%" class="section-6" >
							<span class="title">Valid until /</span><div class="title_mn">Хүртэл.</div>
						</td>
						<td align="left" width="44%" class="section-6">
							<span class="title">Signature and stamp /</span><div class="title_mn">Гарын үсэг, тамга aa</div>
						</td>
					</tr>
					<?php
						$i =1; $counter=0;

						$rest_count = 5-$range_count;

						$set_rowspan = false;

				
						 $page_2 = (array)$page_2;

						foreach ($page_2 as $row) {

							$counter++;

							echo "<tr class='section-exam'>";

							if($i==1){
							

								if(strlen($row['code'])>35)
									echo "<td class='exam-fr minify'><div class='ex-left'>".$row['code']."</div></td>";

								else echo "<td class='exam-fr'><div class='ex-left'>".$row['code']."</div></td>";


								echo "<td class='exam-fr'><div class='p6-row-mid'>".date("Y/m/d",strtotime($row['exam_date']))."</div></td>";


								echo "<td class='exam-fr'><div>".date("Y/m/d",strtotime($row['valid_date']))."</div></td>";


								echo "<td width='6%' class='exam-fr'></td>";


				   				$i=0;

							}else{

								if($counter==$range_count){ //indicates last row


									if(strlen($row['code'])>35)
					   					echo "<td class='last-exam minify'><div class='p6-row'>".$row['code']."</div></td>";

					   				else echo "<td class='last-exam'><div class='p6-row'>".$row['code']."</div></td>";
						   												
									
									echo "<td class='last-exam'><div>".date("Y/m/d",strtotime($row['exam_date']))."</div></td>";

									echo "<td class='last-exam'><div>".date("Y/m/d",strtotime($row['valid_date']))."</div></td>";

									echo "<td class='last-exam'></td>";


					   			}else{

					   				if(strlen($row['code'])>35)

						   				echo "<td class='exam minify'><div class='p6-row'  style='padding:0px 0;'>".$row['code']."</div></td>";
						   			else
						   				echo "<td class='exam'><div class='p6-row'>".$row['code']."</div></td>";
					   				
					   				echo "<td class='exam'><div class='p6-row-mid'>".date("Y/m/d",strtotime($row['exam_date']))."</div></td>";
					   				
					   				echo "<td class='exam'><div class=''>".date("Y/m/d",strtotime($row['valid_date']))."</div></td>";
					   				

					   				echo "<td class='exam'></td>";


					   			}

				   			}
				   			echo "</tr>";
				   		}


				   		if($range_count<5){

							$col_range = $rest_count+$range_count+2;

							$set_rowspan = true;

							for ($i=0; $i <$rest_count; $i++) { 

								echo "<tr class='section-exam'>";
								
																						
								echo "<td class='exam-fr' width='5%'><div class='ex-left'>&nbsp;</div></td>";

				   				echo "<td class='exam-fr'></td>";
				   				echo "<td class='exam-fr'></td>";
				   				echo "<td class='exam-fr'></td>";

				   				echo "</tr>";
							}


						}

			   		?>


				</table>

			</div>

			<div class="center" style="margin-top: 10px">
				<button id='exam_history' class='exam_history_add'>Шалгалтын түүх нэмэх</button>

				<button class='page_break' onClick="printPage('printable_page6')"">VI-р Хуудсыг бүхэлд нь хэвлэх</button>
				<button class='page_break' onClick="printLastPage('printable_page6')"">VI-р Хуудсын сүүлчийн мөрийг хэвлэх</button>
			</div>

			<?php } ?>
			<br>
			<br>

			<!-- end Page 6 -->


			<!-- start page 7 -->

			<?php

			 $range_count = sizeof((array)$page_3);

			 if($page_3&&$range_count>0) { 

			?>

			<div id="printable_page7">

				<table width="100%" cellpadding="0" cellspacing="0" class="license" id="table_exam">
						<tr>
							<td rowspan="<?=$count_exam;?>"  width="5%">
								<span class="title">XV</span>
							</td>
							<td class="section-3" colspan="4" align="left"><span class="title">TECHNICAL COMPETENCY EMDORSEMENT AND VALIDITY/ </span><span class="title_mn section-3">Тусгай зөвшөөрөл, баталгаажилт</span>
							</td>
						</tr>
						<tr>
							<td align="left" width="31%" class="section-3" >
								<span class="title">Technical Competency /</span>
								<div class="title_mn">Ажиллах тоног төхөөрөмж.</div>
							</td>
							<td align="right" width="13%" class="section-3" >
								<span class="title">Date of exam /</span><div class="title_mn">Шалгасан огноо.</div>
							</td>
							<td align="left" width="13%" class="section-3" >
								<span class="title">Valid until /</span><div class="title_mn">Хүртэл.</div>
							</td>
							<td align="left" class="section-3">
								<span class="title">Signature and stamp /</span><div class="title_mn">Гарын үсэг, тамга aa</div>
							</td>
						</tr>
							<?php
							$i =1; $counter=0;

							foreach ($page_3 as $row) {

								$counter++;

								echo "<tr class='section-exam'>";

								if($i==1){

								if(strlen($row['code'])>35)
									echo "<td class='exam-fr minify'><div class='ex-left'>".$row['code']."</div></td>";
								else echo "<td class='exam-fr'><div class='ex-left'>".$row['code']."</div></td>";

				   				echo "<td class='exam-fr'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
				   				echo "<td class='exam-fr'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
				   				echo "<td class='exam-fr'></td>";

				   				$i=0;

								}else{

									if($counter==$range_count){ //indicates last row

										if(strlen($row['code'])>35)
						   					echo "<td class='last-exam minify'><div class='ex-left'>".$row['code']."</div></td>";
						   				else echo "<td class='last-exam'><div class='ex-left'>".$row['code']."</div></td>";
					   				echo "<td class='last-exam'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
					   				echo "<td class='last-exam'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
					   				echo "<td class='last-exam'></td>";

					   			}else{

					   				if(strlen($row['code'])>35)
						   				echo "<td class='exam minify'><div class='ex-left'  style='padding:0px 0;'>".$row['code']."</div></td>";
						   			else
						   				echo "<td class='exam'><div class='ex-left'>".$row['code']."</div></td>";
					   				echo "<td class='exam'>".date("Y/m/d",strtotime($row['exam_date']))."</td>";
					   				echo "<td class='exam'>".date("Y/m/d",strtotime($row['valid_date']))."</td>";
					   				echo "<td class='exam'></td>";

					   			}

				   			}
				   			echo "</tr>";
				   		}

		   		?>
				</table>

			</div>

			<div class="center" style="margin-top: 10px">
				
				<button id='exam_history' class="exam_history_add">Шалгалтын түүх нэмэх</button>

				<button class='page_break' onClick="printPage('printable_page7')"">VII-р Хуудсыг бүхэлд нь хэвлэх</button>
				<button class='page_break' onClick="printLastPage('printable_page7')"">VII-р Хуудсын сүүлчийн мөрийг хэвлэх</button>
			</div>
			<br>
			<?php } ?>
		
			<!--end page 7-->

			<!-- begin remarks page -->
		
			<div id="printable_page8">

				<table width="100%" cellpadding="0" cellspacing="0" class="license" id="page_8">

					<tr>							
						<td width="5%" rowspan="<?=$count_exam;?>">
								<span class="title">XVI</span>
							</td>
						<td class="section-6" colspan="4" align="left"><span class="title">SPECIAL REMARKS</span><span class="title_mn section-6">/Тусгай тэмдэглэл</span>
						</td>
					</tr>
					
					<?php

						$i =1; $counter=0;

						$rest_count = 5-$range_count;

						$set_rowspan = false;
				
						$page_2 = (array)$page_2;
					
							$i =1; $counter=0;

							foreach ($remarks as $remark) {

								$counter++;

								echo "<tr>";
																
								echo "<td class='remark'>".$remark->remark."</td>";
							
								echo "</tr>";
								
							}

		   		?>

				</table>

			</div>

			<div class="center" style="margin-top: 10px">
				<button id='special_mark' class='special_mark_add'>Тусгай тэмдэглэл нэмэх</button>

				<button class='page_break' onClick="printPage('printable_page8')"">Тусгай тэмдэглэлийг хэвлэх</button>

			</div>

			<!-- /remarks page -->

		</div>

		<br>
		<br>

</div>

	<!-- INFO VIEW DATA here -->

	<?php echo form_open('#', array('name'=>'info', 'id'=>'info', 'title'=>'Ажилтны мэдээлэл')); ?>

		<?php echo validation_errors();?>

		<?=form_hidden('trainer_id', $trainer->trainer_id);?>

		<p class="feedback"></p>

		<div class="field">

	      <label for="section_id">Мэргэжлийн үнэмлэх №:</label>
	      
	      <?php echo form_input('license_no', isset($trainer->license_no) ? $trainer->license_no : null, "id='license_no' placeholder='Үнэмлэхний №'" ); ?>

	    </div>

	 	<div class="field">

	      <label for="section_id">Эзэмшигчийн овог:</label>

	      <?php echo form_input('lastname', isset($trainer->lastname) ? $trainer->lastname : null, "id='lastname' placeholder='Овог бичнэ үү'"); ?>


	      <label for="section_id">Нэр:</label>
	      
	      <?php echo form_input('firstname', isset($trainer->firstname) ? $trainer->firstname : null, "id='firstname' placeholder='Нэр бичнэ үү'" ); ?>

	    </div>

	  	<div class="field">
	      
	      <label for="register">Регистрийн дугаар:</label>

	      <?php echo form_input('register', isset($trainer->register) ? $trainer->register : null, "id='register' placeholder='Регистр'");
	       ?>

	    </div>


	    <div class="field">
	      
	      <label for="section_id">Төрсөн огноо:</label>

	      <?php echo form_input('birthdate', isset($trainer->birthdate) ? $trainer->birthdate : null, "id='birthdate' placeholder='Регистр'");
	       ?>

	    </div>

	  	<div class="field">
	      <br>
	      <span>Төгссөн сургууль, мэргэжил, зэрэг:</span>

	      <?php echo form_textarea(
	      		array('name'=>'education', 
	      			  'id'=>'education',
	      			   'cols'=>64,
	      			    'rows'=>2,
	      			    'value'=>isset($trainer->education) ? $trainer->education : null
	      			)
	      	);
	      	?>
	    
	    </div>

	  	<div class="field">
	     
	      <label for="citizen">Иргэний харьяалал:</label>
	     
	      <?=form_input('nationality', isset($trainer->nationality) ? $trainer->nationality : 'Монгол', "id='nationality'");?>
	    
	    </div>  

	    <div class="field">
	     
	      <label for="citizen">Олгосон огноо:</label>
	     
	      <?php echo form_input('issued_date', isset($trainer->issued_date) ? $trainer->issued_date : null, "id='issued_date'");?>
	    
	    </div>	

<!-- 	    <div class="field">
	     
	      <label for="citizen">Хүчинтэй хугацаа:</label>
	     
	      <?php // echo form_input('valid_date', isset($trainer->valid_date) ? $trainer->valid_date : null, "id='valid_date'");?>
	    
	    </div>	 -->

	    <div class="field">
	     
	      <label for="citizen">Үнэмлэхний төрөл:</label>

	      <?php $type = array('CS'=>'CS-Холбоо', 'NS'=>'NS-Навигаци', 'SS'=>'SS-Ажиглалт', 'ES'=>'ES-Цахилгаан хангалт');

	      ?>
	     
	      <?=form_dropdown('license_type', $type, $trainer->license_type,  "id='license_type'");?>
	    
	    </div>  

	    <div class="field">
	     
	      <label for="citizen">Анх олгосон огноо:</label>
	     
	      <?php echo form_input('initial_date', isset($trainer->initial_date) ? $trainer->initial_date : null, "id='initial_date'");?>
	    
	    </div>

		<br>
	   
	<?php echo form_close();?>

		<!-- END VIEW HERE -->


	<!-- form created here -->

		<?php echo form_open('#', array('name'=>'create', 'id'=>'create', 'title'=>'Албан тушаалын түүх нэмэх')); ?>

		<?php echo validation_errors();?>

		<?php echo form_hidden('id', null);?>

		<p class="feedback"></p>

		   <?php if(isset($pos_history)){

		   	?>
		   	<div>
		   		<table>
		   			<tr>
		   				<th>Албан тушаал</th>
		   				<th>Томилогдсон огноо</th>
		   				<th>Үйлдэл</th>
		   			</tr>
		   		<?php foreach($pos_history as $row){
		   			echo "<tr>";
		   			echo "<td>".$row->position_detail->detail."</td>";
		   			echo "<td>".$row->appointed_date."</td>";
		   			echo "<td><input type='button' name='delete' class='btnDelete' id='".$row->id."' value='Устгах'></td>";
		   			echo "</tr>";
		   		}
		   		?>
		   			</tr>
		   		</table>

		   	</div>

		   <?php  } ?>
		   <hr>

		 	<div class="field">

		      <label for="section_id">Ажилтан:</label>
		      <?php echo form_input('name', $trainer->fullname, "disabled"); ?>

		      <?=form_hidden('employee_id', $trainer->trainer_id);?>

		   </div>

		  	<div class="field">
		      <label for="section_id">Албан тушаал:</label>
		      <?php echo form_dropdown('position_id', $position_detail, null, 'id="position_detail_id"');?>
		   </div>


		  	<div class="field">
		      <label for="equipment_id">Томилогдсон огноо:</label>
		      <?php echo form_input('appointed_date', null, "id='appointed_date'");?>
		    </div>

		<?php echo form_close();?>

		<!-- form ends here -->


		<!-- form created here -->

		<?php echo form_open('#', array('name'=>'exam', 'id'=>'exam', 'title'=>'Албан тушаалын түүх нэмэх')); ?>

		<?php echo validation_errors();?>

		<?php echo form_hidden('id', null);?>

		<p class="feedback"></p>
		   <?php if(isset($exam_history)){?>
		   	<div>
		   		<table>
		   			<tr>
		   				<th>Ажиллах тоног төхөөрөмж</th>
		   				<th>Шалгасан огноо</th>
		   				<th>Хүртэл</th>
		   				<th>Үйлдэл</th>
		   			</tr>
		   		<?php foreach($exam_history as $row){
		   			echo "<tr>";
		   			echo "<td>".$row->license_equipment->code."</td>";
		   			echo "<td>".$row->exam_date."</td>";
		   			echo "<td>".$row->valid_date."</td>";
		   			echo "<td><input type='button' class='deleteExam' id='".$row->id."' value='Устгах'></td>";
		   			echo "</tr>";
		   		}
		   		?>
		   			</tr>
		   		</table>

		   	</div>

		   <?php  } ?>
		   <hr>

		 	<div class="field">

		      <label for="section_id">Ажилтан:</label>

		      <?php echo form_input('name', $trainer->fullname, "disabled"); ?>

		      <?=form_hidden('employee_id', $trainer->trainer_id);?>
		   </div>

		    <div class="field">
		      <label for="license_equipment">Ажиллах тоног төхөөрөмж:</label>

		      <?php echo form_dropdown('license_equipment_id', $license_equipment, null, 'id="license_equipment"');?>
		   </div>

		  	<div class="field">
		      <label for="equipment_id">Шалгалт өгсөн огноо:</label>
		      <?php echo form_input('exam_date', null, "id='exam_date'");?>
		    </div>

		    <div class="field">
		      <label for="equipment_id">Хүчинтэй хугацаа хүртэл:</label>
		      <?php echo form_input('valid_date', null, "id='valid_date'");?>
		    </div>

		<?php echo form_close();?>



<!-- form ends here -->

	<!-- form remark here -->

		<?php echo form_open('#', array('name'=>'remark', 'id'=>'remark', 'title'=>'Тустгай тэмдэглэл нэмэх')); ?>

		<?php echo validation_errors();?>

		<?php echo form_hidden('id', null);?>

		<?php if(isset($remarks)){?>

		   	<div>
		   		<table>
		   			<tr>
		   				<th>Тусгай тэмдэглэл</th>
		  				<th>Үйлдэл</th>
		   			</tr>
		   		<?php foreach($remarks as $row){
		   			echo "<tr>";
						 echo "<td>".$row->remark."</td>";		
						 echo "<td><input type='button' class='deleteRemark' id='".$row->id."' value='Устгах'></td>";   			
		   			echo "</tr>";
		   		}
		   		?>
		   			</tr>
		   		</table>

		   	</div>

		   <?php  } ?>

		<p class="feedback"></p>
		 
		    <div class="field">
		      <label for="license_equipment">Тустгай тэмдэглэл:</label>
					
					<?=form_hidden('employee_id', $trainer->trainer_id);?>

					<textarea name="remark" cols="68" rows="5"></textarea>

		   </div>

		  	

		<?php echo form_close();?>
<!-- form ends here -->


<script type="text/javascript">

  var create; var page, info, remark_dialog;

   create=$('#create'); 

   info=$('#info');

   
    $('#appointed_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });   

    $('#issued_date', '#info').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      changeYear: true,
      showWeek: true
   });    

    $('#initial_date', '#info').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      changeYear: true,
      showWeek: true
   });    

   $('#birthdate', '#info').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      changeYear: true,
      showWeek: true
   });

   $('#valid_date', '#info').datepicker({
	     dateFormat: 'yy-mm-dd',      
	     changeMonth: true,
	     showOtherMonths: true,
	     showWeek: true,
	     changeYear: true,
	     opened:   false
		}); 
		

   $('#exam_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });


   $('#valid_date').datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true
   });


   info.dialog({

      autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $(this).dialog("close");

       }
   });

   create.dialog({

      autoOpen: false,
       width: 600,
       resizable: false,
       modal: true,
       close: function () {

          $('p.feedback', $(this)).html('').hide();

          $(this).dialog("close");

       }
   });

   var form_exam; // exam tuuhuudiig nemeh

   form_exam=$('#exam');

   form_exam.dialog({

     autoOpen: false,
       width: 600,

       resizable: false,

       modal: true,

       close: function () {

          $('p.feedback', $(this)).html('').hide();

         
          $(this).dialog("close");

       }
   });

	 remark_dialog = $('#remark');

   remark_dialog.dialog({

     autoOpen: false,
       width: 600,

       resizable: false,

       modal: true,

       close: function () {

          $('p.feedback', $(this)).html('').hide();

         
          $(this).dialog("close");

       }
   });

  var error = [];

  function printPage(divName) {

     var child = document.getElementsByClassName("title");

     var originalContents = document.body.innerHTML;

       $(".title").css("opacity", "0");

      $(".title_mn").css("opacity", "0");
    // $( ".title_mn" ).hide();

      var currentHtml = $('#printable_page1').html();

      var printContents = document.getElementById(divName).innerHTML;

      document.body.innerHTML = printContents;

      var page_number;

      switch (divName) {

        case  'page_0':

          page_number = 2;

        break;     
        
        case 'printable_page2':

          page_number = 3;
        break;

        case 'printable_page3':

          page_number = 5;
        break;

      }

      // page = page_number;

      sessionStorage.setItem("page", page_number);

      window.print();

      document.body.innerHTML = originalContents;

      sessionStorage.setItem("trainer_id", $('#trainer_id').val());
      
      sessionStorage.setItem("user_id", $('#user_id').val());

      sessionStorage.setItem("license_no", $('#license_no').val());       

      sessionStorage.setItem("content", 'хуудсан дээрх бүх утгийг хэвлэсэн');   

      location.reload();

  }

  function printLastPage(divName) {

    // Хэрэв last left, last right bhgui bol position-name , date эхний мөр гсн үг

    var originalContents = document.body.innerHTML;

     // бүх title-г харагдахгүй болгоно.

    $(".title").css("opacity", "0");

    $(".title_mn").css("opacity", "0");

    // check if div==page2 its page III

    if(divName=='printable_page2'){

        $('.data-license_').css('opacity', '0');

        if($('#'+divName).find('div.last-left').length !==0){

          $('#position-name').css('opacity','0');
          $('#position-date').css('opacity','0');

          $('#'+divName).find('div.row-left').css('opacity','0');      
          $('#'+divName).find('div.row-right').css('opacity','0');      

        }else{

          console.log('no dont have class position its first');     
        }


    }else if(divName=='printable_page5'){
      // V-VI Хуудас бол нэнд хэвлэнэ

      if($('#'+divName).find('td.last-exam').length !==0){

        $('#'+divName).find('td.exam-fr').css('opacity','0'); 
        
        $('#'+divName).find('td.exam').css('opacity','0');  
      }

    }else if(divName=='printable_page6'){
      // V-VI Хуудас бол нэнд хэвлэнэ

      if($('#'+divName).find('td.last-exam').length !==0){

        $('#'+divName).find('td.exam-fr').css('opacity','0'); 
        
        $('#'+divName).find('td.exam').css('opacity','0');  
      }

    }else if(divName=='printable_page7'){
      // V-VI Хуудас бол нэнд хэвлэнэ

      if($('#'+divName).find('td.last-exam').length !==0){

        $('#'+divName).find('td.exam-fr').css('opacity','0'); 
        
        $('#'+divName).find('td.exam').css('opacity','0');  
      }

    }
    
      var printContents = document.getElementById(divName).innerHTML;

      document.body.innerHTML = printContents;

      var page_number;

      switch (divName) {

        case  'page_0':

          page_number = 2;

        break;     
        
        case 'printable_page2':

          page_number = 3;
        break;

        case 'printable_page3':

          page_number = 5;
        break;

      }

      // page = page_number;

      sessionStorage.setItem("page", page_number);

      window.print();

      document.body.innerHTML = originalContents;

      sessionStorage.setItem("trainer_id", $('#trainer_id').val());
      
      sessionStorage.setItem("user_id", $('#user_id').val());

      sessionStorage.setItem("license_no", $('#license_no').val());       
      
      sessionStorage.setItem("content", 'сүүлчийн мөрийг хэвлэсэн');        

      location.reload();

  }


window.addEventListener("afterprint", myFunction);
// onafter printed page

function myFunction(){

  var page_number = sessionStorage.getItem("page");
  
  var user_id =  sessionStorage.getItem("user_id");

  var trainer_id = sessionStorage.getItem("trainer_id");  
  
  var license_no = sessionStorage.getItem("license_no");  
  
  var content = sessionStorage.getItem("content");  
  
      $.ajax({
        type:   'POST',
        url:    base_url+'/training/index/add_print',
        data:   {page_number:page_number, trainer_id:trainer_id, user_id: user_id, license_no: license_no, content:content},
        dataType: 'json',
        // async: false,
        success:  function(json){
          
          if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд         
            
            showMessage(json.message, 'success');
            // show the success message

            // location.reload();           

          }else{  // ямар нэг юм нэмээгүй тохиолдолд
            
            $('p.feedback', create).removeClass('success, notify').addClass('error').html(json.message).show();

          }
        
        }
      
      });
  
}
	

</script>