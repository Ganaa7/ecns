<script>
	$(document).ready(function(){
		$('.enter_dt').datepicker({
	       dateFormat: 'yy-mm-dd',      
	       changeMonth: true,
	       showOtherMonths: true,
	       showWeek: true,
	       opened:   false
   	   }); 
	   $('.year_dt').datepicker({
	     dateFormat: 'yy,mm,dd',      
	     changeMonth: true,
	     showOtherMonths: true,
	     showWeek: true,
	     opened:   false
	   }); 
	});
</script>
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
<div class="main">

	<form name="trainer_form" method="post" id="trainer_form">

		<table class='anket' align="center" width="70%" border="0"
			cellpadding="2" cellspacing="2">
			<tr>
				<th colspan="2">
					<h2>АГААРЫН НАВИГАЦИЙН ИНЖЕНЕР ТЕХНИКЧИЙН АНКЕТ</h2>
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
				<td colspan="2">1.1.Регистрийн дугаар:<input type="text"
					name="register" value="">
				</td>
			</tr>
			<tr>
				<td>1.2. Эцэг эхийн нэр:<i><input type="text" name="lastname"></i></td>
				<td>Нэр: <input type="text" name="firstname">
				</td>
			</tr>
			<tr>
				<td>1.3.Хүйс: <select name="gender" id="">
						<option value="1">Эрэгтэй</option>
						<option value="2">Эмэгтэй</option>
				</select>
				</td>
				<td>1.4.Төрсөн он-сар-өдөр <input type="text" name="birthdate"
					class="enter_dt">
				</td>
			</tr>
			<tr>
				<td>1.5.Харьяа Байгууллага:
				<?=form_dropdown('org_id', $organization, 0);?>
			</td>
				<td>1.6.Байршил:
			   <?=form_dropdown('location_id', $location, 0);?>
			</td>
			</tr>
			<tr>
				<td>1.7.Албан тушаал:
				<?=form_dropdown('position_id', $position, 0);?>
			</td>
				<td>1.8.Гэрийн хаяг: <textarea name="address" id="" cols="40"
						rows="2"></textarea>
				</td>
			</tr>
			<tr>
				<td>1.9.Гэрийн утас: <input type="text" name="phone" value="">
				</td>
				<td>1.10.Гар утас: <input type="text" name="mobile" value="">
				</td>
			</tr>
			<tr>
				<td colspan="2">1.11.Имэйл хаяг:<input type="text" name="email"
					value="">
				</td>
			</tr>

			<tr>
				<td>1.12.Онцгой шаардлага гарвал харилцах хүн ( <select
					name="rel_type">
						<option value="0">Сонго..</option>
						<option value="Аав">Аав</option>
						<option value="Ээж">Ээж</option>
						<option value="Эхнэр">Эхнэр</option>
						<option value="Нөхөр">Нөхөр</option>
						<option value="Хүүхэд">Хүүхэд</option>
						<option value="Ах">Ах</option>
						<option value="Эгч">Эгч</option>
						<option value="Хамаатан">Хамаатан</option>
				</select> )
				</td>
				<td>Холбогдох хүний утас: <input type="text" name="rel_phone">
				</td>
			</tr>
			<tr>
				<td>1.13.Эзэмшсэн мэргэжил: <input type="text" name="occupation"
					id="occupation">
				</td>
				<td>1.14.Боловсролын байдал: <select name="education">
						<option value="0">Сонго..</option>
						<option value="Дээд">Дээд</option>
						<option value="Тусгай дунд">Тусгай дунд</option>
						<option value="Бүрэн дунд">Бүрэн дунд</option>
						<option value="Дунд">Дунд</option>
						<option value="Бага">Бага</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>1.15.Мэргэжлийн үнэмлэх №: <input type="text" name="license_no"
					value="">
				</td>
				<td>1.16.Үнэмлэхний төрөл:			
			<?=form_dropdown('license_type_id', $license_type, 0)?></td>
			</tr>
			<tr>
				<td colspan='2'>/Хэрэв цахилгааны инженер/техникч бол А/А-ын
					үнэмлэх:<input type="text" size="3" name="aa_license" value="">
					A/А-ын групп:<input type="text" size="2" name="aa_group" value="">
				</td>
			</tr>
			<tr>
				<td>1.17.Үнэмлэх олгосон огноо:<input type="text" id="issued_date"
					name="issued_date" class="enter_dt"></td>
				<td>1.18.Үнэмлэхний хүчинтэй хугацаа: <input type="text"
					name="valid_date" class="enter_dt"></td>
			</tr>
			<tr>
				<td colspan="2">1.19.Ажиллах тоног төхөөрөмжүүд: <input type="text"
					name="work_equipment"></td>
			</tr>
			<tr>
				<td colspan="2"><h3 align='center'>2.БОЛОВСРОЛЫН ТАЛААРХ МЭДЭЭЛЭЛ</h3>
					Боловсролын /ерөнхий, тусгай дунд, дээд боловсрол, дипломын,
					баклаврын болон магистрийн зэргийг оролцуулан/ (багана нэмэх)
					<table align='center' border="1" cellpadding="0" cellspacing="0"
						width="90%">
						<tr>
							<th>Сургуулийн нэр</th>
							<th>Орсон он, сар</th>
							<th>Төгссөн он, сар</th>
							<th>Эзэмшсэн боловсрол, мэргэжил, гэрчилгээ, дипломын дугаар</th>
						</tr>
						<tr>
							<td><input type="text" name="school"></td>
							<td><input type="text" name="enter_dt" class="enter_dt"></td>
							<td><input type="text" name="grade_dt" class="enter_dt"></td>
							<td><textarea name="detail" cols="25"></textarea></td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td colspan="2"><h3 align="center">3.МЭРГЭШЛИЙН БЭЛТГЭЛИЙН ТАЛААРХ
						МЭДЭЭЛЭЛ</h3> 3.1. Мэргэшлийн бэлтгэл /Мэргэжлийн болон бусад
					чиглэлээр мэргэшүүлэх сургалтанд хамрагдсан байдлыг бичнэ/
					<table align='center' border="1" cellpadding="0" cellspacing="0"
						width="90%">
						<tr>
							<th>Хаана ямар байгууллагад</th>
							<th>Эхэлсэн дууссан он, сар,өдөр</th>
							<th>Хугацаа /хоногоор/</th>
							<th>Ямар чиглэлээр</th>
							<th>Үнэмлэх, гэрчилгээний дугаар, олгосон он, сар, өдөр</th>
						</tr>
						<tr>
							<td><input type="text" name="name"></td>
							<td><input type="text" name="name"></td>
							<td><input type="text" name="name"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table></td>
			</tr>

		</table>
	</form>
	<div>

		<input type="button" id="submit" value="Бүртгэх">
	</div>
</div>
<script>
   //onclick submit post ajax to index.add\
   $( "#submit" ).click(function() {  
   	  var trainer_form = $('#trainer_form'), data = {};   	     	  
      var inputs = $('input[type="text"], input[type="hidden"], select, textarea', trainer_form); 
	  // console.log(inputs);

	  inputs.each(function(){
	     var el = $(this);
	     data[el.attr('name')] = el.val();
	  });   
	  // collect the form data form inputs and select, store in an object 'data'
	  $.ajax({
	     type:     'POST',
	     url:    '/ecns/training/index/add/',
	     data:   data,
	     dataType: 'json', 
	     success:  function(json){ 
	        if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд                  
	          // close the dialog            
	           $('p.feedback', trainer_form).removeClass('error').hide();             
	           showMessage(json.message, 'success');
	          // show the success message          
	        }                  
	        else{  // ямар нэг юм нэмээгүй тохиолдолд
	          $('p.feedback', trainer_form).removeClass('success, notify').addClass('error').html(json.message).show();                        
	        }
	      }
	  });
  });
 

  function showMessage(message, p_class){
   if (!$('p#notification').length){
      //$('#main_wrap').prepend('<p id="notification"></p>');
      $('#nav-bar').prepend('<p id="notification"></p>');
   }
   var paragraph = $('p#notification');
   paragraph.hide();
   paragraph.removeClass();
   // remove all classes from the <p>
   paragraph.addClass(p_class);
   // add the class supplied
   paragraph.html(message);
   // change the text inside
   paragraph.fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}
 
</script>
