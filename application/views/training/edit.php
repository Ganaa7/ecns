<?=$library_src; ?>
<?php$p = array ();$json_array = json_decode ( $out->data ['training_json'] );$json_education = json_decode ( $out->data ['trainer_education'] );( object ) $p = ( object ) $out->data;// var_dump($p);$education = array ('Бага' => 'Бага','Дунд' => 'Дунд','Бүрэн дунд' => 'Бүрэн дунд','Тусгай дунд' => 'Тусгай дунд','Дээд' => 'Дээд' );$rel_type = array ('Аав' => 'Аав','Ээж' => 'Ээж','Эхнэр' => 'Эхнэр','Нөхөр' => 'Нөхөр','Хүүхэд' => 'Хүүхэд','Хамаатан' => 'Хамаатан' );?>
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
<script>

$(document).ready(function(){
	// $("<div>Test message</div>").dialog();
	var appent_txt;
	   
   	// Education Button Clicked!        
	// $( "#edu_button_add" ).click(function(){  	   	
	//   var appent_txt="<tr><td><input type='datetime' name='school[]'></td><td><input type='datetime' name='enter_dt[]' class='date_class'></td><td><input type='text' name='grade_dt[]' class='date_class'></td><td><textarea name='detail[]' cols='25'></textarea></td></tr>";
 //       $("#education").append(appent_txt).find('.date_class').datepicker();      
	// });
	// //Мөр хасан товч дарагдахад
	// $('#edu_button_sub').click(function(){
	// 	var rowCount = $('#education tr').length;
	// 	if(rowCount>2) //Хасах боломжтой
	//        $('#education tr:last').remove();
	//     else
	//        alert("Сүүлчийн мөрийг хасах боломжгүй!");
	// });
	// // Study Button Clicked!    

	// $("#back").click(function(){
	// 	document.location="/ecns/training/";		
	// });
	
	// $( "#stu_button_add" ).click(function(){  	   	
	//    var training_append="<tr><td><input type='text' name='training[]'></td><td><input type='text' name='type_id[]'></td><td><input type='text' name='date[]' class='date_class' size='10'></td><td><input type='text' name='time[]' size='4' style='width:30px;'></td><td><input type='text' name='place[]'></td><td><input type='text' name='orderN[]' size='6'></td></tr>";
 //       $("#study").append(training_append).find('.date_class').datepicker();      
	// });
	// //Мөр хасан товч дарагдахад
	// $('#stu_button_sub').click(function(){
	// 	var rowStudy = $('#study tr').length;
	// 	if(rowStudy>2) //Хасах боломжтой
	//        $('#study tr:last').remove();
	//     else
	//        alert("Сүүлчийн мөрийг хасах боломжгүй!");
	// }); 


 // $('#valid_date').datepicker({
 //       dateFormat: 'yy-mm-dd',      
 //       changeMonth: true,
 //       showOtherMonths: true,
 //       showWeek: true,
 //       opened:   false
 //   }); 
 //   	 $('#issued_date').datepicker({
 //       dateFormat: 'yy-mm-dd',      
 //       changeMonth: true,
 //       showOtherMonths: true,
 //       showWeek: true,
 //       opened:   false
 //   }); 

 //   $('.enter_dt').datepicker({
 //       dateFormat: 'yy-mm-dd',      
 //       changeMonth: true,
 //       showOtherMonths: true,
 //       showWeek: true,
 //       opened:   false
 //   }); 
 //   $('.year_dt').datepicker({
 //       dateFormat: 'yy,mm,dd',      
 //       changeMonth: true,
 //       showOtherMonths: true,
 //       showWeek: true,
 //       opened:   false
 //   }); 

});
</script>
<div class="main">
	<form name="trainer_form" method="post" id="trainer_form">
		<input type="hidden" name="trainer_id" value="<?=$p->trainer_id?>">
		<table class='anket' align="center" width="70%" border="0"
			cellpadding="2" cellspacing="2">
			<tr>
				<th colspan="2">
					<h2>АГААРЫН НАВИГАЦИЙН ИНЖЕНЕР/ТЕХНИКЧИЙН АНКЕТ</h2>
				</th>
			</tr>
			<tr>
				<th colspan="2">
					<p class="feedback"></p>
				</th>
			</tr>
			<tr>
				<th colspan="2"><hr></th>
			</tr>
			<tr>
				<th colspan="2">1.ЕРӨНХИЙ МЭДЭЭЛЭЛ</th>
			</tr>
			<tr>
				<td colspan="2">1.1.Регистрийн дугаар<input type="text"
					name="register" value="">
				</td>
			</tr>
			<tr>
				<td>1.2. Эцэг /эхийн/ нэр:<i><input type="text" name="lastname"
						value="<?=$p->lastname;?>"></i></td>
				<td>Нэр: <input type="text" name="firstname"
					value="<?=$p->firstname;?>">
				</td>
			</tr>
			<tr>
				<td>1.3.Хүйс: <select name="gender" id="">
						<option value="1">Эрэгтэй</option>
						<option value="2">Эмэгтэй</option>
				</select>
				</td>
				<td>1.4.Төрсөн он-сар-өдөр <input type="text" name="birthdate"
					class='year_dt' value="<?=$p->birthdate;?>">
				</td>
			</tr>
			<tr>
				<td>1.5.Харьяа Байгууллага:
				<?//=form_dropdown('orginization_id', $organization, $p->organiztion_id);?>
			</td>
				<td>1.6.Байршил:
				<?=form_dropdown('location_id', $location, $p->location_id);?>/хот, аймаг, сум/
			</td>
			</tr>
			<tr>
				<td>1.7.Албан тушаал:
				<?=form_dropdown('position_id', $position, $p->position_id);?>
			</td>
				<td>1.8.Гэрийн хаяг: <textarea name="address" id="" cols="40"
						rows="2"></textarea>
				</td>
			</tr>
			<tr>
				<td>1.9.Гэрийн утас: <input type="text" name="homephone" value="">
				</td>
				<td>1.10.Гар утас: <input type="text" name="phone"
					value="<?=$p->phone;?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">1.11.Имэйл хаяг:<input type="text" name="email"
					value="<?=$p->email;?>">
				</td>
			</tr>
			<tr>
				<td>1.12.Онцгой шаардлага гарсан үед харилцах хүн (
				<?=form_dropdown('rel_type', $rel_type, $p->rel_type)?>				
				)</td>
				<td>Тэр хүнтэй холбогдох утас: <input type="text" name="rel_phone"
					value="<?=$p->rel_phone;?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">1.13.Боловсролын байдал:
				<?=form_dropdown('education', $education, $p->education)?>
			</td>
			</tr>
			<tr>
				<td>1.14.Мэргэжлийн үнэмлэх №: <input type="text" name="license_no"
					value="<?=$p->license_no;?>">
				</td>
				<td>1.15.Үнэмлэхний төрөл:			
			<?=form_dropdown('license_type_id', $license_type, $p->license_type_id)?></td>
			</tr>
			<tr>
				<td colspan='2'>/Хэрэв цахилгааны инженер/техникч бол А/А-ын
					үнэмлэх:<input type="text" size="3" name="speciality"
					value="<?=$p->specialty;?>"> A/А-ын групп:<input type="text"
					size="2" name="speciality" value="<?=$p->security_group;?>"> /
				</td>
			</tr>
			<tr>
				<td>1.16.Үнэмлэх олгосон огноо<input type="text" id="issued_date"
					name="issued_date" value="<?=$p->issued_date;?>"></td>
				<td>1.17.Үнэмлэхний хүчинтэй хугацаа: <input type="text"
					id="valid_date" name="valid_date" value="<?=$p->valid_date;?>"></td>
			</tr>
			<tr>
				<td colspan='2' valign="middle">1.18.Ажиллах тоног төхөөрөмжүүд: <textarea
						cols=70 name="license_equipment"><?=$p->license_equipment?></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><h3 align='center'>2.БОЛОВСРОЛЫН ТАЛААРХ МЭДЭЭЛЭЛ</h3>
					Боловсролын /ерөнхий, тусгай дунд, дээд боловсрол, дипломын,
					баклаврын болон магистрийн зэргийг оролцуулан/
					<table id="education" align='center' border="1" cellpadding="0"
						cellspacing="0" width="90%">
						<tr>
							<th>Сургуулийн нэр</th>
							<th>Орсон он, сар</th>
							<th>Төгссөн он, сар</th>
							<th>Эзэмшсэн боловсрол, мэргэжил, гэрчилгээ, дипломын дугаар</th>
						</tr>
					<?php
					if (isset ( $json_education )) {
						foreach ( $json_education as $key => $value ) {
							foreach ( $value as $row ) {
								echo "<tr>";
								echo "<td><input type='text' name='school[]' value='$row->school'></td>";
								echo "<td><input type='text' name='enter_dt[]' class='enter_dt' value='$row->entered'></td>";
								echo "<td><input type='text' name='grade_dt[]' class='enter_dt' value='$row->finished'></td>";
								echo "<td><textarea name='detail[]' cols='50'>$row->education</textarea></td>";
								echo "</tr>";
							}
						}
					}
					?>
				</table>
					<div style="margin-top: 10px;">
						<input type='button' id='edu_button_add' value='Талбар нэмэх' /> <input
							type='button' id='edu_button_sub' value='Талбар хасах' />
					</div></td>
			</tr>
			<tr>
				<td colspan="2"><h3 align="center">3.СУРГАЛТ/МЭРГЭШЛИЙН ТАЛААРХ
						МЭДЭЭЛЭЛ</h3> 3.1. /Мэргэжлийн болон бусад чиглэлээр мэргэшүүлэх
					сургалтанд хамрагдсан байдлыг бичнэ/
					<table id='study' align='center' border="1" cellpadding="0"
						cellspacing="0" width="90%">
						<tr>
							<th>Тушаал №</th>
							<th>Огноо</th>
							<th>Сургалт</th>
							<th>Сургалтын төрөл</th>
							<th>Цаг /цагаар/</th>
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
 				</table> <!-- <div style="margin-top:10px;">
					<input type='button' id='stu_button_add' value='Талбар нэмэх'/>
					<input type='button' id='stu_button_sub' value='Талбар хасах'/>
				</div> --></td>
			</tr>

		</table>
	</form>
	<div>

		<input type="button" id="back" value="Буцах"> <input type="button"
			id="submit" value="Бүртгэх">
	</div>
</div>
<script>
   //onclick submit post ajax to index.add\
   $(document).ready(function(){
	  //  $( "#submit" ).click(function() {  
	  //  	  var trainer_form = $('#trainer_form'), data = {};   	     	  
	  //     var inputs = $('input[type="text"], input[type="hidden"], select, textarea', trainer_form); 
		 //  // console.log(inputs);	
		 // var data = $( trainer_form ).serialize();
		  
		 //  // inputs.each(function(){	  	
		 //  //    var el = $(this);	     
		 //  //    if(names.search(el.attr('name'))<0)
		 //  //      data[el.attr('name')] = el.val();
		 //  // }); 

		 //  console.log(data);
		 //  // collect the form data form inputs and select, store in an object 'data'
		 //  $.ajax({
		 //     type:     'POST',
		 //     url:    '/ecns/training/index/update/',
		 //     data:   data,
		 //     dataType: 'json', 
		 //     success:  function(json){ 
		 //        if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
		 //          // close the dialog                         
		 //            $('p.feedback', trainer_form).removeClass('error').hide();
		 //           	showMessage(json.message, 'success');
		 //          // show the success message      
		 //          	document.location="/ecns/training/";
		 //        }                  
		 //        else{  // ямар нэг юм нэмээгүй тохиолдолд
		 //          $('p.feedback', trainer_form).removeClass('success, notify').addClass('error').html(json.message).show();                        
		 //        }
		 //      }
		 //  });
	  // });
	});

//  function showMessage(message, p_class){
//    if (!$('p#notification').length){
//       //$('#main_wrap').prepend('<p id="notification"></p>');
//       $('#nav-bar').prepend('<p id="notification"></p>');
//    }
//    var paragraph = $('p#notification');
//    paragraph.hide();
//    paragraph.removeClass();
//    // remove all classes from the <p>
//    paragraph.addClass(p_class);
//    // add the class supplied
//    paragraph.html(message);
//    // change the text inside
//    paragraph.fadeIn('fast', function(){
//       paragraph.delay(3000).fadeOut();
//     // fade out again after 3 seconds  
//    });
//   // fade in the paragraph again
// }
 
</script>
