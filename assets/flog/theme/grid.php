<?php 
$data['industy_id']=$industy_id;
$CI = &get_instance ();
$CI->load->view('plugin/filter', $data); ?>
<style>
.field {
	display: block;
	width: auto; 
	line-height: 2em;
	margin: 5px 0px;
}

p.success {
	background: rgba(64, 211, 64, 0.97);
}

</style>
<div id="main_wrap" style="margin: 10px 20px 20px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>
<div style="margin-left:5px; padding:5px 0; font-size: 9pt;">
	<strong><i>ГЭМТЭЛ, ДУТАГДЛЫН ӨНГӨ:</i></strong>
		ШИНЭЭР НЭЭСЭН болон ЕЗИ танилцах гэмтэл: <span style="background-color: #FF9900; font-style: italic">УЛБАР ШАР</span>,
		Хаагдаагүй гэмтэл: <span
			style="background-color: rgb(255, 113, 86); font-style: italic">УЛААН ӨНГӨ</span>,
		ЭРСДЛИЙН ҮНЭЛГЭЭ хийх: <span
			style="background-color: #FFDE00; font-style: italic">ШАР ӨНГӨ</span>, 
		ТАЙЛАНГИЙН ФАЙЛ хавсаргах: <span
			style="background-color: #73B0E6; font-style: italic">ХӨХ ӨНГӨ</span>,
		ХААГДСАН гэмтэл: <span
			style="background-color: #fff; font-style: italic">ЦАГААН ӨНГӨ</span>-р 
		тус тус харагдахыг анхаарна уу!	
</div>

<!-- View Dialog form -->
<form action="" id="view_dialog">
	<div id="wrap_createdby" class="field">
		<label for="category">Хэсэг:</label> 
		<input type="text" name="category" id="category">
	</div>
	<div id="wrap_log_num" class="field">
		<label for="log_num">Дугаар:</label> <input type="text"
			name='log_num' id="log_num"
			class="text ui-widget-content ui-corner-all" disabled />
	</div>
	<div id="wrap_createdby" class="field">
		<label for="edit_createdby_id">Нээсэн ИТА:</label> <span
			id="createdby_id"></span>
	</div>	
	<div id="wrap_created_datetime" class="field">
		<label for="created_datetime">Гэмтэл гарсан хугацаа:</label> <input
			type='text' name="created_datetime" id="created_datetime"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div id="wrap_location" class="field">
		<label for="location_id">Байршил:</label>
        <?=form_dropdown('location_id',$location, null, 'disabled class="text ui-widget-content ui-corner-all" id="location_id"')?>
    </div>
	<div id="wrap_equipment" class="field">
		<label for="equipment_id">Тоног төхөөрөмж:</label>
        <?=form_dropdown('equipment_id',$equipment, null, 'disabled class="text ui-widget-content ui-corner-all" id="equipment_id"')?>
    </div>
	<div>
		<label>Гэмтлийн төрөл</label>
		<?=form_dropdown('log_type_id',$log_type, null, 'disabled class="text ui-widget-content ui-corner-all" id="log_type_id"')?>
	</div>
	<div>
		<label for="defect">Гэмтсэн модуль, дэд хэсэг</label>
		<textarea name="defect" id="defect"
			style="width: 460px; height: 70px;"></textarea>
	</div>
	<div id="wrap_reason" class="field">
		<label for="reason">Шалтгаан:</label>        
         <?=form_dropdown('reason_id',$reason, null, 'disabled class="text ui-widget-content ui-corner-all" id="reason_id"')?>
    </div>
    <div>
    	<label for="com_id" id="equip_comp">Гадны байгууллага:</label>
    	<span id="equip_com_id">    		
    	</span>
    </div>
    <div id="field_comment">
    	<label>Нээхэд өгсөн тайлбар:</label>
    	<textarea name="comment" id="comment"
			style="width: 460px; height: 70px;"></textarea>    	
    </div>
	<div id="wrap_closed">
		<div class="field">
			<label for="closed_datetime">Хаасан хугацаа:</label> <input
				type="text" name='closed_datetime' id="closed_datetime"
				class="text ui-widget-content ui-corner-all" disabled />
		</div>
		<div class='field'>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <input type="text"
				name='duration' id="duration" disabled />
		</div>
		<div class='field'>
			<label for="closedby_id">Гэмтэл хаасан ИТА:</label> <span
				id="closedby_id"></span>
		</div>
		<div class="field">
			<label for="completion">Засварласан байдал:</label>			
			<?php echo form_dropdown('completion_id', $completion, null, "id='completion_id'");  ?>			 хэвийн.
		</div>
		<div class="field fixing_field">
			<div id="spare_radio">
				<label>Сэлбэг ашигласан уу?:</label> <input type="radio"
					name="is_spare" id="is_spare" value="N"> Үгүй <input type="radio"
					name="is_spare" id="is_spare" value="Y"> Тийм <br>
			</div>
		</div>
		<div class="field spare_fields">
			<label>Сэлбэгийн төрөл</label>
           <?php											
		if (isset ( $sparetype_id ))
			echo form_dropdown ( 'sparetype_id', $sparetype, $sparetype_id, "id='sparetype_id' disabled" );
		else
			echo form_dropdown ( 'sparetype_id', $sparetype, null, "id='sparetype_id' disabled" );
				?>
        </div>
		<div class="field spare_fields">
			<label>Сэлбэгийн нэр:</label> <input type="text" name="spare"
				id="spare" placeholder="Сэлбэгийн нэрийг бичнэ үү!" size="30"
				disabled>
		</div>
		<div id="field_closed_comment">
    		<label>Хаахад өгсөн тайлбар:</label>  		
    		<textarea name="closed_comment" id="closed_comment" style="width: 460px; height: 70px;"></textarea>    	
    	</div>
    	<div id="show_file">
		
		</div>

	</div>
</form>


<!-- Approve dialog энд байна  -->
<form id="approve_dialog" action="" method="POST">
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id"> <input type="hidden"
		id="log_num" name="log_num"> <input type="hidden" id="status"
		name="status"> <input type="hidden" id="equipment_id"
		name="equipment_id">

	<div>
		<label for="section">Хэсэг:</label> <span class="bolder"
			id="category"></span>
	</div>
	<div>
		<label for="log_num_txt">Гэмтлийн дугаар:</label> <span class="bolder"
			id="log_num_txt" /></span>
	</div>
	<div>
		<label for="createdby_id">Бүртгэл нээсэн ИТА:</label> <span
			class="bolder" id="createdby_id"></span>
	</div>
	<div>
		<label for="created_dt">Гэмтэл гарсан хугацаа:</label> <span
			class="bolder" id="created_dt"></span>
	</div>
	<div>
		<label for="location_id">Байршил:</label> <span class="bolder"
			id="location_id"></span>
	</div>
	<div>
		<label for="equipment_id">Тоног төхөөрөмж:</label> <span
			class="bolder" id="equipment_id"></span>
	</div>
	<div>
		<label for="reason">Шалтгаан:</label> <span class="bolder" id="reason"
			class="def"></span>
	</div>
	<div>
		<label for="defect">Гэмтлийн төрөл:</label> <span class="bolder"
			id="log_type" class="def"></span>
	</div>
	<div>
		<label for="defect">Гэмтсэн модуль:</label> <span class="bolder"
			id="node" class="def"></span>
	</div>
	<div id="wrap_closed">
		<div>
			<label>Хаасан хугацаа:</label> <span class="bolder" id="closed_dt"></span>
		</div>
		<div>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <span
				class="bolder" id="duration"></span>
		</div>
		<div>
			<label for="closedby_id">Бүртгэл Хаасан:</label> <span class="bolder"
				id='closedby_id'></span>
		</div>
		<div>
			<label for="completion">Засварласан байдал:</label>
			<p class="bolder" id="completion" class="def"></p>
		</div>
	</div>
	<!-- <div>
      <p style='font-size:10pt;'>
        Гэмтлийн зэрэглэл, давталтын хүснэгтийг <strong><a href="/ecns/shiftlog/help#zereglel" target="_blank">энд дарж 
        тусламж </a></strong>хэсгээс харна уу!        
      </p>
      <label for="level">Зэрэглэл:</label> -->
        <? //=form_dropdown('level', $out->severity, null, 'class="text ui-widget-content ui-corner-all" id="level"')?>
    <!-- </div> -->
	<!-- <div>         -->
	<!-- <label for="inst">Давталт:</label> -->
        <? //=form_dropdown('inst', $out->ser_level, null, 'class="text ui-widget-content ui-corner-all" id="inst"')?>
    <!-- </div>       -->
</form>


<!-- Quality dialog энд байна  -->
<form id="quality_dialog" action="" method="POST">
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id"> <input type="hidden"
		id="log_num" name="log_num"> <input type="hidden" id="status"
		name="status"> <input type="hidden" id="equipment_id"
		name="equipment_id">

	<div>
		<label for="category">Хэсэг:</label> <span class="bolder"
			id="section"></span>
	</div>
	<div>
		<label for="log_num_txt">Дугаар:</label> <span class="bolder"
			id="log_num" /></span>
	</div>

	<div>
		<label for="createdby_id">Нээсэн ИТА:</label> <span
			class="bolder" id="createdby_id"></span>
	</div>
	<div>
		<label>Гэмтэл гарсан хугацаа:</label>
		<span class="bolder" id="created_dt"></span>
	</div>
	<div>
		<label for="location_id">Байршил:</label> <span class="bolder"
			id="location_id"></span>
	</div>
	<div>
		<label for="equipment_id">Тоног төхөөрөмж:</label> <span
			class="bolder" id="equipment_id"></span>
	</div>
	<div>
		<label for="reason">Шалтгаан:</label> <span class="bolder" id="reason"
			class="def"></span>
	</div>
	<div>
		<label>Гэмтлийн төрөл:</label>
		<span class="bolder" id="log_type"></span>
	</div>
	<div>
		<label for="defect">Гэмтсэн модуль:</label> <span class="bolder"
			id="node" class="def"></span>
	</div>
	<div id="wrap_closed">
		<div>
			<label>Хаасан хугацаа:</label> <span class="bolder" id="closed_dt"></span>
		</div>
		<div>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <span
				class="bolder" id="duration"></span>
		</div>
		<div>
			<label for="closedby_id">Бүртгэл хаасан:</label> <span class="bolder"
				id='closedby_id'></span>
		</div>
		<div>
			<label for="completion">Засварласан байдал:</label>
			<p class="bolder" id="completion" class="def"></p>
		</div>
	</div>
	<div>   
	  
	<p style="font-size: 10pt;"><i>Аюулыг шинжилж эрсдлийг тогтоох хүснэгтийг ҮАЗ-ын 6.15 Аюулгүй ажиллагааны удирдлагын журмаас харна уу!</i></p>  
      <label for="level">Хүндрэл:</label>
        <?=form_dropdown('level', $severity, null, 'class="text ui-widget-content ui-corner-all" id="level"')?>
    </div>
	<div>        
	<label for="inst">Магадлал:</label>
        <?=form_dropdown('num', $ser_level, null, 'class="text ui-widget-content ui-corner-all" id="num"')?>
    </div>
    <p class="warning">5A, 4A, 3A, 2A, 1A, 5B, 4B, 3B, 2B, 1B, 2C, 1C үнэлгээг сонгоод хадгалахад автоматаар тайлангийн файл шаардана! <br>Дээрхээс өөр сонголт хийсэн бол <strong>"Тайлангийн файл шаардах"</strong> эсэхээ сонгох ёстойг анхаарна уу!</p>
    <div id='wp_require'>        
		<label>Тайлангийн файл шаардах:</label>
        <select name="require_file" id="require_file">
        			<option value="0">Нэг утгийг сонго</option>
        			<option value="1">Тийм</option>        			
        			<option value="2">Үгүй</option>        			        			
        </select>
    </div>      
</form>


<!-- Файл хавсаргах -->
<?php echo form_open_multipart('ecns/flog/upload_file/', 'id="file_dialog"'); ?>
	<p class="feedback"></p>
	<input type="hidden" id="log_id" name="log_id"> <input type="hidden"
		id="log_num" name="log_num"> <input type="hidden" id="status"
		name="status"> <input type="hidden" id="equipment_id"
		name="equipment_id">

	<div>
		<label for="category">Хэсэг:</label> <span class="bolder"
			id="section"></span>
	</div>
	<div>
		<label for="log_num_txt">Дугаар:</label> <span class="bolder"
			id="log_num" /></span>
	</div>

	<div>
		<label for="createdby_id">Нээсэн ИТА:</label> <span
			class="bolder" id="createdby_id"></span>
	</div>
	<div>
		<label>Гэмтэл гарсан хугацаа:</label>
		<span class="bolder" id="created_dt"></span>
	</div>
	<div>
		<label for="location_id">Байршил:</label> <span class="bolder"
			id="location_id"></span>
	</div>
	<div>
		<label for="equipment_id">Тоног төхөөрөмж:</label> <span
			class="bolder" id="equipment_id"></span>
	</div>
	<div>
		<label for="reason">Шалтгаан:</label> <span class="bolder" id="reason"
			class="def"></span>
	</div>
	<div>
		<label>Гэмтлийн төрөл:</label>
		<span class="bolder" id="log_type"></span>
	</div>
	<div>
		<label for="defect">Гэмтсэн модуль:</label> <span class="bolder"
			id="node" class="def"></span>
	</div>
	<div>
			<label>Хаасан хугацаа:</label> <span class="bolder" id="closed_dt"></span>
		</div>
		<div>
			<label for="duration">Үргэлжилсэн хугацаа:</label> <span
				class="bolder" id="duration"></span>
		</div>
		<div>
			<label for="closedby_id">Бүртгэл хаасан:</label> <span class="bolder"
				id='closedby_id'></span>
		</div>
		<div>
			<label for="completion">Засварласан байдал:</label>
			<p class="bolder" id="completion" class="def"></p>
		</div>	
	<div>    
	<h4 style="text-align: center;">Аюулыг шинжилж, эрсдлийг тогтоох</h4>  
      <label for="level">Хүндрэл:</label>
      <span id='level'></span>        
    </div>
	<div>        
	<label >Магадлал:</label>
        <span id='num'></span>
    </div>       
    <div class="field" id='_file'>
		<Label>Тайлангийн файл:</Label> 
                <input type="file" name="userfile" value="" id="cert_file">
			<i>Файлаа оруулсны дараа дэлгэрэнгүй хэсэг дээр дарж татаж авах боломжтой!</i>
	</div>  

</form>