<script>

	//var base_url = window.location.origin;
	var CLIPBOARD = "";
      $(document).ready(function() {
                $("#black, #gray").treeview({
				   collapsed: true,
				   animated: "fast",
				   control: "#sidetreecontrol",
				   persist: "location",
				   cookieId: "treeview-black",
				   mod_class: "module"			
				});
                
           
	});   
        
 
	function reset_by(){
		var id = $('#vw_loc_equip_id').val();
		var location_id = $('#location_id').val();
		$.ajax({
		   type:    'POST',
	   	   url:    base_url+'/flog/jx_reset/',
		   data:   {equipment_id:id, location_id:location_id},
	       dataType: 'json',
	       async: false,
	       success: function(json){
	          if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
	          	console.log('restor successed');
	          	return true;
	             //alert(json.status);                  		
	          }else
	          	console.log('restor false');
	          	return false;
			    // alert(json.status);                  		
	          }                   
		}).done(function(){
			return true;
		});
	}

	$(window).bind('beforeunload', function(){		  		 
   		 return reset_by();
	});
	
	function cancel(){
		reset_by();
		 window.location.assign(base_url+"/flog");
	}

</script>
<div class="gray-bg" style="padding-left: 40px; padding-top: 20px;">
	<h3>Гэмтэл нээх</h3>
	<style>
table.ftree {
	display: block;
}

table.ftree td.col-r {
	text-align: right;
	font-weight: bold;
}

#create_form {
	display: block;
}
</style>

<?php print_r($section); ?>

	<form id="create_form" name="create_form">
		
		<p class="feedback"></p>

		<input type="hidden" name='is_reason' id='is_reason' value="0">
		
		<table width="100%" class="ftree" cellspacing="15" cellpadding="5">
			<tr>
				<td class="col-r"><span>Нээсэн огноо:</span></td>
				<td><input type="text" name="created_dt" id="created_dt"
					placeholder="Нээсэн огноо" /></td>
			</tr>
			<tr>
				<td class="col-r"><span>Хэсэг:</span></td>                                
				<td><?php echo form_dropdown('section_id', $section, $section_id, "id = 'section_id'");?></td>
			</tr>
			<tr>
				<td class="col-r"><span>Байршил:</span></td>
				<td>      
                                <?php echo form_dropdown('location_id', $location, $location_id, "id='location_id' title='Байршлийг сонгоход тухайн байршил дээрх тоног төхөөрөмжүүд гарч ирнэ!'");?>
                        </td>
			</tr>
			<tr>
				<td class="col-r"><span id='equipment_label'>Тоног төхөөрөмж:</span></td>
				<td><i class="warning">Дээрх байршил дахь төхөөрөмжүүдийг
                                        харуулна! <br> Хэрэв байршил дээр тоног төхөөрөмж харагдахгүй байвал, Тохиргоо цэсний Байршил/Төхөөрөмж дээр нэмэх шаардлагатайг анхаарна уу!</i> <br>
			<?php echo form_dropdown('equipment_id', $equipment, $equipment_id, "id='vw_loc_equip_id' title='Тухайн тоног төхөөрөмжийг сонгоход тухайн т/т гэмтлийн модыг үүсгэсэн бол харагдана үгүй бол <Гэмтлийн мод> цэсэнд нэмнэ үү!'");?>
			</td>
			</tr>
                        <tr>
				<td>	
					<strong>Алдааны мод:</strong>
					</td>
				<td class="col-c">
					<div class="tree">
					 <?php echo $ftree;?> 						
					</div>
				</td>
			</tr>
			<tr>
				 <td><strong>Гэмтлийн төрөл:</strong></td>
				 <td>
				 		 <?php echo form_dropdown('type_id', $log_type, null, "id = 'log_type_id' disabled ");?>
				 		 <i class="warning">Алдаа гарсан хэсгүүдийг сонгоход автоматаар сонгогдоно!</i>
				 </td>
			</tr>
			<tr>
				<td class="col-r"><span id='ftree_label'>Гэмтэл/дутагдал гарсан дэд хэсэг:</span>
				</td>
				<td><i class="warning">Гэмтлийн модны сонгосон мөчрийн баруун доод
						буланд гарч ирэх товч дээр дарна уу!</i>
						<div id="wrapper-tag" style="min-height: 20px;">							
						</div>
					<ul id="myTags" placeholder="Гэмтлийн модоос сонго!"></ul> <!-- <input type="text" id="ftree_id" name="node" size="70" />		 -->
				</td>

			</tr>

			
			<tr>
				<td class="col-r"><span id="label_reason">Шалтгаан:</span></td>
				<td>
        		<?php echo form_dropdown('reason_id', $reason, null, "id = 'reason_id'");?>
        			<span>гэмтэл гарахад нөлөөлсөн хүчин зүйлс:</span>
				</td>
			</tr>
			<tr id="reason_log_1">
				<td colspan="2">
					<fieldset style="width: 100%; padding: 0;">
						<legend id="reason_title">Гэмтлийн ангилал:</legend>
						<div style="margin: 5px; font-style: italic" class="warning">Нээж
							буй гэмтлийн тухайн байршилд, нээж буй хугацаанаас өмнөх 72
							цагийн дотор гарсан гэмтлүүдийг харуулж буйг анхаарна уу!</div>
						<em class="warning">Гэмтлийн №, Хэсэг, Нээгдсэн огноо, Тоног
							төхөөрөмж, Модиулууд гэсэн форматаар харуулна!</em> <br> <label
							id="reason_log_title"> Бусад гэмтлүүд:</label> 
							<select
							id="parent_id" name="parent_id"
							data-placeholder="Гэмтлийн нэр || хугацаа || тоног төхөөрөмжийг бичиж шүүж болно!"
							class="chosen-select" tabindex="1">
						</select>
					</fieldset>
				</td>
			</tr>
			<tr id="reason_log_2">
				<td colspan="2">
					<fieldset>
						<legend id="reason_title">Гадны байгууллагын тоног төхөөрөмжөөс
							хамаарсан:</legend>
						<div class='warning' style="font-style: italic;">Тухайн гэмтлийн
							байршил дээрх гадны тоног төхөөрөмжүүдийг харуулахыг анхаарна уу!</div>
						<label for="">Тоног төхөөрөмж, байгууллага</label> <select
							name="equip_com_id" id="equip_com_id">
							<option value='0'>Сонгох</option>
						</select> <br>
					</fieldset>

				</td>
			</tr>	
			<tr id='trigger_comment'><td class="col-r">Тайлбар:</td>
				<td><i>Тайлбар хийх шаардлагатай бол <a href="#" id="set_comment">энд дарна уу</a>! <br>Заавал тайлбар бичих шаардлагагүйг анхаарна уу!</i></td>
			</tr>
			<tr id="comment_field"><td class="col-r"><span>Тайлбар:</span></td>
				<td>
					<textarea name="comment" id="comment" cols="80" rows="7"></textarea>
				</td>
			</tr>

		
                        
  			<tr>
				<td colspan="2">
					<div style="text-align: right;">
						<span style="margin-right: 20%;"> <a href="#" id='btn_save'
							class="button">Хадгалах</a> 
							<a href="#" onclick="cancel()" class="button">Болих</a>
						</span>
					</div>
				</td>
			</tr>
		</table>
	</form>
    
</div>

			

<!-- chosen script here -->
<script src="<?=base_url();?>/assets/chosen/chosen.jquery.js"
	type="text/javascript"></script>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"200%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

      $(function() {
        $('.chosen-container').css({"width": "520px"});
      });


  </script>
<!-- chosen script ends here -->